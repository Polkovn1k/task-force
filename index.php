<?php
    use App\Classes\Task\TaskAction;

    require_once('vendor/autoload.php');

    $task_action = new TaskAction(TaskAction::STATUS_NEW, 1, 2);

    //var_dump($task_action->getAvailableAction('executor', 1));
$test = ['111', '', ''];
