<?php

Yii::import('system.cli.commands.MigrateCommand');
/*
 * Schema cache flushing fix
 */ 
class ChMigrateCommand extends MigrateCommand
{
    public function run($args)
    {
        parent::run($args);
 
        $this->flushCache();
    }
 
    public function flushCache()
    {
        $db = $this->getDbConnection();
 
        if ($db->schemaCachingDuration > 0) {
            $cacher = Yii::app()->getComponent($db->schemaCacheID);
            if ($cacher)
                $cacher->flush();
        }
    }
}