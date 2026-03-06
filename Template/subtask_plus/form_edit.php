<?php
    $sp_subtask_id = isset($values['id']) ? $values['id'] : 0;
    $sp_extra = $sp_subtask_id ? $this->task->subtaskExtraModel->getBySubtaskId($sp_subtask_id) : array();
    $sp_files = $sp_subtask_id ? $this->task->subtaskFileModel->getAll($sp_subtask_id) : array();
    $sp_task_id = isset($values['task_id']) ? $values['task_id'] : 0;
    $sp_project_id = 0;
    if ($sp_task_id) {
        $sp_task = $this->task->taskFinderModel->getById($sp_task_id);
        $sp_project_id = $sp_task ? $sp_task['project_id'] : 0;
    }
?>
<div class="sp-extra-fields">
    <?php if (!empty($sp_extra['date_due'])): ?>
        <label><?= t('Due date') ?></label>
        <span class="sp-due"><?= $this->text->e($sp_extra['date_due']) ?></span>
    <?php endif ?>

    <?php if (!empty($sp_extra['description'])): ?>
        <label><?= t('Description') ?></label>
        <div class="sp-description-box"><?= $this->text->markdown($sp_extra['description']) ?></div>
    <?php endif ?>

    <?php if (count($sp_files) > 0): ?>
        <label><?= t('Attached files') ?></label>
        <ul class="sp-file-list">
            <?php foreach ($sp_files as $file): ?>
                <li>
                    <a href="<?= $this->url->href('SubtaskPlusController', 'download', array(
                        'plugin' => 'SubtaskPlus',
                        'file_id' => $file['id'],
                    )) ?>">
                        <i class="fa fa-<?= $file['is_image'] ? 'image' : 'file' ?>"></i>
                        <?= $this->text->e($file['name']) ?>
                    </a>
                    <span class="sp-file-size">(<?= $this->text->bytes($file['size']) ?>)</span>
                </li>
            <?php endforeach ?>
        </ul>
    <?php endif ?>

    <?php if ($sp_subtask_id && $sp_task_id && $sp_project_id): ?>
        <div class="sp-edit-actions">
            <a href="<?= $this->url->href('SubtaskPlusController', 'upload', array(
                'plugin' => 'SubtaskPlus',
                'task_id' => $sp_task_id,
                'subtask_id' => $sp_subtask_id,
                'project_id' => $sp_project_id,
            )) ?>" class="btn btn-small">
                <i class="fa fa-upload"></i> <?= t('Add file / drawing') ?>
            </a>
        </div>
    <?php endif ?>
</div>
