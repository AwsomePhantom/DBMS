<?php

global $conn;
try {
    $conn = new PDO('mysql:host=localhost;dbname=dbmsproject;charset=UTF8', 'root', 'root',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo 'Connected.<br>';
    $conn->beginTransaction();

    $sql = 'INSERT INTO person (name, lastname, birthdate, gender) VALUES (:name, :lastname, :birthdate, :gender);';
    $stmt = $conn->prepare($sql);

    $name = 'rabinul';
    $lastname = 'islam';
    $birthdate = '1992-11-10';
    $gender = 'M';
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':lastname', $lastname, PDO::PARAM_STR);
    $stmt->bindParam(':birthdate', $birthdate, PDO::PARAM_STR);
    $stmt->bindParam(':gender', $gender, PDO::PARAM_STR_CHAR);
    $stmt->execute();

    $conn->commit();
}
catch (PDOException $e) {
    $conn->rollBack();
    echo $e->getMessage() . '<br>';
}



