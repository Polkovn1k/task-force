<?php

namespace App\Classes\Action;

class RespondAction extends AbstractAction
{
    const ACTION_NAME = 'Откликнуться';
    const ACTION_CODE = 'action_respond';

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
