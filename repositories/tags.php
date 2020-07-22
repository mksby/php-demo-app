<?php

namespace App\Repositories;

header('Content-type: application/json');

class Tags {
    private $connection;

    function __construct($connection) {
        $this->connection = $connection;
    }

    function read($params, $deps) {
        $query = $this->connection->prepare(
            "SELECT * FROM tags WHERE id = :id"
        );

        $query->execute($params);

        $task = $query->fetch();

        return array_merge($task, $deps);
    }

    function readAll($params) {
        if (empty($params)) {
            return $this->connection->query('SELECT * FROM tags')->fetchAll();
        } else {
            if (count($params['ids'])) {
                $in  = str_repeat('?,', count($params['ids']) - 1) . '?';
                $query = $this->connection->prepare(
                    "SELECT * FROM tags WHERE id IN ($in)"
                );

                $query->execute($params['ids']);
                $tags = $query->fetchAll();

                return $tags;
            } else {
                return [];
            }
        }
    }

    function fill() {
        $this->connection->exec(
            "CREATE TABLE IF NOT EXISTS tags (
                id INTEGER PRIMARY KEY,
                name TEXT,
                date TEXT
            )"
        );

        $this->connection->exec(
            "INSERT INTO tags (name, date) VALUES
                ('tag1', '2020-07-21T05:55:51+0000'),
                ('tag2', '2020-07-22T05:55:51+0000'),
                ('tag3', '2020-07-23T05:55:51+0000')
            "
        );
    }
}