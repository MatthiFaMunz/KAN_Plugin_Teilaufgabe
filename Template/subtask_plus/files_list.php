<div class="page-header">
    <h2>
        <i class="fa fa-paperclip"></i>
        <?= t('Files for subtask: %s', $this->text->e($subtask['title'])) ?>
    </h2>
</div>

<?php if (!empty($extra['description'])): ?>
<div class="sp-description-box">
    <h3><i class="fa fa-align-left"></i> <?= t('Description') ?></h3>
    <div class="markdown">
        <?= $this->text->markdown($extra['description']) ?>
    </div>
</div>
<?php endif ?>

<?php if (!empty($extra['date_due'])): ?>
<div class="sp-due-box">
    <strong><i class="fa fa-calendar"></i> <?= t('Due date') ?>:</strong>
    <?= $extra['date_due'] ?>
</div>
<?php endif ?>

<?php if (empty($files)): ?>
    <p class="alert"><?= t('No files attached.') ?></p>
<?php else: ?>
    <table class="table-striped table-scrolling">
        <tr>
            <th><?= t('Filename') ?></th>
            <th><?= t('Size') ?></th>
            <th><?= t('Actions') ?></th>
        </tr>
        <?php foreach ($files as $file): ?>
        <tr>
            <td>
                <i class="fa fa-<?= $file['is_image'] ? 'image' : 'file-pdf-o' ?>"></i>
                <?= $this->text->e($file['name']) ?>
            </td>
            <td><?= $this->text->bytes($file['size']) ?></td>
            <td>
                <a href="<?= $this->url->href('SubtaskPlusController', 'download', array(
                    'plugin' => 'SubtaskPlus',
                    'file_id' => $file['id'],
                )) ?>" class="btn btn-small">
                    <i class="fa fa-download"></i> <?= t('Download') ?>
                </a>
                <a href="<?= $this->url->href('SubtaskPlusController', 'removeFile', array(
                    'plugin' => 'SubtaskPlus',
                    'file_id' => $file['id'],
                    'csrf_token' => $this->app->getToken()->getReusableCSRFToken(),
                )) ?>" class="btn btn-small btn-red" onclick="return confirm('<?= t('Are you sure?') ?>')">
                    <i class="fa fa-trash"></i> <?= t('Remove') ?>
                </a>
            </td>
        </tr>
        <?php endforeach ?>
    </table>
<?php endif ?>

<div class="sp-upload-section">
    <h3><i class="fa fa-upload"></i> <?= t('Upload new file') ?></h3>
    <form method="post" enctype="multipart/form-data" action="<?= $this->url->href('SubtaskPlusController', 'save', array(
        'plugin' => 'SubtaskPlus',
        'task_id' => $task['id'],
        'subtask_id' => $subtask['id'],
        'project_id' => $task['project_id'],
    )) ?>">
        <?= $this->form->csrf() ?>
        <input type="file" name="files[]" multiple>
        <br>
        <button type="submit" class="btn btn-blue">
            <i class="fa fa-upload"></i> <?= t('Upload') ?>
        </button>
    </form>
</div>

<div class="sp-back">
    <a href="<?= $this->url->href('TaskViewController', 'show', array(
        'task_id' => $task['id'],
        'project_id' => $task['project_id'],
    )) ?>" class="btn">
        <i class="fa fa-arrow-left"></i> <?= t('Back to task') ?>
    </a>
</div>
