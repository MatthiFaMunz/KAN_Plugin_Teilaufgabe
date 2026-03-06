<?php
    $sp_extra = $this->task->subtaskExtraModel->getBySubtaskId($subtask['id']);
    $sp_files = $this->task->subtaskFileModel->getAll($subtask['id']);
    $sp_file_count = count($sp_files);
    $sp_has_due = !empty($sp_extra['date_due']);
    $sp_is_overdue = $sp_has_due && $sp_extra['date_due'] < date('Y-m-d');
?>
<td class="sp-col-files">
    <?php if ($sp_file_count > 0): ?>
        <a href="<?= $this->url->href('SubtaskPlusController', 'files', array(
            'plugin' => 'SubtaskPlus',
            'task_id' => $task['id'],
            'subtask_id' => $subtask['id'],
            'project_id' => $task['project_id'],
        )) ?>" title="<?= t('%d file(s)', $sp_file_count) ?>">
            <i class="fa fa-paperclip"></i> <?= $sp_file_count ?>
        </a>
    <?php else: ?>
        <a href="<?= $this->url->href('SubtaskPlusController', 'upload', array(
            'plugin' => 'SubtaskPlus',
            'task_id' => $task['id'],
            'subtask_id' => $subtask['id'],
            'project_id' => $task['project_id'],
        )) ?>" class="sp-upload-link" title="<?= t('Upload file') ?>">
            <i class="fa fa-upload"></i>
        </a>
    <?php endif ?>
</td>
<td class="sp-col-desc">
    <?php if (!empty($sp_extra['description'])): ?>
        <span title="<?= $this->text->e($sp_extra['description']) ?>">
            <i class="fa fa-align-left"></i>
        </span>
    <?php endif ?>
</td>
<td class="sp-col-due">
    <?php if ($sp_has_due): ?>
        <span class="<?= $sp_is_overdue ? 'sp-overdue' : 'sp-due' ?>">
            <?= $sp_extra['date_due'] ?>
        </span>
    <?php endif ?>
</td>
