<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function delete($id)
    {
    	$comment = Comment::findOrFail($id);
    	//todo add layer for checking of capabilities to delete a comment

    	if($comment->delete()){
    		return response()->json([
    			'comment_id' => $id,
    			'message' => 'Successfully deleted'
    		], 200);
    	}
    	return response()->json([
    		'message' => "Can't delete comment."
    	], 522);
    }
}
