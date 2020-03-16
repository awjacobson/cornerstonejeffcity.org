<?php
class Message
{
  var $id;
  var $title;
  var $reference;
  var $service;
  var $description;
  var $date;
  var $speaker;
  var $file;
  var $length;
  var $duration;
  var $guid;
  var $archived;
  var $lastMaintOpId;
  var $lastMaintDateTime;

  function __construct() {
       $this->archived = false;
   }
}
?>
