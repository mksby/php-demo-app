<?php

namespace App\Api\V1;

use App\Db\Connection;
use App\Repositories;

include_once '../../../index.php';

$tags = new Tags();
$tags->{[
    'POST' => 'create',
    'GET' => 'read',
    'PUT' => 'update',
    'DELETE' => 'delete'
][$_SERVER["REQUEST_METHOD"]]}();

class Tags {
    public $repTasks;
    public $repTags;
    public $repTasksTags;

    function __construct() {
        $db = Connection::getInstance()->getConnection();

        $this->repTasks = new Repositories\Tasks($db);
        $this->repTags = new Repositories\Tags($db);
        $this->repTasksTags = new Repositories\TasksTags($db);

        $this->repTasks->fill();
        $this->repTags->fill();
        $this->repTasksTags->fill();
    }

    function create() {
        echo 'create data';
    }

    function read() {
        $id = htmlspecialchars($_GET['id']);

        if ($id) {
            $tasksTags = $this->repTasksTags->readAll([
                'tagId' => $id
            ]);

            $tasksIds = array_map(function($item) {
                return $item['taskId'];
            }, $tasksTags);

            echo json_encode($this->repTags->read([
                'id' => $id
            ], [
                'tasks' => $this->repTasks->readAll([
                    'ids' => $tasksIds
                ])
            ]));
        } else {
            echo json_encode($this->repTags->readAll([]));
        }
    }

    function update() {
        echo 'update data';
    }

    function delete() {
        echo 'delete data';
    }
}