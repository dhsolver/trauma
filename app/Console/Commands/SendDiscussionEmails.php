<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\UsersCoursesRegistration;
use Illuminate\Support\Facades\Mail;

class SendDiscussionEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:discussion_reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends out emails to remind users about courses discussion';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = User::all();
        foreach ($users as $key => $user) {
            $courses = [];
            if (!$user->discussion_emails_enabled) continue;
            foreach ($user->registrations as $key => $registration) {
                $comments = $registration->course->comments()
                    ->where('user_id', '<>', $user->id)
                    ->where('created_at', '>', $registration->last_discussion_at)
                    ->get()->toArray();

                if (count($comments) > 0) {
                    $courses[] = array(
                        'course' => $registration->course,
                        'comments' => count($comments)
                    );
                }
            }
            if (count($courses) > 0) {
                Mail::send('emails.discussion', ['user' => $user, 'courses' => $courses], function ($m) use ($user) {
                    $m->to($user->email,$user->first_name . ' ' . $user->last_name)->subject('You have unread comments on Trauma Analytics courses');
                });
            }
        }
    }
}
