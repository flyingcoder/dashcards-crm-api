<?php

namespace App\Http\Controllers;

use App\Activity;
use Spatie\MediaLibrary\Media;
use Illuminate\Http\Request;
use App\Project;
use Storage;

class MediaController extends Controller
{
  	private $allowedDocs = [
  		'docx', 'csv', 'doc', 'pdf', 'pptx', 'pps'
  	]; 

  	private $allowedImages = [
  		'png', 'jpg', 'jpeg', 'bmp', 'ico', 'svg', 'psd'
  	];

  	private $allowedVideos = [
  		'mp4', 'mov', 'avi'
  	];

  	private $allowedOtherFiles = [
  		'zip'
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
                  'application/zip',
                  'application/octet-stream',
                  'application/x-zip-compressed',
                  'multipart/x-zip'
                ];

              $medias->whereIn('mime_type', $mime_type);
              break;
          }
        }

        $medias = $medias->paginate(10);

        $medias->map(function ($media) {
           $media['public_url'] = $media->getUrl();
           $media['thumb_url'] = $media->getUrl('thumb');
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
        $collectionName = $this->collectionName(request());

        if(!$collectionName)
          return response('Invalid file format.', 422);
        //$type = $this->fileType(request());

        $project = Project::findOrFail($project_id);

        $media = $project->addMedia(request()->url)
                ->withCustomProperties(['ext' => request()->extention])
                ->toMediaCollection($collectionName);

        $log = auth()->user()->first_name.' linked a .'.request()->extention.' file.';

        $activity = activity(auth()->user()->company()->name)
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

    public function collectionName(Request $request)
    {
        $collectionName = '';
        if(request()->has('file')){
        if(collect($this->allowedDocs)->contains($request->file('file')->extension())) {
          $collectionName = 'project.files.documents';
        } else if (collect($this->allowedImages)->contains($request->file('file')->extension())) {
          $collectionName = 'project.files.images';
        } else if (collect($this->allowedVideos)->contains($request->file('file')->extension())) {
          $collectionName = 'project.files.videos';
        } else if (collect($this->allowedOtherFiles)->contains($request->file('file')->extension())) {
          $collectionName = 'project.files.others';
        } else {
          return false;
        }
      }

        return $collectionName;
    }

    public function fileType(Request $request)
    {
        $collectionName = '';
        if(request()->has('file')){
        if(collect($this->allowedDocs)->contains($request->file('file')->extension())) {
          $collectionName = 'documents';
        } else if (collect($this->allowedImages)->contains($request->file('file')->extension())) {
          $collectionName = 'images';
        } else if (collect($this->allowedVideos)->contains($request->file('file')->extension())) {
          $collectionName = 'videos';
        } else if (collect($this->allowedOtherFiles)->contains($request->file('file')->extension())) {
          $collectionName = 'zip';
        } else {
          return false;
        }
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
      	$collectionName = $this->collectionName(request());

        if(!$collectionName)
          return response('Invalid file format.', 422);

        $type = $this->fileType(request());

      	$project = Project::findOrFail($project_id);
        //dd(request()->has('file'));
        if(request()->has('file')){
          $media = $project->addMedia(request()->file('file'))
                           ->withCustomProperties([
                            'ext' => request()->file('file')->extension(),
                            'user' => auth()->user()
                           ])
                           ->toMediaCollection($collectionName);

          $log = auth()->user()->first_name.' uploaded '.$type.' on project '.$project->title;

          activity(auth()->user()->company()->name)
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
      	
    }


}
