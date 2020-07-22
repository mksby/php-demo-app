<?php

namespace App\Repositories;

class Tasks {
    private $connection;

    function __construct($connection) {
        $this->connection = $connection;
    }

    function create($params) {
        $this->connection->prepare(
            "INSERT INTO tasks (name, description, date) VALUES
                (:name, :description, :date)
            "
        )->execute($params);

        return array_merge([
            'id' => $this->connection->lastInsertId()
        ], $params);
    }

    function read($params, $deps) {
        $query = $this->connection->prepare(
            "SELECT * FROM tasks WHERE id = :id"
        );

        $query->execute($params);

        $task = $query->fetch();

        return array_merge($task, $deps);
    }

    function readAll($params) {
        if (empty($params)) {
            return $this->connection->query('SELECT * FROM tasks')->fetchAll();
        } else {
            if (count($params['ids'])) {
                $in  = str_repeat('?,', count($params['ids']) - 1) . '?';
                $query = $this->connection->prepare(
                    "SELECT * FROM tasks WHERE id IN ($in)"
                );

                $query->execute($params['ids']);
                $tasks = $query->fetchAll();

                return $tasks;
            } else {
                return [];
            }
        }
    }

    function fill() {
        $this->connection->exec(
            "CREATE TABLE IF NOT EXISTS tasks (
                id INTEGER PRIMARY KEY,
                name TEXT,
                description TEXT,
                date TEXT
            )"
        );

        $this->connection->exec(
            "INSERT INTO tasks (name, description, date) VALUES
                ('task1', 'desc1', '2020-07-21T05:55:51+0000'),
                ('task2', 'desc2', '2020-07-22T05:55:51+0000'),
                ('task3', 'desc3', '2020-07-23T05:55:51+0000')
            "
        );
    }
}