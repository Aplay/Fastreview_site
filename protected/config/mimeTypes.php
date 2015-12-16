<?php
// Microsoft see http://technet.microsoft.com/en-us/library/ee309278%28office.12%29.aspx
// https://ru.wikipedia.org/wiki/%D0%A1%D0%BF%D0%B8%D1%81%D0%BE%D0%BA_MIME-%D1%82%D0%B8%D0%BF%D0%BE%D0%B2
$mimeTypes = require Yii::getPathOfAlias('system.utils.mimeTypes').'.php';

return array_merge($mimeTypes, array(
    'docx'=>'application/vnd.openxmlformats-officedocument.wordprocessingml.document', //Microsoft Office Word 2007 document
    'xlsx'=>'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // Microsoft Office Excel 2007 workbook
    'pptx'=>'application/vnd.openxmlformats-officedocument.presentationml.presentation'//Microsoft Office PowerPoint 2007 presentation
    'doc'=>'application/msword', // or application/vnd.ms-word
    'xls'=>'application/vnd.ms-excel',
    'ppt'=>'application/vnd.ms-powerpoint',
    'wbmp' => 'image/vnd.wap.wbmp',
    'mp4' => 'video/mp4',
    'apk' => 'application/vnd.android.package-archive',
    'jar' => 'application/java-archive',
    'sis' => 'application/vnd.symbian.install',
    'sisx' => 'x-epoc/x-sisx-app',
    'jad' => 'text/vnd.sun.j2me.app-descriptor; charset=UTF-8',
    'thm' => 'application/vnd.eri.thm',
    'nth' => 'application/vnd.nok-s40theme',
    'sdt' => 'application/vnd.siemens-mp.theme',
    'mpn' => 'application/vnd.mophun.application',
    'mpc' => 'application/vnd.mophun.certificate',
));