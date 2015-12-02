<?php
class BaseDao
{
  var $dbHost;
  var $dbUserLogin;
  var $dbPassword;
  var $dbName;
  var $dbh;

  function BaseDao($dbHost, $dbUserLogin, $dbPassword, $dbName)
  {
    $this->dbHost = $dbHost;
    $this->dbUserLogin = $dbUserLogin;
    $this->dbPassword = $dbPassword;
    $this->dbName = $dbName;
  }

  function GetConnection()
  {
    $dsn = sprintf('mysql:dbname=%s;host=%s', $this->dbName, $this->dbHost);
    try
    {
      $dbh = new PDO($dsn, $this->dbUserLogin, $this->dbPassword);
    }
    catch (PDOException $e)
    {
      echo $e->getMessage();
      exit;
    }
    return $dbh;
  }

  function Query($sql)
  {
    $link=@mysql_connect($this->dbHost, $this->dbUserLogin, $this->dbPassword);
    if(!$link)
    {
    	echo "<font color=red>There was an error connecting to the mySQL server.</font><br><br>";
    	echo "The error was reported as:<br>";
    	echo mysql_error();
    	exit;
    }
    if(!@mysql_select_db($this->dbName,$link))
    {
    	echo "<font color=red>There was an error connecting to the mySQL database.</font><br><br>";
    	echo "The error was reported as:<br>";
    	echo mysql_error();
    	exit;
    }
    $result=@mysql_query($sql,$link);
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
