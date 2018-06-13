<?php

function getS3Url($fileKey) {
    if (empty($fileKey)) {
        return url('images/no_photo.png');
    }

    $region             = Config::get('aws.s3.region');
    $bucket_name        = Config::get('aws.s3.bucket');
    return "//s3-$region.amazonaws.com/$bucket_name/$fileKey";
}

function prepareS3Data(){
    $access_key         = Config::get('aws.s3.access_key');
    $secret_key         = Config::get('aws.s3.secret_key');
    $bucket_name        = Config::get('aws.s3.bucket');
    $region             = Config::get('aws.s3.region');
    $allowd_file_size   = Config::get('aws.s3.max_size');

    //dates
    $short_date         = gmdate('Ymd'); //short date
    $iso_date           = gmdate("Ymd\THis\Z"); //iso format date
    $expiration_date    = gmdate('Y-m-d\TG:i:s\Z', strtotime('+1 hours')); //policy expiration 1 hour from now

    //POST Policy required in order to control what is allowed in the request
    //For more info http://docs.aws.amazon.com/AmazonS3/latest/API/sigv4-HTTPPOSTConstructPolicy.html
    $policy = utf8_encode(json_encode(array(
            'expiration' => $expiration_date,
                'conditions' => array(
                    array('acl' => 'public-read'),
                    array('bucket' => $bucket_name),
                    array('starts-with', '$key', ''),
                    array('content-length-range', '1', $allowd_file_size),
                    array('x-amz-credential' => $access_key.'/'.$short_date.'/'.$region.'/s3/aws4_request'),
                    array('x-amz-algorithm' => Config::get('aws.s3.algorithm')),
                    array('X-amz-date' => $iso_date)
                )
            )));

    //Signature calculation (AWS Signature Version 4)
    //For more info http://docs.aws.amazon.com/AmazonS3/latest/API/sig-v4-authenticating-requests.html
    $kDate = hash_hmac('sha256', $short_date, 'AWS4' . $secret_key, true);
    $kRegion = hash_hmac('sha256', $region, $kDate, true);
    $kService = hash_hmac('sha256', "s3", $kRegion, true);
    $kSigning = hash_hmac('sha256', "aws4_request", $kService, true);
    $signature = hash_hmac('sha256', base64_encode($policy), $kSigning);

    return array(
        'bucket' => $bucket_name,
        'region' => $region,
        'access_key' => $access_key,
        'short_date' => $short_date,
        'hash_algorithm' => Config::get('aws.s3.algorithm'),
        'iso_date' => $iso_date,
        'policy' => base64_encode($policy),
        'signature' => $signature,
        'max_size' => $allowd_file_size,
    );
}
