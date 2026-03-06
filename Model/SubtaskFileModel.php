<?php

namespace Kanboard\Plugin\SubtaskPlus\Model;

use Kanboard\Model\FileModel;

class SubtaskFileModel extends FileModel
{
    const TABLE = 'subtask_has_files';
    const EVENT_CREATE = 'subtask.file.create';
    const EVENT_DESTROY = 'subtask.file.destroy';

    protected function getTable()
    {
        return self::TABLE;
    }

    protected function getForeignKey()
    {
        return 'subtask_id';
    }

    protected function getPathPrefix()
    {
        return 'subtasks';
    }

    protected function fireCreationEvent($file_id)
    {
        // No async event job needed for subtask files
    }

    protected function fireDestructionEvent($file_id)
    {
        // No async event job needed for subtask files
    }

    public function getProjectId($file_id)
    {
        return $this->db
            ->table(self::TABLE)
            ->columns('tasks.project_id')
            ->eq(self::TABLE.'.id', $file_id)
            ->join('subtasks', 'id', 'subtask_id', self::TABLE)
            ->join('tasks', 'id', 'task_id', 'subtasks')
            ->findOneColumn('tasks.project_id') ?: 0;
    }

    /**
     * Get task_id for a subtask (needed for redirects)
     */
    public function getTaskId($subtask_id)
    {
        return $this->db
            ->table('subtasks')
            ->eq('id', $subtask_id)
            ->findOneColumn('task_id') ?: 0;
    }
}
