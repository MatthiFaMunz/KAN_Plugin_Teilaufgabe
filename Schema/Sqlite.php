<?php

namespace Kanboard\Plugin\SubtaskPlus\Schema;

const VERSION = 1;

function version_1($pdo)
{
    $pdo->exec('CREATE TABLE IF NOT EXISTS subtask_has_files (
        "id" INTEGER PRIMARY KEY,
        "name" VARCHAR(255) NOT NULL,
        "path" VARCHAR(255) NOT NULL,
        "is_image" INTEGER DEFAULT 0,
        "size" INTEGER DEFAULT 0,
        "subtask_id" INTEGER NOT NULL,
        "user_id" INTEGER DEFAULT 0,
        "date" INTEGER DEFAULT 0,
        FOREIGN KEY(subtask_id) REFERENCES subtasks(id) ON DELETE CASCADE
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS subtask_has_extra (
        "subtask_id" INTEGER PRIMARY KEY,
        "description" TEXT DEFAULT "",
        "date_due" VARCHAR(10) DEFAULT "",
        FOREIGN KEY(subtask_id) REFERENCES subtasks(id) ON DELETE CASCADE
    )');
}
