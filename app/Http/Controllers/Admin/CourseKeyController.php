<?php

namespace App\Http\Controllers\Admin;

use App\Course;
use App\CourseKey;
use App\Http\Controllers\AdminController;
use App\Http\Requests\Admin\CourseKeyGenerateRequest;

class CourseKeyController extends AdminController {

    private function generateRandomString($length = 20) {
        return substr(str_shuffle(
                str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)))
            ), 1, $length
        );
    }

    public function create(Course $course, CourseKeyGenerateRequest $request)
    {
        for ($i = 0; $i < $request->count; $i++) {
            $key = new CourseKey;
            $key->course_id = $course->id;
            $key->key = $request->prefix.$this->generateRandomString();
            $key->tag = $request->tag;

            $key->save();
        }

        session()->flash('courseMessage', "$request->count key(s) have bene generated.");

        return response()->json([
            'success' => true,
            'redirect' => action('Admin\CourseController@edit', [
                'course' => $course
            ])
        ]);
    }

    private function download_send_headers($filename) {
        // disable caching
        $now = gmdate("D, d M Y H:i:s");
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");

        // force download
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");

        // disposition / encoding on response body
        header("Content-Disposition: attachment;filename={$filename}");
        header("Content-Transfer-Encoding: binary");
    }

    public function export(Course $course)
    {
        $this->download_send_headers('course_keys.csv');

        ob_start();

        // create a file pointer connected to the output stream
        $output = fopen('php://output', 'w');

        // output the column headings
        fputcsv($output, array(
            'Key',
            'Created At',
            'User Id',
            'Full Name',
            'Email',
            'Redeemed At',
        ));
        foreach ($course->keys as $key) {
            fputcsv($output, array(
                $key->key,
                $key->created_at,
                $key->redeemed ? $key->redeemedUser->id : '',
                $key->redeemed ? $key->redeemedUser->first_name.' '.$key->redeemedUser->last_name : '',
                $key->redeemed ? $key->redeemedUser->email : '',
                $key->redeemed ? $key->redeemed_at : '',
            ));
        }

        fclose($output);
        echo ob_get_clean();
        die;
    }
}
