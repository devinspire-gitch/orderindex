<?php
include "database.php";
session_start();
class action extends Database{
    protected $conn;
    function _construct(){
        parent::_construct();
        $this->conn = parent::getConn();
    }
    function getConn()
    {
        return $this->conn;
    }

    public function add(){
        $sql = "INSERT INTO frequencies(content, created_at, updated_at, user_id) VALUES(:content, :created_at, :updated_at, :user_id)";

        $content = json_decode(file_get_contents("php://input"), true);
        $created_at = date("Y-m-d H:i:s");
        $updated_at = date("Y-m-d H:i:s");
        $user_id = $_SESSION["id"];

        $query = $this->getConn()->prepare($sql);
        $query->bindParam(':content',$content,PDO::PARAM_STR);
        $query->bindParam(':created_at',$created_at,PDO::PARAM_STR);
        $query->bindParam(':updated_at',$updated_at,PDO::PARAM_STR);
        $query->bindParam(':user_id',$user_id,PDO::PARAM_INT);
        $query->execute();
        $id = $this->getConn()->lastInsertId();

        echo json_encode(array('id' => $id, 'time' => $created_at));
    }
}

$obj = new action();
$obj->_construct();

$obj->add();
