<?php

//database_connection.php 


 $servername = "localhost";
                    $username = "root";
                    $password = "password";
                    $dbname = "solardata";

                    // Create connection
                    $conn = mysqli_connect($servername, $username, $password, $dbname);
                    // Check connection
                    if (!$conn) {
                        die("Connection failed: " . mysqli_connect_error());
                    }
?>
