<?php
include "database.php";
session_start();

$obj = new Database();
$obj->_construct();

$sql = "SELECT * FROM frequencies where id=".$_POST['file_list'];
$query = $obj->getConn()->prepare($sql);
$query -> execute();
$frequency = $query->fetch(PDO::FETCH_OBJ);

$myFile = "files/".$frequency->id.".txt";
$fo = fopen($myFile, 'a') or die("can't open file");

$stringData = $frequency->content;

fwrite($fo, $stringData);
fclose($fo);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  header('Content-Description: File Transfer');
  header('Content-Type: application/octet-stream');
  header("Cache-Control: no-cache, must-revalidate");
  header("Expires: 0");
  header('Content-Disposition: attachment; filename="'.($frequency->created_at.'.txt').'"');
  header('Content-Length: ' . filesize($myFile));
  header('Pragma: public');

  flush();
  readfile($myFile);
  exit();
}
