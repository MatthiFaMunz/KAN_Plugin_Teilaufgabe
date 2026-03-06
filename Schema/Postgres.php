<?php

namespace Kanboard\Plugin\SubtaskPlus\Schema;

const VERSION = 1;

function version_1($pdo)
{
    $pdo->exec('CREATE TABLE IF NOT EXISTS subtask_has_files (
        id SERIAL PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        path VARCHAR(255) NOT NULL,
        is_image BOOLEAN DEFAULT FALSE,
        size INT DEFAULT 0,
        subtask_id INT NOT NULL,
        user_id INT DEFAULT 0,
        date INT DEFAULT 0,
        FOREIGN KEY(subtask_id) REFERENCES subtasks(id) ON DELETE CASCADE
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS subtask_has_extra (
        subtask_id INT NOT NULL,
        description TEXT DEFAULT '',
        date_due VARCHAR(10) DEFAULT '',
        PRIMARY KEY(subtask_id),
        FOREIGN KEY(subtask_id) REFERENCES subtasks(id) ON DELETE CASCADE
    )');
}
