<?php $sp_extra = $this->task->subtaskExtraModel->getBySubtaskId($subtask['id']); ?>

<div class="page-header">
    <h2>
        <i class="fa fa-upload"></i>
        <?= t('Upload file for subtask: %s', $this->text->e($subtask['title'])) ?>
    </h2>
</div>

<form method="post" enctype="multipart/form-data" action="<?= $this->url->href('SubtaskPlusController', 'save', array(
    'plugin' => 'SubtaskPlus',
    'task_id' => $task['id'],
    'subtask_id' => $subtask['id'],
    'project_id' => $task['project_id'],
)) ?>">
    <?= $this->form->csrf() ?>

    <div class="sp-form-section">
        <label><strong><?= t('File') ?></strong></label>
        <input type="file" name="files[]" multiple>
    </div>

    <div class="sp-form-section">
        <label><strong><?= t('Description') ?></strong></label>
        <?= $this->form->textarea('description', $sp_extra, array(), array(
            'placeholder' => t('Material, details, notes...'),
            'rows' => 5,
        )) ?>
    </div>

    <div class="sp-form-section">
        <label><strong><?= t('Due date') ?></strong></label>
        <?= $this->form->text('date_due', $sp_extra, array(), array('placeholder' => 'YYYY-MM-DD')) ?>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-blue">
            <i class="fa fa-check"></i> <?= t('Save') ?>
        </button>
        <a href="<?= $this->url->href('TaskViewController', 'show', array(
            'task_id' => $task['id'],
            'project_id' => $task['project_id'],
        )) ?>" class="btn">
            <?= t('Cancel') ?>
        </a>
    </div>
</form>
