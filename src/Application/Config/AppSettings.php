<?php

namespace App\Application\Config;

/**
 *
 */
class AppSettings
{
    public const ACCOUNT_SUSPENSION_DURATION = 3600 * 24;
    public const ACCOUNT_SUSPENSION_DURATION_HOURS = 24;
    public const ACCOUNT_MAX_LOGIN_ATTEMPTS = 5;
    public const TOKEN_SHORT_DURATION = 360; // 6 minutes
    public const TOKEN_LONG_DURATION = 3600; // 60 minutes
    public const USERNAME_MIN_LENGTH = 3;
    public const USERNAME_MAX_LENGTH = 200;
    public const USERNAME_PATTERN = "/^[a-zA-Z0-9]{3,200}$/";
    public const USERNAME_PATTERN_HTML = "^[a-zA-Z0-9]{3,200}$";
    public const PASSWORD_MIN_LENGTH = 12;
    public const PASSWORD_PATTERN = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])[a-zA-Z0-9]{12,}$/";
    public const PASSWORD_PATTERN_HTML = "^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])[a-zA-Z0-9]{12,}$";
    public const PASSWORD_ALGO = PASSWORD_BCRYPT;
    public const PASSWORD_MAX_BYTES = 72;
    public const CONTACT_MESSAGE_MIN_LENGTH = 10;
    public const CONTACT_MESSAGE_MAX_LENGTH = 1000;
    public const CONTACT_MESSAGE_PATTERN = "/^[\p{L}\p{N}\s\p{P}\p{S}]{10,1000}$/u";
    public const SOFTWARE_NAME_MIN_LENGTH = 1;
    public const SOFTWARE_NAME_MAX_LENGTH = 100;
    public const SOFTWARE_SUMMARY_MIN_LENGTH = 10;
    public const SOFTWARE_SUMMARY_MAX_LENGTH = 2000;
    public const SOFTWARE_NAME_PATTERN = "/^[\p{L}\p{N}\s\p{P}\p{S}]{1,100}$/u";
    public const SOFTWARE_SUMMARY_PATTERN = "/^[\p{L}\p{N}\s\p{P}\p{S}]{10,2000}$/u";
    public const ANCHOR_URL_MIN_LENGTH = 10;
    public const ANCHOR_URL_MAX_LENGTH = 400;
    public const ANCHOR_CONTENT_MIN_LENGTH = 10;
    public const ANCHOR_CONTENT_MAX_LENGTH = 400;
    public const ANCHOR_URL_PATTERN = "/^http[s]?:[\p{L}\p{N}\s\p{P}\p{S}]{10,400}$/u";
    public const ANCHOR_URL_PATTERN_HTML = "^http[s]?:[\p{L}\p{N}\s\p{P}\p{S}]{10,400}$";
    public const ANCHOR_CONTENT_PATTERN = "/^[\p{L}\p{N}\s\p{P}\p{S}]{10,400}$/u";
}
