<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Project;
use Embed\Embed;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Spatie\MediaLibrary\Media;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

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

            case 'links':
              $medias->where('mime_type', 'link');
              break;
          }
        }

        $medias = $medias->latest()->paginate(10);

        $medias->map(function ($media) {
          if($media->mime_type == 'link'){
            $media['download_url'] = $media->getCustomProperty('url');
            $media['public_url'] = $media->getCustomProperty('image');
            $media['thumb_url'] = $media->getCustomProperty('thumb');
          } else {
            $media['download_url'] = URL::signedRoute('download', ['media_id' => $media->id]);
            $media['public_url'] = url($media->getUrl());
            $media['thumb_url'] = url($media->getUrl('thumb'));
          }
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
      request()->validate([
          'url' => 'required|url'
        ]);

      try {
        DB::beginTransaction();
        $user = auth()->user();
        
        $project = Project::findOrFail($project_id);
        $info   = Embed::create(request()->url);

        if (!$info) {
          throw new \Exception("Error! Invalid url.", 422);
        }

        $media = $project->createMediaLink([
            'name' => $info->title,
            'file_name' => $info->providerName. ' Link',
            'custom_properties' => [
              'ext' => 'link',
              'url' => request()->url,
              'thumb' => $info->providerIcon,
              'image' => $info->image,
              'description' => $info->description,
              'type' => $info->type,
              'embed' => $info->code,
              'embed_width' => $info->width,
              'embed_height' => $info->height,
              'embed_ratio' => $info->aspectRatio,
              'user' => $user
            ]
        ]);
        
        $activity = activity('files')->performedOn($project)
                       ->causedBy($user)
                       ->withProperties([
                          'company_id' => $user->company()->id,
                          'media' => [$media],
                          'thumb_url' => $media->getCustomProperty('thumb')
                        ])
                       ->log($user->first_name.' linked a file.');

        // $activity = Activity::where('properties->media->id', $media->id)->first();

        $activity->users()->attach($user->company()->membersID());

        DB::commit();

        $media['download_url'] = $media->getCustomProperty('url');
        $media['public_url'] = $media->getCustomProperty('image');
        $media['thumb_url'] = $media->getCustomProperty('thumb');

        return $media->toJson();

      } catch (\Exception $e) {
        DB::rollback();

        return response()->json([
                'error'=> $e->getMessage(),
                'code' => $e->getCode()
              ], 422);
      }
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
          return response()->json(['Invalid file format.'], 422);
          
        try {
          DB::beginTransaction();

          $type = $this->fileType($file);

          $project = Project::findOrFail($project_id);

          $media = $project->addMedia($file)
                           ->withCustomProperties([
                            'ext' => $file->extension(),
                            'user' => auth()->user()
                           ])
                           ->toMediaCollection($collectionName);

          $log = auth()->user()->first_name.' uploaded '.$type.' on project '.$project->title;

          $activity = activity('files')
                      ->performedOn($project)
                      ->causedBy(auth()->user())
                      ->withProperties([
                            'company_id' => auth()->user()->company()->id,
                            'media' => [$media],
                            'thumb_url' => $media->getUrl('thumb')
                          ])
                      ->log($log);


          // $activity = Activity::where('properties->media->id', $media->id)->first();

          $activity->users()->attach(auth()->user()->company()->membersID());

          DB::commit();

          $media['download_url'] = URL::signedRoute('download', ['media_id' => $media->id]);
          $media['public_url'] = url($media->getUrl());
          $media['thumb_url'] = url($media->getUrl('thumb'));
          $media['log_id'] = $activity->id;

          return $media->toJson();

        } catch (\Exception $e) {
          DB::rollback();
          $error = strripos($e->getMessage(), 'maximum allowed') !== false ? 'File size exceed max limit.' : $e->getMessage();
          return response()->json([ $error ], 422);
        }
    }

    public function delete($id)
    {
        $model = Media::findOrFail($id);
        
        return $model->destroy($id);
    }

    public function uploadImage()
    {
        request()->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif'
        ]);
        $file = request()->file('file');
        try {

            $image = Image::make($file);
            $fileName = Str::slug(preg_replace("/\.[^.]+$/", "", $file->getClientOriginalName())).'.'.$file->getClientOriginalExtension();
            $folder = 'uploads/'.date('Y/m').'/';

            $file = $file->storeAs($folder, $fileName, 'public');
            $filePath = $folder . $fileName;

            return response()->json([
                        'fileName' => $fileName,
                        'url'      => url(Storage::url($filePath))
                ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 522);   
        }
    }
}
