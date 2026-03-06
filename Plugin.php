<?php

namespace Kanboard\Plugin\SubtaskPlus;

use Kanboard\Core\Plugin\Base;
use Kanboard\Core\Translator;

class Plugin extends Base
{
    public function initialize()
    {
        // --- CSS ---
        $this->hook->on('template:layout:css', array(
            'template' => 'plugins/SubtaskPlus/Asset/css/subtask-plus.css',
        ));

        // --- Subtask Table: extra columns ---
        $this->template->hook->attach(
            'template:subtask:table:header:before-timetracking',
            'SubtaskPlus:subtask_plus/table_header'
        );

        // Row hook: $task ist NICHT im Scope, nur $subtask → wir injizieren $task
        $container = $this->container;
        $this->template->hook->attachCallable(
            'template:subtask:table:rows',
            'SubtaskPlus:subtask_plus/table_row',
            function ($subtask) use ($container) {
                $task = $container['taskFinderModel']->getById($subtask['task_id']);
                return array('task' => $task);
            }
        );

        // --- Board: SubtasksOnBoard Kompatibilität (Extra-Info pro Subtask auf Karte) ---
        $this->template->hook->attachCallable(
            'template:board:tooltip:subtasks:rows',
            'SubtaskPlus:subtask_plus/board_row',
            function ($subtask) use ($container) {
                $task = $container['taskFinderModel']->getById($subtask['task_id']);
                return array('task' => $task);
            }
        );

        // --- Subtask Forms: extra fields ---
        $this->template->hook->attach(
            'template:subtask:form:create',
            'SubtaskPlus:subtask_plus/form_create'
        );
        $this->template->hook->attach(
            'template:subtask:form:edit',
            'SubtaskPlus:subtask_plus/form_edit'
        );

        // --- Routes ---
        $this->route->addRoute(
            'subtask-plus/upload/:task_id/:subtask_id',
            'SubtaskPlusController',
            'upload',
            'SubtaskPlus'
        );
        $this->route->addRoute(
            'subtask-plus/save/:task_id/:subtask_id',
            'SubtaskPlusController',
            'save',
            'SubtaskPlus'
        );
        $this->route->addRoute(
            'subtask-plus/download/:file_id',
            'SubtaskPlusController',
            'download',
            'SubtaskPlus'
        );
        $this->route->addRoute(
            'subtask-plus/remove-file/:file_id',
            'SubtaskPlusController',
            'removeFile',
            'SubtaskPlus'
        );
        $this->route->addRoute(
            'subtask-plus/files/:task_id/:subtask_id',
            'SubtaskPlusController',
            'files',
            'SubtaskPlus'
        );

        // --- API procedures ---
        $this->api->getProcedureHandler()->withClassAndMethod('createSubtaskFile',     new \Kanboard\Plugin\SubtaskPlus\Api\SubtaskPlusProcedure($this->container), 'createSubtaskFile');
        $this->api->getProcedureHandler()->withClassAndMethod('getAllSubtaskFiles',     new \Kanboard\Plugin\SubtaskPlus\Api\SubtaskPlusProcedure($this->container), 'getAllSubtaskFiles');
        $this->api->getProcedureHandler()->withClassAndMethod('getSubtaskFile',        new \Kanboard\Plugin\SubtaskPlus\Api\SubtaskPlusProcedure($this->container), 'getSubtaskFile');
        $this->api->getProcedureHandler()->withClassAndMethod('downloadSubtaskFile',   new \Kanboard\Plugin\SubtaskPlus\Api\SubtaskPlusProcedure($this->container), 'downloadSubtaskFile');
        $this->api->getProcedureHandler()->withClassAndMethod('removeSubtaskFile',     new \Kanboard\Plugin\SubtaskPlus\Api\SubtaskPlusProcedure($this->container), 'removeSubtaskFile');
        $this->api->getProcedureHandler()->withClassAndMethod('removeAllSubtaskFiles', new \Kanboard\Plugin\SubtaskPlus\Api\SubtaskPlusProcedure($this->container), 'removeAllSubtaskFiles');
        $this->api->getProcedureHandler()->withClassAndMethod('setSubtaskExtra',       new \Kanboard\Plugin\SubtaskPlus\Api\SubtaskPlusProcedure($this->container), 'setSubtaskExtra');
        $this->api->getProcedureHandler()->withClassAndMethod('getSubtaskExtra',       new \Kanboard\Plugin\SubtaskPlus\Api\SubtaskPlusProcedure($this->container), 'getSubtaskExtra');

        // --- Translations ---
        Translator::load($this->languageModel->getCurrentLanguage(), __DIR__.'/Locale');
    }

    public function onStartup()
    {
        // Reserved for future event listeners
    }

    public function getClasses()
    {
        return array(
            'Plugin\SubtaskPlus\Model' => array(
                'SubtaskFileModel',
                'SubtaskExtraModel',
            ),
        );
    }

    public function getPluginName()
    {
        return 'SubtaskPlus';
    }

    public function getPluginDescription()
    {
        return t('Adds file attachments, description and due date to subtasks.');
    }

    public function getPluginAuthor()
    {
        return 'Fa. Munz D/M/H';
    }

    public function getPluginVersion()
    {
        return '1.0.0';
    }

    public function getPluginHomepage()
    {
        return 'https://munz-dmh.de';
    }

    public function getCompatibleVersion()
    {
        return '>=1.2.20';
    }
}
