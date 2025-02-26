<?php

namespace App\Enums;

enum ResponseStatus: string
{
    case SUCCESS = 'success';
    case NOT_FOUND = 'not_found';
    case OTP_EXPIRED    = 'otp_expired';
    case OTP_AUTH_VERFICATION    = 'otp_auth_verification';
    case OTP_ALREADY_VERIFIED    = 'otp_already_verified';
    case OTP_SENT    = 'otp_sent';
    case SERVER_ERROR    = 'server_error';
    case INCORRECT_OTP    = 'incorrect_otp';
    case ACCOUNT_DELETED    = 'account_deleted';
    case BANNED          = 'banned';
    case SUCCESS_LOGIN    = 'success_login';
    case FORGET_PASSWORD_ACTION_FAULT = 'forget_password_action_fault';
    case SUCCESS_PASSWORD_RESET = 'success_password_reset';
    case TRY_AGAIN = 'try_again';
    case FAIL = 'fail';
}
