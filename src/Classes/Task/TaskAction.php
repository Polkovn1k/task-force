<?php

namespace App\Classes\Task;

use App\Classes\Action\CancelAction;
use App\Classes\Action\RefuseAction;
use App\Classes\Action\CompleteAction;
use App\Classes\Action\RespondAction;

class TaskAction
{
    const STATUS_NEW = 'new';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_CANCELED = 'canceled';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';

    const ROLE_CUSTOMER = 'customer';
    const ROLE_EXECUTOR = 'executor';

    private int $customerId;
    private ?int $executorId;
    private string $currentStatus;

    /**
     * @param string $status
     * @param int $customerId
     * @param int|null $executorId
     */
    public function __construct(string $status, int $customerId, ?int $executorId = null)
    {
        $this->customerId = $customerId;
        $this->executorId = $executorId;
        $this->setStatus($status);
    }

    /**
     * Получаем карту всех допустимых статусов по задаче
     * @return string[]
     */
    public function getStatusesMap(): array
    {
        return [
            self::STATUS_NEW => 'Новое',
            self::STATUS_CANCELED => 'Отменено',
            self::STATUS_IN_PROGRESS => 'В работе',
            self::STATUS_COMPLETED => 'Выполнено',
            self::STATUS_FAILED => 'Провалено',
        ];
    }

    /**
     * Получаем текущий статус задачи
     * @return string
     */
    public function getCurrentStatus(): string
    {
        return $this->currentStatus;
    }

    /**
     * Устанавливаем статус задачи
     * @param string $status
     * @return void
     */
    public function setStatus(string $status): void
    {
        $allStatusesKeys = array_keys($this->getStatusesMap());

        if (in_array($status, $allStatusesKeys)) {
            $this->currentStatus = $status;
        }
    }

    /**
     * Получаем нужный статус исходя из переданного действия
     * @param string $action
     * @return string|null
     */
    public function getAvailableStatusesByAction(string $action): ?string
    {
        $map = [
            CompleteAction::class => self::STATUS_COMPLETED,
            CancelAction::class => self::STATUS_CANCELED,
            RefuseAction::class => self::STATUS_CANCELED,
            RespondAction::class => null,
        ];

        return $map[$action] ?? null;
    }

    /**
     * Получаем массив допустимых действий исходя из переданного статуса
     * @param string $status
     * @return array|string[]
     */
    private function getAvailableActionsByStatus(string $status): array
    {
        $map = [
            self::STATUS_NEW => [RespondAction::class, CancelAction::class],
            self::STATUS_CANCELED => [],
            self::STATUS_IN_PROGRESS => [CompleteAction::class, RefuseAction::class],
            self::STATUS_COMPLETED => [],
            self::STATUS_FAILED => [],
        ];

        return $map[$status] ?? [];
    }

    /**
     * Получаем массив допустимых действий исходя из переданной роли
     * @param string $role
     * @return array|string[]
     */
    private function getAvailableActionsByRole(string $role): array
    {
        $map = [
            self::ROLE_CUSTOMER => [CancelAction::class, CompleteAction::class],
            self::ROLE_EXECUTOR => [RespondAction::class, RefuseAction::class],
        ];

        return $map[$role] ?? [];
    }

    /**
     * Получаем готовый массив допустимых действий
     * @param string $role
     * @param int $userId
     * @return array
     */
    public function getAvailableActions(string $role, int $userId): array
    {
        $actionByStatus = $this->getAvailableActionsByStatus($this->getCurrentStatus());
        $actionByRole = $this->getAvailableActionsByRole($role);
        $availableActions = array_values(array_intersect($actionByStatus, $actionByRole));

        return array_filter($availableActions, function($action) use ($userId) {
            return $action::checkVerification($userId, $this->customerId,  $this->executorId);
        });
    }

    /**
     * ????
     * @return array
     */
    private function getStatusMap()
    {
        $map = [
            self::STATUS_NEW => [self::STATUS_FAILED, self::STATUS_CANCELED],
            self::STATUS_IN_PROGRESS => [self::STATUS_CANCELED, self::STATUS_COMPLETED],
            self::STATUS_CANCELED => [],
            self::STATUS_COMPLETED => [],
            self::STATUS_FAILED => [self::STATUS_CANCELED]
        ];

        return $map;
    }
}
