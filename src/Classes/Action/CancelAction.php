<?php

namespace App\Classes\Action;

class CancelAction extends AbstractAction
{
    const ACTION_NAME = 'Отменить';
    const ACTION_CODE = 'action_cancel';
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
