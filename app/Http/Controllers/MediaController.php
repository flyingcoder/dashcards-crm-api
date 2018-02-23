<?php

namespace App\Http\Controllers;

use Spatie\MediaLibrary\Media;
use Illuminate\Http\Request;
use App\Project;
use Storage;

class MediaController extends Controller
{
  	private $allowedDocs = [
  		'docx', 'csv', 'doc', 'pdf', 'ppt'
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
        return Media::where('model_id', $project_id)
                      ->where('model_type', 'App\Project')
                      ->paginate(10);
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

        $project = Project::findOrFail($project_id);

        $project->addMedia(request()->url)
                ->withCustomProperties(['ext' => request()->extention])
                ->toMediaCollection($collectionName);
    }

    public function collectionName(Request $request)
    {
        $collectionName = '';

        if(collect($this->allowedDocs)->contains($request->file('file')->extension())) {
          $collectionName = 'project.files.documents';
        } else if (collect($this->allowedImages)->contains($request->file('file')->extension())) {
          $collectionName = 'project.files.images';
        } else if (collect($this->allowedVideos)->contains($request->file('file')->extension())) {
          $collectionName = 'project.files.videos';
        } else if (collect($this->allowedOtherFiles)->contains($request->file('file')->extension())) {
          $collectionName = 'project.files.others';
        } else {
          return response('Invalid file', 402);
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

      	$project = Project::findOrFail($project_id);

      	$project->addMedia(request()->file('file'))
               ->withCustomProperties(['ext' => request()->file('file')->extension()])
      		     ->toMediaCollection($collectionName);
    }


}
