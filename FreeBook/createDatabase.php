<?php
include "connect.php";
// Create database

try {
    $sql = "CREATE DATABASE demo";
    $pdo->exec($sql);
    echo "Database created successfully<br>";
} catch(PDOException $e) {
    die ("Error: Could not able to execute $sql. " . $e->getMessage() . "<br>");

}

//Close connecion
unset($pdo);
?>
