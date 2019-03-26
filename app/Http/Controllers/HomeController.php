<?php

namespace App\Http\Controllers;

use Spatie\MediaLibrary\Media;
use Illuminate\Http\Request;
use Storage;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function download()
    {
        $media = Media::findOrFail(request()->media_id);

        return Storage::download("public/".str_replace("storage/", "", $media->getUrl()));
    }
}
