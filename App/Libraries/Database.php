<?php
try {
    $dbh = new PDO('mysql:host=localhost;dbname=blogAula', 'root', '');
} catch (PDOException $e) {
    // tentar reconectar após algum intervalo, por exemplo
    echo "Error".$e;
}