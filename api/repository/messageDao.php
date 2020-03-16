<?php
require_once($_SERVER['DOCUMENT_ROOT']."/../api/repository/baseDao.php");
require_once($_SERVER['DOCUMENT_ROOT']."/../api/models/message.php");

class MessageDao extends BaseDao
{
  function GetLatest($count = 1)
  {
    $messages = array();
    $pdo = $this->GetConnection();
    $sql = "SELECT Id, Title, Reference, Service, Description, Date, Speaker, File, Archived FROM Messages WHERE File IS NOT NULL AND File != '' ORDER BY Date DESC, Service DESC LIMIT ".$count;
    foreach($pdo->query($sql) as $row)
    {
      $message = new Message();
      $message->id = $row["Id"];
      $message->title = $row["Title"];
      $message->reference = $row["Reference"];
      $message->service = $row["Service"];
      $message->description = $row["Description"];
      $message->date = $row["Date"];
      $message->speaker = $row["Speaker"];
      $message->file = $row["File"];
      $message->archived = $row["Archived"];
      $messages[] = $message;
    }
    $pdo = null;
    return $messages;
  }

  /**
   * Get a list of messages that are suitable to show to the public.
   */
  function GetAllMessagesForListeners()
  {
    $messages = array();
    $pdo = $this->GetConnection();
    $sql = "SELECT Id,Title,Service,Reference,Date,Speaker,File,Archived FROM Messages WHERE File IS NOT NULL AND File != '' ORDER BY Date DESC, Service DESC";
    foreach($pdo->query($sql) as $row)
    {
      $message = new Message();
      $message->id = $row["Id"];
      $message->title = $row["Title"];
      $message->reference = $row["Reference"];
      $message->service = $row["Service"];
      $message->date = $row["Date"];
      $message->speaker = $row["Speaker"];
      $message->file = $row["File"];
      $message->archived = $row["Archived"];
      $messages[] = $message;
    }
    $pdo = null;
    return $messages;
  }

  function GetAllMessages()
  {
    $messages = array();
    $pdo = $this->GetConnection();
    $sql = "SELECT Id,Title,Service,Reference,Date,Speaker,File,Archived FROM Messages ORDER BY Date DESC";
    foreach($pdo->query($sql) as $row)
    {
      $message = new Message();
      $message->id = $row["Id"];
      $message->title = $row["Title"];
      $message->reference = $row["Reference"];
      $message->service = $row["Service"];
      $message->date = $row["Date"];
      $message->speaker = $row["Speaker"];
      $message->file = $row["File"];
      $message->archived = $row["Archived"];
      $messages[] = $message;
    }
    $pdo = null;
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
    $message->id = $messageId;
    $message->title = $row["Title"];
    $message->reference = $row["Reference"];
    $message->service = $row["Service"];
    $message->description = $row["Description"];
    $message->date = $row["Date"];
    $message->speaker = $row["Speaker"];
    $message->file = $row["File"];
    $message->archived = $row["Archived"];
    $message->lastMaintOpId = $row["LastMaintOpid"];
    $message->lastMaintDateTime = $row["LastMaintDateTime"];

    return $message;
  }

  /**
   * Adds a message.
   *
   * @param Message Message to be added.
   *
   * @return int Id of added message.
   */
  function AddMessage($message)
  {
    $date = date("Y-m-d",strtotime($message->date));
    $dbh = $this->GetConnection();
    $stmt = $dbh->prepare("INSERT INTO Messages (Title, Reference, Date, Service, Speaker, File, Length, Duration, Guid, Archived, Description) VALUES (:title, :reference, :date, :service, :speaker, :file, :length, :duration, :guid, :archived, :description)");
    $stmt->bindParam(':title', $message->title);
    $stmt->bindParam(':reference', $message->reference);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':service', $message->service);
    $stmt->bindParam(':speaker', $message->speaker);
    $stmt->bindParam(':file', $message->file);
    $stmt->bindParam(':length', $message->length);
    $stmt->bindParam(':duration', $message->duration);
    $stmt->bindParam(':guid', $message->guid);
    $stmt->bindParam(':archived', $message->archived);
    $stmt->bindParam(':description', $message->description);
    $stmt->execute();
    $lastId = $dbh->lastInsertId();
    return $lastId;
  }

  function UpdateMessage($message)
  {
    $date = date("Y-m-d",strtotime($message->date));
    $dbh = $this->GetConnection();
    $stmt = $dbh->prepare("UPDATE Messages SET Title=:title, Reference=:reference, Date=:date, Service=:service, Speaker=:speaker, File=:file, Archived=:archived, Description=:description, LastMaintDateTime=:currentTimestamp WHERE Id=:id");
    $stmt->bindParam(':id', $message->id);
    $stmt->bindParam(':title', $message->title);
    $stmt->bindParam(':reference', $message->reference);
    $stmt->bindParam(':date', $date);
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
