<?php
require_once("/home/esllwnac/api/repository/baseDao.php");
require_once("/home/esllwnac/api/models/file.php");

class FileDao extends BaseDao
{
  /**
   * Gets a list of unused files.
   */
  function GetUnusedFiles()
  {
    $usedFiles = $this->GetUsedFiles();
    $allFiles = $this->GetAllFiles();
    $unusedFiles = array();

    $usedFileNames = array();
    foreach ($usedFiles as &$usedFile) {
      $usedFileNames[] = $usedFile->name;
    }

    foreach ($allFiles as &$file) {
      if(!in_array($file->name, $usedFileNames))
      {
        $unusedFiles[] = $file;
      }
    }
    return $unusedFiles;
  }

  /**
   * Gets a list of files that are assigned to a Message.
   */
  function GetUsedFiles()
  {
    $files = array();
    $result=$this->Query("SELECT File FROM Messages ORDER BY File DESC");
    while($row=mysql_fetch_array($result))
    {
      $filename = $row["File"];
    	$files[] = new File($filename);
    }
    return $files;
  }

  /**
   * Get all files from the file system.
   */
  function GetAllFiles()
  {
    $files = array();
    $dir = "/home/esllwnac/public_html/messages/";
    if(is_dir($dir))
    {
    	if($dh = opendir($dir))
    	{
    		while(($filename = readdir($dh)) != false)
    		{
    			if(filetype($dir.$filename)=="file")
    			{
    				$type=strtoupper(strrchr($filename,"."));
    				if($type==".MP3" || $type==".WMA")
    				{
              $filesize = filesize($dir.$filename);
              $files[] = new File($filename, $filesize);
    				}
    			}
    		}
    		closedir($dh);
    	}
    }
    return $files;
  }
}
?>
