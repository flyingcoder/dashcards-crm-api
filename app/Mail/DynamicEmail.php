<?php

namespace App\Mail;

use App\Invoice;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DynamicEmail extends Mailable
{

    use Queueable, SerializesModels;

    /**
     * @var parse content
     */
    public $contents;

    public $now;

    public $subject;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($contents, $subject )
    {
        $this->now = Carbon::now();
        $this->contents = $contents;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('[' . config('app.name') . '] '.$this->subject)
                    ->view('email.dynamic-email');
    }
}
