<?php

namespace Kanboard\Plugin\SubtaskPlus\Api;

use Kanboard\Core\Base;
use Kanboard\Core\ObjectStorage\ObjectStorageException;

class SubtaskPlusProcedure extends Base
{
    // ── File operations ─────────────────────────────────────────────────────

    public function createSubtaskFile($project_id, $subtask_id, $filename, $blob)
    {
        return $this->subtaskFileModel->uploadContent($subtask_id, $filename, $blob);
    }

    public function getAllSubtaskFiles($subtask_id)
    {
        return $this->subtaskFileModel->getAll($subtask_id);
    }

    public function getSubtaskFile($file_id)
    {
        return $this->subtaskFileModel->getById($file_id);
    }

    public function downloadSubtaskFile($file_id)
    {
        $file = $this->subtaskFileModel->getById($file_id);
        if (empty($file)) {
            return '';
        }

        try {
            return base64_encode($this->objectStorage->get($file['path']));
        } catch (ObjectStorageException $e) {
            $this->logger->error($e->getMessage());
            return '';
        }
    }

    public function removeSubtaskFile($file_id)
    {
        return $this->subtaskFileModel->remove($file_id);
    }

    public function removeAllSubtaskFiles($subtask_id)
    {
        return $this->subtaskFileModel->removeAll($subtask_id);
    }

    // ── Extra data (description + date_due) ─────────────────────────────────

    public function setSubtaskExtra($subtask_id, $description = '', $date_due = '')
    {
        return $this->subtaskExtraModel->save($subtask_id, $description, $date_due);
    }

    public function getSubtaskExtra($subtask_id)
    {
        return $this->subtaskExtraModel->getBySubtaskId($subtask_id);
    }
}
