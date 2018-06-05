<?php

namespace App\Jobs;

use App\User;
use App\Course;
use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Mail\Mailer;

class SendPurchaseConfirmationEmail extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $user;

    protected $course;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, Course $course)
    {
        $this->user = $user;
        $this->course = $course;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        $mailer->send('emails.purchased', ['user' => $this->user, 'course' => $this->course], function ($m) {
            $m->to($this->user->email, $this->user->first_name . ' ' . $this->user->last_name)->subject('Trauma Analytics Purchase Confirmation');
        });
    }
}
