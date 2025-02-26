<?php

use App\Models\Admin;
use App\Enums\ResponseMethodEnum;
use Illuminate\Http\UploadedFile;
use Propaganistas\LaravelPhone\PhoneNumber;

use Illuminate\Support\Facades\{
    DB,
    Storage
};

if (!function_exists('generalApiResponse')) {
    function generalApiResponse(
        ResponseMethodEnum $method,
        $resource = null,
        $dataPassed = null,
        $customMessage = null,
        $customStatusMsg = 'success',
        $customStatus = 200,
        $additionalData = null
    ) {
        return match ($method) {
            ResponseMethodEnum::CUSTOM_SINGLE => !is_null($additionalData) ? $resource::make($dataPassed)->additional(['status' => $customStatusMsg, 'message' => $customMessage, 'additional_data' => $additionalData], $customStatus) : $resource::make($dataPassed)->additional(['status' => $customStatusMsg, 'message' => $customMessage], $customStatus),

            ResponseMethodEnum::CUSTOM_COLLECTION => !is_null($additionalData) ? $resource::collection($dataPassed)->additional(['status' => $customStatusMsg, 'message' => $customMessage, 'additional_data' => $additionalData], $customStatus) : $resource::collection($dataPassed)->additional(['status' => $customStatusMsg, 'message' => $customMessage], $customStatus),

            ResponseMethodEnum::CUSTOM => !is_null($additionalData) ? response()->json(['status' => $customStatusMsg, 'data' => $dataPassed, 'message' => $customMessage, 'additional_data' => $additionalData], $customStatus) : response()->json(['status' => $customStatusMsg, 'data' => $dataPassed, 'message' => $customMessage], $customStatus),

            default => throw new InvalidArgumentException('Invalid response method'),
        };
    }
}



if (!function_exists('isAdminURL')) {
    function isAdminURL() {
        return !strpos(request()->url(), 'api');
    }
}

if (!function_exists('isProduction')) {
    function isProduction() {
        return env('APP_ENV') == 'production';
    }
}

if (!function_exists('getUserName')) {
    function getUserName($username) {
        switch ($username) {
            case filter_var($username, FILTER_VALIDATE_EMAIL) || preg_match('/[a-zA-Z]/', $username):
                $username = 'email';
                break;
            default:
                $username = 'phone_number';
                break;
        }
        return $username;
    }
}


if (!function_exists('validatePhone')) {
    function validatePhone() {
        return 'phone:NATIONAL,mobile,SA';
    }
}






if(!function_exists('haversineGreatCircleDistance')) {
    function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000) {
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos($latFrom) * cos($latTo) *
             sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        return $distance;
    }
}

if(!function_exists('setting')) {
    function setting($key, $default = null) {
        if (env('SKIP_COMPOSER')) {
            return 'boot';
        }
        $value = DB::table('settings')->where('key', $key)->first()?->value;
        if($key == 'app_name' || $key == 'about_desc' || $key == 'client_terms' || $key == 'client_policy' || $key == 'provider_terms' || $key == 'provider_policy') {
            return $value !== null && is_array(json_decode($value, true)) ? json_decode($value, true)[app()->getLocale()] : $value;
        }

        if($key == 'app_profit' || $key == 'vat' || $key == 'provider_profit') {
            return (float)$key / 100;
        }
        return $value ?? $default;
    }
}
