<?php
    use App\Classes\TaskAction;

    require_once('vendor/autoload.php');

    $task_action = new TaskAction(TaskAction::STATUS_NEW, 1, 1);

    echo $task_action->getCurrentStatus();
