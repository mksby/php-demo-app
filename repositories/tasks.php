<?php

namespace App\Repositories;

class Tasks {
    private $connection;

    function __construct($connection) {
        $this->connection = $connection;
    }

    function getAll() {
        return $this->connection->query('SELECT * FROM tasks')->fetchAll();
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
                ('name1', 'desc1', '2020-07-21T05:55:51+0000'),
                ('name2', 'desc2', '2020-07-22T05:55:51+0000'),
                ('name3', 'desc3', '2020-07-23T05:55:51+0000')
            "
        );
    }
}