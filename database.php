<?php
include('config.php');
class Database
{
  protected $conn;
  protected $servername = DBHOST;
  protected $username = DBUSER;
  protected $password = DBPWD;
  protected $dbname = DBNAME;

  function _construct()
  {
    try {
      $db = new PDO("mysql:host=$this->servername;charset=utf8", $this->username, $this->password);
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $database = "CREATE DATABASE IF NOT EXISTS " . $this->dbname . ";";
      $db->exec($database);

      $this->conn = new PDO("mysql:host=$this->servername;charset=utf8", $this->username, $this->password);
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $this->create_table_users();
      $this->create_table_frqs();
      if($this->create_table_users() || $this->create_table_frqs())
        return "success";
      else
        return "error";
    } catch (PDOException $e) {
      return $e->getMessage();
    }
  }

  function create_table_users(){
    try{
        $user=" 
        use ".$this->dbname.";
        CREATE TABLE IF NOT EXISTS users (
            id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(200) NOT NULL, 
            password VARCHAR(200) NOT NULL,
            is_admin TINYINT(4) DEFAULT 0,
            is_active TINYINT(4) DEFAULT 0,
            activation_code VARCHAR(255) UNIQUE,
            created_at DATETIME NULL
        )ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";

        if($this->conn->exec($user))
          return true;
        else
          return false;
    }
    catch(PDOException $e)
    {
        return $e->getMessage();
    }
}

  protected function create_table_frqs()
  {
    try {
      $file = " 
            use ".$this->dbname.";
            CREATE TABLE IF NOT EXISTS frequencies (
                id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                content longtext NOT NULL, 
                created_at DATETIME NOT NULL,
                updated_at DATETIME NOT NULL,
                user_id INT(11) NOT NULL
            )ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
            ALTER TABLE frequencies FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE";

      if($this->conn->exec($file))
        return true;
      else
        return false;
    } catch (PDOException $e) {
      return $e->getMessage();
    }
    return;
  }

  public function getConn()
  {
    return $this->conn;
  }
}
