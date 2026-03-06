<?php
    $sp_extra = $this->task->subtaskExtraModel->getBySubtaskId($subtask['id']);
    $sp_files = $this->task->subtaskFileModel->getAll($subtask['id']);
    $sp_file_count = count($sp_files);
    $sp_icons = '';
    if ($sp_file_count > 0) {
        $sp_icons .= '<i class="fa fa-paperclip" title="'.t('%d file(s)', $sp_file_count).'"></i> ';
    }
    if (!empty($sp_extra['description'])) {
        $sp_icons .= '<i class="fa fa-align-left" title="'.$this->text->e($sp_extra['description']).'"></i> ';
    }
    if (!empty($sp_extra['date_due'])) {
        $sp_icons .= '<span class="sp-board-due">'.$sp_extra['date_due'].'</span>';
    }
?>
<?php if ($sp_icons): ?>
<td class="sp-board-cell"><?= $sp_icons ?></td>
<?php else: ?>
<td class="sp-board-cell"></td>
<?php endif ?>
