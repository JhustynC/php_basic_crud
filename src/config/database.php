<?php
$host = 'db';
$user = 'root';
$password = 'rootpass';
$dbname = 'appdb';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
  die("Conexión fallida: " . $conn->connect_error);
}
?>
