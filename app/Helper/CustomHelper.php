<?php

use Carbon\Carbon;

if (!function_exists('signatureBuilder')) {
    function signatureBuilder($transactionId,$amount)
    {
        return hash_hmac('sha256', env("KODE_MERCHANT").$transactionId.$amount, env("PRIVATE_KEY"));
    }
}

if (!function_exists('timestampToDateTime')) {
    function timestampToDateTime($timestamp)
    {
        $carbonDate = Carbon::createFromTimestamp($timestamp);
        return $carbonDate->format('d/m/y H:i:s');
    }
}
