<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Project;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\Media;
use Storage;
use URL;

class MediaController extends Controller
{
  	private $allowedDocs = [
  		'docx', 'csv', 'doc', 'pdf', 'pptx', 'pps', 'txt'
  	]; 

  	private $allowedImages = [
  		'png', 'jpg', 'jpeg', 'bmp', 'ico', 'svg', 'psd', 'gif'
  	];

  	private $allowedVideos = [
  		'mp4', 'mov', 'avi'
  	];

  	private $allowedOtherFiles = [
  		'zip', 'rar'
  	];


    public function projectMedia($project_id)
    {
        $medias = Media::where('model_id', $project_id)
                       ->where('model_type', 'App\Project');
                      
        if(request()->has('type')) {
          
          switch (request()->type) {
            case 'images':
              $medias->where('mime_type', 'like', 'image/%');
              break;

            case 'videos':
              $medias->where('mime_type', 'like', 'video/%');
              break;

            case 'documents':
              $mime_type = [
                  'application/msword',
                  'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                  'text/plain',
                  'application/pdf',
                  'application/vnd.ms-powerpoint',
                  'application/vnd.openxmlformats-officedocument.presentationml.presentation'
                ];

              $medias->whereIn('mime_type', $mime_type);
              break;

            case 'others':
              $mime_type = [
                  'application/rar',
                  'application/zip',
                  'application/octet-stream',
                  'application/x-zip-compressed',
                  'multipart/x-zip'
                ];

              $medias->whereIn('mime_type', $mime_type);
              break;
          }
        }

        $medias = $medias->latest()->paginate(10);

        $medias->map(function ($media) {
           $media['download_url'] = URL::signedRoute('download', ['media_id' => $media->id]);
           $media['public_url'] = url($media->getUrl());
           $media['thumb_url'] = url($media->getUrl('thumb'));
           return $media;
        });

        return $medias;
    }

    public function projectMediaAll($project_id)
    {
         return Media::where('model_id', $project_id)
                      ->where('model_type', 'App\Project')
                      ->get();
    }

    public function addMediaLink($project_id)
    {
        $type = $this->fileType(request());

        $media = $project->addMedia(request()->url)
                ->withCustomProperties(['type' => 'link'])
                ->toMediaCollection($collectionName);

        $log = auth()->user()->first_name.' linked a file.';

        $activity = activity('files')
                       ->performedOn($project)
                       ->causedBy(auth()->user())
                       ->withProperties([
                          'company_id' => auth()->user()->company()->id,
                          'media' => $media,
                          'thumb_url' => $media->getUrl('thumb')
                        ])
                       ->log($log);

        $activity = Activity::latest()->first();

        $activity->users()->attach(auth()->user()->company()->membersID());

        return $activity;
        
    }

    public function collectionName($file)
    {
        $collectionName = '';
        if(collect($this->allowedDocs)->contains($file->extension())) {
          $collectionName = 'project.files.documents';
        } else if (collect($this->allowedImages)->contains($file->extension())) {
          $collectionName = 'project.files.images';
        } else if (collect($this->allowedVideos)->contains($file->extension())) {
          $collectionName = 'project.files.videos';
        } else if (collect($this->allowedOtherFiles)->contains($file->extension())) {
          $collectionName = 'project.files.others';
        } else {
          return false;
        }

        return $collectionName;
    }

    public function fileType($file)
    {
        $collectionName = '';

        if(collect($this->allowedDocs)->contains($file->extension())) {
          $collectionName = 'documents';
        } else if (collect($this->allowedImages)->contains($file->extension())) {
          $collectionName = 'images';
        } else if (collect($this->allowedVideos)->contains($file->extension())) {
          $collectionName = 'videos';
        } else if (collect($this->allowedOtherFiles)->contains($file->extension())) {
          $collectionName = 'zip';
        } else {
          return false;
        }

        return $collectionName;
    }


   /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function projectFileUpload($project_id)
    {
        $file = request()->file('file');

        $collectionName = $this->collectionName($file);

        if(!$collectionName)
          return response('Invalid file format.', 422);

        $type = $this->fileType($file);

        $project = Project::findOrFail($project_id);

        $media = $project->addMedia($file)
                         ->withCustomProperties([
                          'ext' => $file->extension(),
                          'user' => auth()->user()
                         ])
                         ->toMediaCollection($collectionName);

        $log = auth()->user()->first_name.' uploaded '.$type.' on project '.$project->title;

        activity('files')
              ->performedOn($project)
              ->causedBy(auth()->user())
              ->withProperties([
                    'company_id' => auth()->user()->company()->id,
                    'media' => $media,
                    'thumb_url' => $media->getUrl('thumb')
                  ])
              ->log($log);



        $activity = Activity::where('properties->media->id', $media->id)->first();

        $activity->users()->attach(auth()->user()->company()->membersID());

        $media['download_url'] = URL::signedRoute('download', ['media_id' => $media->id]);

        $media['public_url'] = url($media->getUrl());

        $media['thumb_url'] = url($media->getUrl('thumb'));

        return $media->toJson();
    }

    public function delete($id)
    {
        $model = Media::findOrFail($id);
        
        return $model->destroy($id);
    }
}
