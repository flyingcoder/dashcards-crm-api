<?php

namespace App\Observers;

use App\MediaLink;

class MediaLinkObserver
{
    /**
     * Handle the media link "created" event.
     *
     * @param  \App\MediaLink  $mediaLink
     * @return void
     */
    public function created(MediaLink $mediaLink)
    {
        $mediaLink->order_column = $mediaLink->id;
        $mediaLink->save();
    }

    /**
     * Handle the media link "updated" event.
     *
     * @param  \App\MediaLink  $mediaLink
     * @return void
     */
    public function updated(MediaLink $mediaLink)
    {
        //
    }

    /**
     * Handle the media link "deleted" event.
     *
     * @param  \App\MediaLink  $mediaLink
     * @return void
     */
    public function deleted(MediaLink $mediaLink)
    {
        //
    }

    /**
     * Handle the media link "restored" event.
     *
     * @param  \App\MediaLink  $mediaLink
     * @return void
     */
    public function restored(MediaLink $mediaLink)
    {
        //
    }

    /**
     * Handle the media link "force deleted" event.
     *
     * @param  \App\MediaLink  $mediaLink
     * @return void
     */
    public function forceDeleted(MediaLink $mediaLink)
    {
        //
    }
}
