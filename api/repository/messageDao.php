<?php
require_once("/home/esllwnac/api/repository/baseDao.php");
require_once("/home/esllwnac/api/models/message.php");

class MessageDao extends BaseDao
{
  /**
   * Get a list of messages that are suitable to show to the public.
   */
  function GetAllMessagesForListeners()
  {
    $messages = array();
    $result = $this->Query("SELECT Id,Title,Service,Reference,Date,Speaker,File,Archived FROM Messages WHERE File IS NOT NULL AND File != '' ORDER BY Date DESC, Service DESC");
    while($row=mysql_fetch_array($result))
    {
      $message = new Message();
      $message->id = $row["Id"];
      $message->title = $row["Title"];
      $message->reference = $row["Reference"];
      $message->service = $row["Service"];
      $message->date = $row["Date"];
      $message->speaker = $row["Speaker"];
      $message->file = $row["File"];
      $message->file = $row["Archived"];
    	$messages[] = $message;
    }
    @mysql_free_result($result);
    return $messages;
  }

  function GetAllMessages()
  {
    $messages = array();
    $result = $this->Query("SELECT Id,Title,Service,Reference,Date,Speaker,File,Archived FROM Messages ORDER BY Date DESC");
    while($row=mysql_fetch_array($result))
    {
      $message = new Message();
      $message->id = $row["Id"];
      $message->title = $row["Title"];
      $message->reference = $row["Reference"];
      $message->service = $row["Service"];
      $message->date = $row["Date"];
      $message->speaker = $row["Speaker"];
      $message->file = $row["File"];
      $message->file = $row["Archived"];
    	$messages[] = $message;
    }
    @mysql_free_result($result);
    return $messages;
  }

  function GetMessageById($messageId)
  {
    $dbh = $this->GetConnection();
    $stmt = $dbh->prepare("SELECT Title, Reference, Service, Description, Date, Speaker, File, Archived, LastMaintOpid, LastMaintDateTime FROM Messages WHERE Id = :id");
    $stmt->bindParam(':id', $messageId);
    $stmt->execute();

    $row = $stmt->fetch();

    $message = new Message();
    $message->id = $row["Id"];
    $message->title = $row["Title"];
    $message->reference = $row["Reference"];
    $message->service = $row["Service"];
    $message->description = $row["Description"];
    $message->date = $row["Date"];
    $message->speaker = $row["Speaker"];
    $message->file = $row["File"];
    $message->file = $row["Archived"];
    $message->lastMaintOpId = $row["LastMaintOpid"];
    $message->lastMaintDateTime = $row["LastMaintDateTime"];

    return $message;
  }

  function AddMessage($message)
  {
    $dbh = $this->GetConnection();
    $stmt = $dbh->prepare("INSERT INTO Messages (Title, Reference, Date, Service, Speaker, File, Archived, Description) VALUES (:title, :reference, :date, :service, :speaker, :file, :archived, :description)");
    $stmt->bindParam(':title', $message->title);
    $stmt->bindParam(':reference', $message->reference);
    $stmt->bindParam(':date', date("Y-m-d",strtotime($message->date)));
    $stmt->bindParam(':service', $message->service);
    $stmt->bindParam(':speaker', $message->speaker);
    $stmt->bindParam(':file', $message->file);
    $stmt->bindParam(':archived', $message->archived);
    $stmt->bindParam(':description', $message->description);
    $stmt->execute();
  }

  function UpdateMessage($message)
  {
    $dbh = $this->GetConnection();
    $stmt = $dbh->prepare("UPDATE Messages SET Title=:title, Reference=:reference, Date=:date, Service=:service, Speaker=:speaker, File=:file, Archived=:archived, Description=:description, LastMaintDateTime=:currentTimestamp WHERE Id=:id");
    $stmt->bindParam(':id', $message->id);
    $stmt->bindParam(':title', $message->title);
    $stmt->bindParam(':reference', $message->reference);
    $stmt->bindParam(':date', date("Y-m-d",strtotime($message->date)));
    $stmt->bindParam(':service', $message->service);
    $stmt->bindParam(':speaker', $message->speaker);
    $stmt->bindParam(':file', $message->file);
    $stmt->bindParam(':archived', $message->archived);
    $stmt->bindParam(':description', $message->description);
    $stmt->bindParam(':currentTimestamp', date("YmdHms")); // current date in mySQL TIMESTAMP format
    $stmt->execute();
  }

  function DeleteMessage($messageId)
  {
    $dbh = $this->GetConnection();
    $stmt = $dbh->prepare("DELETE FROM Messages WHERE Id=:id");
    $stmt->bindParam(':id', $messageId);
    $stmt->execute();
  }
}
