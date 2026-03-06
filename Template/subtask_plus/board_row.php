<?php
    $sp_extra = $this->task->subtaskExtraModel->getBySubtaskId($subtask['id']);
    $sp_files = $this->task->subtaskFileModel->getAll($subtask['id']);
    $sp_file_count = count($sp_files);
    $sp_upload_url = $this->url->href('SubtaskPlusController', 'upload', array(
        'plugin' => 'SubtaskPlus',
        'task_id' => $task['id'],
        'subtask_id' => $subtask['id'],
        'project_id' => $task['project_id'],
    ));
    $sp_icons = '';
    if ($sp_file_count > 0) {
        $sp_files_url = $this->url->href('SubtaskPlusController', 'files', array(
            'plugin' => 'SubtaskPlus',
            'task_id' => $task['id'],
            'subtask_id' => $subtask['id'],
            'project_id' => $task['project_id'],
        ));
        $sp_icons .= '<a href="'.$sp_files_url.'" title="'.t('%d file(s)', $sp_file_count).'"><i class="fa fa-paperclip"></i>'.$sp_file_count.'</a> ';
    }
    if (!empty($sp_extra['description'])) {
        $sp_icons .= '<i class="fa fa-align-left" title="'.$this->text->e($sp_extra['description']).'"></i> ';
    }
    if (!empty($sp_extra['date_due'])) {
        $sp_icons .= '<span class="sp-board-due">'.$sp_extra['date_due'].'</span> ';
    }
    $sp_icons .= '<a href="'.$sp_upload_url.'" class="sp-board-upload" title="'.t('Add file / drawing').'"><i class="fa fa-upload"></i></a>';
?>
<td class="sp-board-cell"><?= $sp_icons ?></td>
