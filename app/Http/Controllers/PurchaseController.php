<?php namespace App\Http\Controllers;

use App\User;
use App\Course;
use App\CourseKey;
use App\CourseModuleDocument;
use App\UsersCoursesRegistration;
use App\Http\Requests\CourseRegisterRequest;
use App\Jobs\SendPurchaseConfirmationEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PurchaseController extends Controller {
    public function handleRegister(Course $course, CourseRegisterRequest $request)
    {
        $user = Auth::user();
        $alreadyRegistered = UsersCoursesRegistration::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (!empty($alreadyRegistered)) {
            return response()->json([
                'key' => 'You are already registered for this course.'
            ], 422);
        }

        $courseKey = CourseKey::where('course_id', $course->id)
            ->where('key', $request->key)
            ->where('redeemed', false)
            ->where('enabled', true)
            ->first();

        if (empty($courseKey)) {
            return response()->json([
                'key' => 'Invalid course key.'
            ], 422);
        }

        DB::transaction(function () use($course, $courseKey, $user) {
            $registration = new UsersCoursesRegistration();
            $registration->user_id = $user->id;
            $registration->course_id = $course->id;
            $registration->method = 'key';
            $registration->reference = $courseKey->key;
            $registration->registered_at = Carbon::now()->toDateTimeString();
            $registration->payment_status = 'Completed';
            $registration->save();

            $courseKey->redeemed = true;
            $courseKey->redeemed_at = Carbon::now()->toDateTimeString();
            $courseKey->redeemed_user_id = $user->id;
            $courseKey->save();
        });

        session()->flash('courseMessage', 'You have successfully registered for this course!');
        return response()->json([
            'success' => true,
            'redirect' => action('CourseController@show', [
                'course' => $course,
            ])
        ]);
    }

    public function handlePaypalCheckout($slug, Request $request)
    {
        $course = Course::findBySlugOrId($slug);
        if (!$course->enabled) {
            return response()->view('errors.404');
        }

        $user = Auth::user();
        $registration = UsersCoursesRegistration::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        // handling paypal checkout parameters
        if ($request->isMethod('post') &&
            $request->input('payment_type') === 'instant' &&
            $request->input('txn_type') === 'web_accept' &&
            !empty($request->input('txn_id'))
        ) {
            if ($registration === null) {
                $registration = new UsersCoursesRegistration();
                $registration->user_id = $user->id;
                $registration->course_id = $course->id;
                $registration->registered_at = Carbon::now()->toDateTimeString();
            }

            $registration->method = 'paypal';
            $registration->reference = $request->input('txn_id');
            $registration->payment_status = $request->input('payment_status');
            $registration->save();

            if ($request->input('payer_status') === 'VERIFIED' &&
                $request->input('payment_status') === 'Completed'
            ) {
                session()->flash('courseMessage', 'Thanks for your purchase. You can browse the modules now.');
            } else {
                session()->flash('courseMessage', 'Thanks for your purchase, we will send you the confirmation email once we verify the transaction.');
            }

        }

        return redirect()->action('CourseController@show', $course->slug);
    }

    public function handlePaypalIPN(Request $request)
    {
        \Log::info('handlePaypalIPN', [
            $request->all()
        ]);

        // handling paypal IPN parameters
        if ($request->isMethod('post') &&
            $request->input('payment_type') === 'instant' &&
            $request->input('txn_type') === 'web_accept' &&
            !empty($request->input('txn_id'))
        ) {

            $userIdAndCourseId = explode('-', $request->input('custom'));
            $userId = $userIdAndCourseId[0];
            $courseId = $userIdAndCourseId[1];

            $registration = UsersCoursesRegistration::where('user_id', $userId)
                ->where('course_id', $courseId)
                ->first();

            if ($registration === null) {
                $registration = new UsersCoursesRegistration();
                $registration->user_id = $userId;
                $registration->course_id = $courseId;
                $registration->registered_at = Carbon::now()->toDateTimeString();
            } else if ($registration->payment_status === 'Completed') {
                $this->dispatch(new SendPurchaseConfirmationEmail($registration->user, $registration->course));
                return response('OK', 200);
            }

            $registration->method = 'paypal';
            $registration->reference = $request->input('txn_id');
            $registration->payment_status = $request->input('payment_status');
            $registration->save();

            if ($registration->payment_status === 'Completed') {
                $this->dispatch(new SendPurchaseConfirmationEmail($registration->user, $registration->course));
            }
        }

        return response('OK', 200);
    }
}
