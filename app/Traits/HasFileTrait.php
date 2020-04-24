<?php

namespace App\Traits;

use Illuminate\Support\Facades\URL;

trait HasFileTrait
{

	protected $categories = [
		'documents' => [
				'application/msword',
				'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
				'text/plain',
				'application/pdf',
				'application/vnd.ms-powerpoint',
				'application/vnd.openxmlformats-officedocument.presentationml.presentation',
				'application/vnd.ms-excel',
				'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
				'text/css',
				'text/html',
				'text/plain',
				'text/richtext',
				'application/envoy',
				'application/octet-stream',
				'application/rtf',
				'application/vnd.ms-works',
				'application/x-gzip',
				'application/x-javascript',
				'application/x-shockwave-flash',
				'application/futuresplash',
				'application/x-troff-me',
				'application/postscript',
				'text/php',
				'text/x-php',
				'application/php',
				'application/x-php',
				'application/x-httpd-php',
				'application/x-httpd-php-source',
				'text/x-sql',
				'text/sql',
			],
		'images' => [
				'image/bmp',
				'image/cis-cod',
				'image/gif',
				'image/ief',
				'image/jpeg',
				'image/png',
				'image/jpg',
				'image/pipeg',
				'image/svg+xml',
				'image/tiff',
				'image/x-cmu-raster',
				'image/x-cmx',
				'image/x-icon',
				'image/x-portable-anymap',
				'image/x-portable-bitmap',
				'image/x-portable-graymap',
				'image/x-portable-pixmap',
				'image/x-rgb',
				'image/x-xbitmap',
				'image/x-xpixmap',
				'image/x-xwindowdump',
				'image/vnd.adobe.photoshop',
				'image/vnd.adobe.photoshop',
				'application/x-photoshop',
				'application/photoshop',
				'application/psd',
			],
		'links' => [
				'link'
			],
		'videos' => [
				'video/mpeg',
				'video/mp4',
				'video/quicktime',
				'video/x-la-asf',
				'video/x-ms-asf',
				'video/x-msvideo',
				'video/x-sgi-movie',
				'video/x-ms-wmv',
			],
		'others' => [
				'application/x-compressed', 
				'application/x-zip-compressed', 
				'application/zip', 
				'multipart/x-zip',
				'audio/basic',
				'audio/mid',
				'audio/mpeg',
				'audio/x-aiff',
				'audio/x-mpegurl',
				'audio/x-pn-realaudio',
				'audio/x-wav',
				'audio/wav',
				'audio/s-wav',
				'audio/wave',
				'application/xml',
				'application/gzip-compressed', 
				'application/gzipped', 
				'application/x-gunzip', 
				'application/x-gzip',
				'application/gzip',
			]
	];

	public function categories($type)
	{
		if ($type == 'all') {
			return $this->categories;
		}

		if (array_key_exists($type, $this->categories)) {
			return $this->categories[$type];
		}

		return [];
	}

	public function getProjectCollectionName($file, $abort_on_not_found = false)
	{
		$mimetype = $file->getMimeType();
		$category = $this->getFileCategoryByMimeType($mimetype);
		if ($category) {
			return 'project.files.'.$category;
		}
		if ($abort_on_not_found) {
			abort(422, 'File type not yet supported!');
		}
		return false;
	}


	public function getFileCategory($media)
	{
		if (!$media) {
			return '';
		}
		$mime_type = $media->mime_type;

		foreach ($this->categories as $type => $category) {
			foreach ($category as $key => $mimetype) {
				if (trim($mime_type) == trim($mimetype)) {
					return $type;
				}
			}
		}

		return 'others';
	}

	public function getFileCategoryByMimeType($mimetype)
	{
		foreach ($this->categories as $type => $category) {
			foreach ($category as $key => $mime_type) {
				if (trim($mime_type) == trim($mimetype)) {
					return $type;
				}
			}
		}
		return false; //not found
	}

	public function getFullMedia($media, $override_thumb = false)
    {
    	if ($media) {
	    	if($media->mime_type == 'link'){
		        $media->download_url = $media->getCustomProperty('url') ?? '';
		        $media->public_url = $media->getCustomProperty('image') ?? '';
		        $media->thumb_url = $media->getCustomProperty('thumb') ?? '';
		    	$media->category = 'links';
		    } else {
		    	$media->download_url = URL::signedRoute('download', ['media_id' => $media->id]);
		        $media->public_url = url($media->getUrl());
		        $media->thumb_url = url($media->getUrl('thumb'));
		        $media->category = $this->getFileCategory($media);
		    }
	        $media->image_exist = true;
    	}
        return $media;
    }
}


