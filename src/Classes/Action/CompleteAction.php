<?php

namespace App\Classes\Action;

class CompleteAction extends AbstractAction
{
    const ACTION_NAME = 'Завершить';
    const ACTION_CODE = 'action_complete';

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
        return $userId === $customerId;
    }
}
