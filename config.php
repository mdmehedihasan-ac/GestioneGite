<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestionegite";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connessione fallita: " . mysqli_connect_error());
}
?>