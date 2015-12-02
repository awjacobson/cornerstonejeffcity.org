<?php
class File
{
  var $name;
  var $size;

  function File($filename = "", $filesize = 0)
  {
    $this->name = $filename;
    $this->size = $filesize;
  }

  function GetFormattedFileSize() {
    $megabytes = ceil($this->size / 1048576);
    return number_format($megabytes)." MB";
  }
}
?>
