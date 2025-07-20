<?php
    $host = "localhost";
    $user = "root";
    $password = "root";
    $database = "message_board";

    $conn = new mysqli($host, $user, $password, $database);

    if($conn->connect_error){
        die("Koneksi gagal: " . $conn->connect_error);
    }

    function generateNewIdMessage($conn) : string{
        $result = $conn->query("SELECT id_message FROM messages ORDER BY id_message DESC LIMIT 1");
        $idBaru = "";

        if($row = $result->fetch_assoc()){
            $angkaLama = substr($row["id_message"], 3);
            $idBaru = "msg" . sprintf("%03d", $angkaLama + 1);
        }else{
            return "msg001";
        }

        return $idBaru;
    }
?>