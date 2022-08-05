<?php

function getConnection(){
    $conn = false;

    try {
        $conn = new PDO('sqlite:db.sqlite3');
        return $conn;
    } catch(PDOException $e) {
        // header('Location: '. site_url());
        // exit;
        // die('Erro ao conectar ao banco de dados');

        echo 'ERROR: ' . $e->getMessage();
    }

    return $conn;
}