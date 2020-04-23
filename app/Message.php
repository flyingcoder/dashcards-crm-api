<?php

namespace App;

use App\Traits\HasFileTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Musonza\Chat\Models\Message as Msg;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;
use Spatie\MediaLibrary\Media;

class Message extends Msg implements HasMediaConversions
{
	use HasMediaTrait, HasFileTrait;

    public function sender()
    {
    	return $this->belongsTo(User::class, 'user_id');
    }

    public function getFiles()
    {

    	$medias = $this->media;
    	$data = collect([]);
    	if (!$medias->isEmpty()) {
    		foreach ($medias as $key => $media) {
		    	$media->download_url = URL::signedRoute('download', ['media_id' => $media->id]);
		        $media->public_url = url($media->getUrl());
		        $media->thumb_url = url($media->getUrl('thumb'));
    			$data->push($media);
    		}
    	}
        return $data;
    }

    public function getFile()
    {

    	$media = $this->media()->first();
    	if ($media) {
	    	$media->download_url = URL::signedRoute('download', ['media_id' => $media->id]);
	        $media->public_url = url($media->getUrl());
	        $media->thumb_url = url($media->getUrl('thumb'));
	        $media->category = $this->getFileCategory($media);
    	}
        return $media;
    }
	/**
     *
     * Media library image convertion
     *
     */
    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
              ->width(368)
              ->height(232)
              ->sharpen(10);
    }

}
