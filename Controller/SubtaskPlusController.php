<?php

namespace Kanboard\Plugin\SubtaskPlus\Controller;

use Kanboard\Controller\BaseController;

class SubtaskPlusController extends BaseController
{
    /**
     * Show files for a subtask + upload form
     */
    public function files()
    {
        $task = $this->getTask();
        $subtask_id = $this->request->getIntegerParam('subtask_id');
        $subtask = $this->subtaskModel->getById($subtask_id);

        if (empty($subtask) || $subtask['task_id'] != $task['id']) {
            throw new \Kanboard\Core\Controller\AccessForbiddenException();
        }

        $files = $this->subtaskFileModel->getAll($subtask_id);
        $extra = $this->subtaskExtraModel->getBySubtaskId($subtask_id);

        $this->response->html($this->helper->layout->task('SubtaskPlus:subtask_plus/files_list', array(
            'task'     => $task,
            'subtask'  => $subtask,
            'files'    => $files,
            'extra'    => $extra,
            'project'  => $this->projectModel->getById($task['project_id']),
        )));
    }

    /**
     * Upload page
     */
    public function upload()
    {
        $task = $this->getTask();
        $subtask_id = $this->request->getIntegerParam('subtask_id');
        $subtask = $this->subtaskModel->getById($subtask_id);

        if (empty($subtask) || $subtask['task_id'] != $task['id']) {
            throw new \Kanboard\Core\Controller\AccessForbiddenException();
        }

        $this->response->html($this->helper->layout->task('SubtaskPlus:subtask_plus/upload', array(
            'task'    => $task,
            'subtask' => $subtask,
            'project' => $this->projectModel->getById($task['project_id']),
        )));
    }

    /**
     * Save uploaded files + extra data
     */
    public function save()
    {
        $task = $this->getTask();
        $subtask_id = $this->request->getIntegerParam('subtask_id');
        $subtask = $this->subtaskModel->getById($subtask_id);

        if (empty($subtask) || $subtask['task_id'] != $task['id']) {
            throw new \Kanboard\Core\Controller\AccessForbiddenException();
        }

        // Handle file upload
        $files = $this->request->getFileInfo('files');
        if (! empty($files['name'][0]) && $files['name'][0] !== '') {
            $this->subtaskFileModel->uploadFiles($subtask_id, $files);
        }

        // Handle extra data (description, date_due)
        $values = $this->request->getValues();
        $description = isset($values['description']) ? $values['description'] : '';
        $date_due    = isset($values['date_due']) ? $values['date_due'] : '';

        if ($description !== '' || $date_due !== '') {
            $this->subtaskExtraModel->save($subtask_id, $description, $date_due);
        }

        $this->flash->success(t('File(s) uploaded successfully.'));
        return $this->response->redirect(
            $this->helper->url->to('TaskViewController', 'show', array('task_id' => $task['id'], 'project_id' => $task['project_id'])),
            true
        );
    }

    /**
     * Download a file
     */
    public function download()
    {
        $file_id = $this->request->getIntegerParam('file_id');
        $file = $this->subtaskFileModel->getById($file_id);

        if (empty($file)) {
            throw new \Kanboard\Core\Controller\PageNotFoundException();
        }

        try {
            $this->response->withFileDownload($file['name']);
            $this->response->send();
            $this->objectStorage->output($file['path']);
        } catch (\Kanboard\Core\ObjectStorage\ObjectStorageException $e) {
            $this->logger->error($e->getMessage());
        }
    }

    /**
     * Remove a file
     */
    public function removeFile()
    {
        $this->checkCSRFParam();
        $file_id = $this->request->getIntegerParam('file_id');
        $file = $this->subtaskFileModel->getById($file_id);

        if (! empty($file)) {
            $this->subtaskFileModel->remove($file_id);
            $task_id = $this->subtaskFileModel->getTaskId($file['subtask_id']);
            $task = $this->taskFinderModel->getById($task_id);
            $this->flash->success(t('File removed successfully.'));
            return $this->response->redirect(
                $this->helper->url->to('TaskViewController', 'show', array('task_id' => $task_id, 'project_id' => $task['project_id'])),
                true
            );
        }

        throw new \Kanboard\Core\Controller\PageNotFoundException();
    }
}
