<?php

return [
    's3' => [
        'access_key'    => env('S3_ACCESS_KEY'),
        'secret_key'    => env('S3_SECRET_KEY'),
        'region'        => env('S3_REGION'),
        'bucket'        => env('S3_BUCKET'),
        'algorithm'     => env('S3_ALGORITHM'),
        'max_size'      => env('S3_MAX_SIZE'),
    ],
];
