<?php

namespace App\Http\Controllers;

use Spatie\MediaLibrary\Models\Media;
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

    /**
     * @return mixed
     */
    public function download()
    {
        if (!request()->hasValidSignature()) {
            abort(401);
        }

        $media = Media::findOrFail(request()->media_id);

        return Storage::download("public/" . str_replace(["storage/", "%20"], ["", " "], $media->getUrl()));
    }

    /**
     *
     */
    public function arc()
    {
        //to be continue
    }
}
