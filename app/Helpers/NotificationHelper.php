<?php

namespace App\Helpers;


class NotificationHelper
{
    public const NEW_MESSAGE = 'message';

    public static function notificationTypeList()
    {
        return [self::NEW_MESSAGE => 'New Answer'];
    }

    public static function getFullType($type)
    {
        return self::notificationTypeList()[$type];
    }
}