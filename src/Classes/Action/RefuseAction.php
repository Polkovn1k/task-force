<?php

namespace App\Classes\Action;

class RefuseAction extends AbstractAction
{
    const ACTION_NAME = 'Отказаться';
    const ACTION_CODE = 'action_refuse';

    public static function getActionName(): string
    {
        return self::ACTION_NAME;
    }

    public static function getActionCode(): string
    {
        return self::ACTION_CODE;
    }

    public static function checkVerification(int $userId, int $customerId, int $executorId): bool
    {
        return $userId === $executorId;
    }
}
