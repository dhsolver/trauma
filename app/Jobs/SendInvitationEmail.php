<?php

namespace App\Jobs;

use App\User;
use App\Jobs\Job;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Password;
use Illuminate\Mail\Message;

class SendInvitationEmail extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        $mailer->send('emails.invited', ['user' => $this->user], function ($m) {
            $m->to($this->user->email, $this->user->first_name . ' ' . $this->user->last_name)->subject('You\'re invited to join Trauma Analytics');
        });

        $credentials = ['email' => $this->user->email];

        $response = Password::sendResetLink($credentials, function (Message $message) {
            $message->subject('Set your Trauma Analytics password');
        });
    }
}
