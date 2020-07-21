<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DynamicEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $from_email;
    private $from_name;

    public $contents;
    public $now;

    /**
     * Create a new message instance.
     *
     * @param $contents
     * @param $subject
     * @param $from
     */
    public function __construct($contents, $subject, $from = null)
    {
        $this->now = Carbon::now();
        $this->contents = $contents;
        $this->subject = $subject;
        if (is_null($from)) {
            $this->from_email = config('mail.from.address', 'admin@dashcards.com');
            $this->from_name = config('mail.from.name', config('app.name'));
        } else {
            $this->from_email = $from->email;
            $this->from_name = $from->fullname ?? '';
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('[' . config('app.name') . '] ' . $this->subject)
            ->from($this->from_email, $this->from_name)
            ->view('email.dynamic-email');
    }
}
