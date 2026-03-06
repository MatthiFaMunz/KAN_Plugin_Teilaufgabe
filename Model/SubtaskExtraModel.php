<?php

namespace Kanboard\Plugin\SubtaskPlus\Model;

use Kanboard\Core\Base;

class SubtaskExtraModel extends Base
{
    const TABLE = 'subtask_has_extra';

    /**
     * Get extra data for a subtask
     */
    public function getBySubtaskId($subtask_id)
    {
        $row = $this->db->table(self::TABLE)->eq('subtask_id', $subtask_id)->findOne();
        if (! $row) {
            return array(
                'subtask_id'  => $subtask_id,
                'description' => '',
                'date_due'    => '',
            );
        }
        return $row;
    }

    /**
     * Set/update extra data for a subtask (upsert)
     */
    public function save($subtask_id, $description = '', $date_due = '')
    {
        $existing = $this->db->table(self::TABLE)->eq('subtask_id', $subtask_id)->findOne();

        if ($existing) {
            return $this->db->table(self::TABLE)->eq('subtask_id', $subtask_id)->update(array(
                'description' => $description,
                'date_due'    => $date_due,
            ));
        }

        return $this->db->table(self::TABLE)->insert(array(
            'subtask_id'  => $subtask_id,
            'description' => $description,
            'date_due'    => $date_due,
        ));
    }

    /**
     * Remove extra data for a subtask
     */
    public function remove($subtask_id)
    {
        return $this->db->table(self::TABLE)->eq('subtask_id', $subtask_id)->remove();
    }

    /**
     * Get all extras indexed by subtask_id (for batch display)
     */
    public function getAllByTaskId($task_id)
    {
        $subtask_ids = $this->db->table('subtasks')
            ->eq('task_id', $task_id)
            ->findAllByColumn('id');

        if (empty($subtask_ids)) {
            return array();
        }

        $rows = $this->db->table(self::TABLE)
            ->in('subtask_id', $subtask_ids)
            ->findAll();

        $result = array();
        foreach ($rows as $row) {
            $result[$row['subtask_id']] = $row;
        }
        return $result;
    }
}
