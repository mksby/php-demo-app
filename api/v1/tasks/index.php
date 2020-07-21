<?php

namespace App\Api\V1;

use App\Db\Connection;
use App\Repositories;

include_once '../../../index.php';

$tasks = new Tasks();
$tasks->{[
    'POST' => 'create',
    'GET' => 'read',
    'PUT' => 'update',
    'DELETE' => 'delete'
][$_SERVER["REQUEST_METHOD"]]}();

class Tasks {
    public $repTasks;

    function __construct() {
        $this->repTasks = new Repositories\Tasks(
            Connection::getInstance()->getConnection()
        );

        $this->repTasks->fill();
    }

    function create() {
        echo 'create data';
    }

    function read() {
        var_dump($this->repTasks->getAll());

        echo 'read data';
    }

    function update() {
        echo 'update data';
    }

    function delete() {
        echo 'delete data';
    }
}