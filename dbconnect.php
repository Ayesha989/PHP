<?php
$mysqli = new mysqli("localhost","root","test@123","expand");

// Check connection
if ($mysqli -> connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
  exit();
} else {
    echo "connected";
}


?>