<?php

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'FileARBehavior.php';


class PhpThumbARBehavior extends FileARBehavior
{


    public function __construct()
    {
        
        $this->processor = 'ext.phpthumb.phpThumb';
    }


    protected function createProcessor($klass, $srcPath)
    {
        
        $klass  = $klass::create($srcPath);
        $klass->setOptions(array('jpegQuality'=>100));

        return $klass;
    }
}