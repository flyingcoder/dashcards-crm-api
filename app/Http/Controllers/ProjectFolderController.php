<?php

namespace App\Http\Controllers;

use App\Project;
use App\ProjectFolder;
use Illuminate\Http\Request;

class ProjectFolderController extends Controller
{
    public function projectFolders($id, $source)
    {
        $project =  Project::findOrFail($id);
        $folders = $project->projectFolders()
            ->where('user_id', auth()->user()->id)
            ->where('source', '=', $source)
            ->get();
        
        return $folders;
    }

    public function store($id, $source)
    {
    	request()->validate([
    		'folders' => 'required|array'
    	]);

        $folders = request()->folders;
       
        $response = collect([]);

        foreach($folders as $key => $folder){
        	$folder  = (array) $folder;
        	if (array_key_exists('id', $folder)) {
        		$res  = ProjectFolder::create([
        				'user_id' => auth()->user()->id,
        				'name' => $folder['name'],
        				'source' => $source,
        				'project_id' => $id,
        				'properties' => [ 'mimeType' => $folder['mimeType'] ],
        				'folder_id' => $folder['id'],
        				'created_at' => now()->format('Y-m-d H:i:s')
		        	]);
        		$response->push($res);
        	}
        }
        
        return response()->json($response->toArray(), 201);
    }

    public function delete($id, $source, $folder_id)
    {
    	$folder = ProjectFolder::findOrFail($folder_id);
    	$folder->delete();

        return response()->json(['message' => 'Successfully deleted'], 200);
    }

    public function deleteByFolderId($id, $source, $folder_id)
    {
    	$folder = ProjectFolder::where('folder_id',$folder_id)->firstOrFail();
    	$folder->delete();

        return response()->json(['message' => 'Successfully deleted'], 200);
    }
}
