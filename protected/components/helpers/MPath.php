<?php

class MPath
{
  public static function checkDir($path){
    if(!file_exists($path)){
        @mkdir($path, octdec(Yii::app()->params['storeImages']['dirMode']));
    }
    return true;
  }
    /**
       * Chmods files and directories recursively to given permissions.
       *
       * @param   string  $path        Root path to begin changing mode [without trailing slash].
       * @param   string  $filemode    Octal representation of the value to change file mode to [null = no change].
       * @param   string  $foldermode  Octal representation of the value to change folder mode to [null = no change].
       *
       * @return  boolean  True if successful [one fail means the whole operation failed].
       *
       */
  	public static function setPermissions($path, $filemode = '0644', $foldermode = '0755')
      {
          // Initialise return value
          $ret = true;
  
          if (is_dir($path))
          {
              $dh = opendir($path);
  
              while ($file = readdir($dh))
              {
                 if ($file != '.' && $file != '..')
                  {
                      $fullpath = $path . '/' . $file;
                      if (is_dir($fullpath))
                      {
                          if (!self::setPermissions($fullpath, $filemode, $foldermode))
                          {
                              $ret = false;
                          }
                      }
                      else
                      {
                          if (isset($filemode))
                          {
                              if (!@ chmod($fullpath, octdec($filemode)))
                              {
                                  $ret = false;
                              }
                          }
                      }
                  }
              }
              closedir($dh);
              if (isset($foldermode))
              {
                  if (!@ chmod($path, octdec($foldermode)))
                  {
                      $ret = false;
                  }
              }
          }
          else
          {
              if (isset($filemode))
              {
                  $ret = @ chmod($path, octdec($filemode));
              }
          }
  
          return $ret;
      }
}
