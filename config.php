<?php
 $servername = "localhost"; // Indirizzo del server
        $username = "root"; // Nome utente del database
        $password = ""; // Password ( vuota per ambienti locali )
        $dbname = "gite"; // Nome del database
        
        $conn=mysqli_connect($servername , $username , $password ,$dbname);
        if(!$conn){
            die ("Connessione fallita : " . $conn );
        }
?>