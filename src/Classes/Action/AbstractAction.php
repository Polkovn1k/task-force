<?php

namespace App\Classes\Action;

abstract class AbstractAction
{
    abstract public static function getActionName(): string;
    abstract public static function getActionCode(): string;
    abstract public static function checkVerification(int $userId, int $customerId, int $executorId): bool;
}
