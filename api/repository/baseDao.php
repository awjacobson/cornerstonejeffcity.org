<?php
class BaseDao
{
  var $dbHost;
  var $dbUserLogin;
  var $dbPassword;
  var $dbName;

  function __construct($dbHost, $dbUserLogin, $dbPassword, $dbName)
  {
    $this->dbHost = $dbHost;
    $this->dbUserLogin = $dbUserLogin;
    $this->dbPassword = $dbPassword;
    $this->dbName = $dbName;
  }

  function GetConnection()
  {
    $dsn = sprintf('mysql:dbname=%s;host=%s', $this->dbName, $this->dbHost);
    $options = [
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try
    {
      $dbh = new PDO($dsn, $this->dbUserLogin, $this->dbPassword, $options);
    }
    catch (Exception $e)
    {
      echo 'not connected<br/>';
      echo $e->getMessage();
      exit;
    }
    return $dbh;
  }

  function Query($sql)
  {
    $dbh = $this->GetConnection();
    $stmt = $dbh->query($sql);

    if(!$result)
    {
    	echo "<font color=red>There was an error querying the mySQL database.</font><br><br>";
    	echo "The error was reported as:<br>";
    	echo mysql_error();
      echo '<p class="bg-warning">'.$sql.'</p>';
    	exit;
    }
    return $result;
  }
}
?>
