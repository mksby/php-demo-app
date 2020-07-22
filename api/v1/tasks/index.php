<?php

namespace App\Api\V1;

use App\Db\Connection;
use App\Repositories;
use Exception;

include_once '../../../index.php';

header('Content-type: application/json');

$tasks = new Tasks();
$tasks->{[
    'POST' => 'create',
    'GET' => 'read',
    'PUT' => 'update',
    'DELETE' => 'delete'
][$_SERVER["REQUEST_METHOD"]]}();

class Tasks {
    private $db;
    private $repTasks;
    private $repTags;
    private $repTasksTags;

    function __construct() {
        $this->db = Connection::getInstance()->getConnection();

        $this->repTasks = new Repositories\Tasks($this->db);
        $this->repTags = new Repositories\Tags($this->db);
        $this->repTasksTags = new Repositories\TasksTags($this->db);

        $this->repTasks->fill();
        $this->repTags->fill();
        $this->repTasksTags->fill();
    }

    function create() {
        $this->db->beginTransaction();

        try {
            $task = $this->repTasks->create([
                'name' => htmlspecialchars($_POST['name']),
                'description' => htmlspecialchars($_POST['description']),
                'date' => date(\DateTime::ISO8601)
            ]);

            if (!empty($_POST['tags'])) {
                $this->repTasksTags->createAll([
                    'taskId' => $task['id']
                ], array_map(function($tag) {
                    return htmlspecialchars($tag);
                }, $_POST['tags']));
            }

            $this->db->commit();
        } catch (Exception $error) {
            echo $error->getMessage();

            $this->db->rollback();
        };

        return $this->read($task['id']);
    }

    function read($id) {
        $id = $id or htmlspecialchars($_GET['id']);

        if ($id) {
            $tasksTags = $this->repTasksTags->readAll([
                'taskId' => $id
            ]);

            $tagsIds = array_map(function($item) {
                return $item['tagId'];
            }, $tasksTags);

            echo json_encode($this->repTasks->read([
                'id' => $id
            ], [
                'tags' => $this->repTags->readAll([
                    'ids' => $tagsIds
                ])
            ]));
        } else {
            echo json_encode($this->repTasks->readAll([]));
        }
    }

    function update() {
        echo 'update data';
    }

    function delete() {
        echo 'delete data';
    }
}