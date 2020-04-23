<?php

namespace App\Traits;

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
				'application/x-troff-me',
				'application/postscript',
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
				'audio/x-pn-realaudio',
				'audio/x-wav',
				'application/xml'
			]
	];

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
}


