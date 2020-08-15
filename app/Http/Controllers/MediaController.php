<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Comment;
use App\Events\NewMediaCommentCreated;
use App\Project;
use App\Traits\HasFileTrait;
use Embed\Embed;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Spatie\MediaLibrary\Models\Media;

class MediaController extends Controller
{
    use HasFileTrait;
    /**
     * @var float|int
     */
    protected $max_file_size = (1024 * 10); //10mb

    /**
     * @param $project_id
     * @return mixed
     */
    public function projectMedia($project_id)
    {
        $medias = Media::where('model_id', $project_id)
            ->where('model_type', 'App\Project');

        if (request()->has('type')) {

            switch (request()->type) {
                case 'image':
                case 'images':
                    $mime_type = $this->categories('images');
                    $medias->whereIn('mime_type', $mime_type);
                    break;

                case 'video':
                case 'videos':
                    $mime_type = $this->categories('videos');
                    $medias->whereIn('mime_type', $mime_type);
                    break;

                case 'document':
                case 'documents':
                    $mime_type = $this->categories('documents');
                    $medias->whereIn('mime_type', $mime_type);
                    break;

                case 'other':
                case 'others':
                    $mime_type = $mime_type = $this->categories('others');
                    $medias->whereIn('mime_type', $mime_type);
                    break;

                case 'link':
                case 'links':
                    $medias->where('mime_type', 'link');
                    break;
            }
        }

        $medias = $medias->latest()->paginate(15);

        $medias->map(function ($media) {
            $media = $this->getFullMedia($media);
            return $media;
        });

        return $medias;
    }

    /**
     * @param $project_id
     * @return mixed
     */
    public function projectMediaAll($project_id)
    {
        return Media::where('model_id', $project_id)
            ->where('model_type', 'App\Project')
            ->get();
    }

    /**
     * @param $project_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function addMediaLink($project_id)
    {
        request()->validate([
            'url' => 'required|url'
        ]);

        try {
            DB::beginTransaction();
            $user = auth()->user();

            $project = Project::findOrFail($project_id);
            $info = Embed::create(request()->url);

            if (!$info) {
                throw new \Exception("Error! Invalid url.", 422);
            }

            $media = $project->createMediaLink([
                'name' => $info->title,
                'file_name' => $info->providerName . ' Link',
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
                ->log($user->first_name . " linked a file on $project->type " . $project->title);

            $activity = Activity::findOrFail($activity->id);
            $activity->users()->attach($user->company()->membersID());

            DB::commit();

            $media = $this->getFullMedia($media);

            return $media->toJson();

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'error' => $e->getMessage(),
                'code' => $e->getCode()
            ], 422);
        }
    }

    /**
     * Create a new controller instance.
     *
     * @param $project_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function projectFileUpload($project_id)
    {
        request()->validate([
            'file' => 'required|max:' . $this->max_file_size
        ]);

        $file = request()->file('file');
        $collectionName = $this->getProjectCollectionName($file, true);

        if (!$collectionName)
            return response()->json(['Invalid file format.'], 422);

        try {
            DB::beginTransaction();

            $project = Project::findOrFail($project_id);

            $media = $project->addMedia($file)
                ->withCustomProperties([
                    'ext' => $file->extension(),
                    'user' => auth()->user()
                ])
                ->toMediaCollection($collectionName);

            $media = $this->getFullMedia($media);

            $activity = false;
            if (request()->has('file_upload_session') && request()->file_upload_session) {
                $activity = Activity::where('properties->session', request()->file_upload_session)->latest()->first();
            }
            if ($activity) {
                $current_props = json_decode($activity->properties, true);
                $current_props['media'][] = $media;
                $activity->properties = $current_props;
                $activity->save();
            } else {
                $log = auth()->user()->first_name . " uploaded file(s) on $project->type " . $project->title;
                $activity = activity('files')
                    ->performedOn($project)
                    ->causedBy(auth()->user())
                    ->withProperties([
                        'session' => request()->file_upload_session ?? '',
                        'company_id' => auth()->user()->company()->id,
                        'media' => [$media],
                        'thumb_url' => $media->getUrl('thumb')
                    ])
                    ->log($log);
                $activity = Activity::findOrFail($activity->id);
                $activity->users()->attach($project->teamIds());
            }

            DB::commit();

            return $media->toJson();
        } catch (Exception $e) {
            DB::rollback();
            $error = strripos($e->getMessage(), 'maximum allowed') !== false ? 'File size exceed max limit.' : $e->getMessage();
            return response()->json([$error], 422);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        $model = Media::findOrFail($id);

        if ($model->delete()) {
            return response()->json(['message' => 'Successfully deleted'], 200);
        }
        return response()->json(['message' => 'Error! Cant delete this file'], 522);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadImage()
    {
        request()->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif'
        ]);
        $file = request()->file('file');
        $root_folder = request()->folder ?? 'uploads';
        try {
            $image = Image::make($file);
            $extension = $file->getClientOriginalExtension();
            $extension = trim($extension) == '' ? 'png' : $extension;
            $file_name = request()->has('name') ? request()->name : $file->getClientOriginalName();
            $file_name = trim($file_name) == 'blob' ? now()->format('YmdHis') : $file_name . now()->format('-YmdHis');

            $fileName = Str::slug(preg_replace("/\.[^.]+$/", "", $file_name)) . '.' . $extension;
            $folder = $root_folder.'/' . date('Y/m') . '/';

            $file->storeAs($folder, $fileName, "public");
            $filePath = $folder . $fileName;

            return response()->json([
                'fileName' => $fileName,
                'filepath' => $filePath,
                'url' => url(Storage::url($filePath)),
                'public_url' => url(Storage::url($filePath))
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 522);
        }
    }

    /**
     * $id = project id
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulkDeleteFiles($id)
    {
        request()->validate([
            'ids' => 'required|array'
        ]);

        $project = Project::findOrFail($id);
        $files = Media::where('model_id', $project->id)
            ->where('model_type', 'App\Project')
            ->whereIn('id', request()->ids)
            ->get();
        if (!$files->isEmpty()) {
            foreach ($files as $key => $file) {
                $file->delete();
            }
        }

        return response()->json(['message' => $files->count() . ' project(s)  successfully deleted'], 200);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function fetchComments($id)
    {
        $media = \App\Media::findOrFail($id);
        $comments = $media->comments()->latest()->paginate(10);

        $items = $comments->getCollection();

        $data = collect([]);
        foreach ($items as $key => $comment) {
            $comment->body = getFormattedContent($comment->body);
            $comment->load('causer');
            $data->push(array_merge($comment->toArray()));
        }
        $comments->setCollection($data);
        return $comments;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function addComment($id)
    {
        $media = \App\Media::findOrFail($id);
        request()->validate([
            'body' => 'required'
        ]);

        $comment = new Comment([
            'body' => request()->body,
            'causer_id' => auth()->user()->id,
            'causer_type' => 'App\User'
        ]);

        $new_comment = $media->comments()->save($comment);
        $new_comment->load('causer');

        NewMediaCommentCreated::dispatch($media, $new_comment);

        return $new_comment;
    }

    /**
     * @param $id
     * @param $comment_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteComment($id, $comment_id)
    {
        $media = \App\Media::findOrFail($id);
        $comment = $media->comments()->where('id', $comment_id)->firstOrfail();

        $comment->delete();

        return response()->json(['message' => 'Comment deleted!'], 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus($id)
    {
        $media = \App\Media::findOrFail($id);
        $media->approved = request()->action;
        $media->save();

        return response()->json(['message' => 'File status updated!'], 200);
    }
}
