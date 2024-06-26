<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserCredentials extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $link;
    public $login_link;
    public $password;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $password = null)
    {
        $this->user = $user;
        $this->password = $password;
        //one-time use unique codes link for setting password
        $this->link = config('app.frontend_url').'/set-password/'.$user->email.'/'.$user->getPasswordResetToken(); 
        $this->login_link = config('app.frontend_url').'/login';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if (!is_null($this->password)) {
            return $this->view('email.user-credentials');
        } else {
            return $this->view('email.user-reset-password');
        }
    }
}
