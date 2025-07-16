<?php

namespace App\Classes;

class TaskAction {
    const STATUS_NEW = 'new';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_CANCELED = 'canceled';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';

    const ACTION_CANCEL = 'action_cancel';
    const ACTION_COMPLETE = 'action_complete';
    const ACTION_RESPONSE = 'action_respond';
    const ACTION_REFUSE = 'action_refuse';

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
     * Получаем карту всех действий по задаче
     * @return string[]
     */
    public function getActionsMap(): array
    {
        return [
            self::ACTION_CANCEL => 'Отменить',
            self::ACTION_RESPONSE => 'Откликнуться',
            self::ACTION_COMPLETE => 'Выполнено',
            self::ACTION_REFUSE => 'Отказаться',
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
     * Получаем нужный статус исходя из переданного статуса
     * @param string $action
     * @return string|null
     */
    public function getAvailableStatusesByAction(string $action): ?string
    {
        $map = [
            self::ACTION_COMPLETE => self::STATUS_COMPLETED,
            self::ACTION_CANCEL => self::STATUS_CANCELED,
            self::ACTION_REFUSE => self::STATUS_CANCELED,
            self::ACTION_RESPONSE => null,
        ];

        return $map[$action] ?? null;
    }

    /**
     * Получаем массив допустимых действий исходя из переданного статуса
     * @param string $status
     * @return array
     */
    public function getAvailableActionsByStatus(string $status): array
    {
        $map = [
            self::STATUS_NEW => [self::ACTION_RESPONSE, self::ACTION_CANCEL],
            self::STATUS_IN_PROGRESS => [self::ACTION_COMPLETE, self::ACTION_REFUSE],
            self::STATUS_CANCELED => [],
            self::STATUS_COMPLETED => [],
            self::STATUS_FAILED => [],
        ];

        return $map[$status] ?? [];
    }
}
