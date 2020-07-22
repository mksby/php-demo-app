<?php

namespace App\Repositories;

class TasksTags {
    private $connection;

    function __construct($connection) {
        $this->connection = $connection;
    }

    function create($params) {
        $this->connection->prepare(
            "INSERT INTO tasks (taskId, tagId) VALUES
                (:taskId, :tagId)
            "
        )->execute($params);

        return $this->readAll([]);
    }

    function createAll($params, $tags) {
        $tagsValues = implode(',', array_map(function($item) {
            return "(:taskId, $item)";
        }, $tags));

        $this->connection->prepare(
            "INSERT INTO tasksTags (taskId, tagId) VALUES $tagsValues"
        )->execute($params);
    }

    function readAll($params) {
        if ($params['taskId']) {
            $query = $this->connection->prepare(
                "SELECT * FROM tasksTags WHERE taskId = :taskId"
            );
        } else if ($params['tagId']) {
            $query = $this->connection->prepare(
                "SELECT * FROM tasksTags WHERE tagId = :tagId"
            );
        }

        $query->execute($params);

        $tags = $query->fetchAll();

        return $tags;
    }

    function fill() {
        $this->connection->exec(
            "CREATE TABLE IF NOT EXISTS tasksTags (
                id INTEGER PRIMARY KEY,
                taskId INTEGER,
                tagId INTEGER
            )"
        );

        $this->connection->exec(
            "INSERT INTO tasksTags (taskId, tagId) VALUES
                (2, 1),
                (2, 2),
                (3, 2),
                (3, 3)
            "
        );
    }
}