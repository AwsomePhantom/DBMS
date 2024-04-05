<?php

    try {
        $pdo = new PDO('mysql:host=localhost;dbname=dbmsproject;charset=utf8mb4', 'root', 'root',
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_BOTH]);

        $user = 'rabinul';
        $pass = '1234';

        $sql = "SELECT * FROM user_accounts WHERE username = \"{$user}\" AND password = \"{$pass}\" LIMIT 1";
        $stmt = $pdo->query($sql);
        $user_row = $stmt->fetch();

        $c_id = $user_row['customer_id'];
        $sql = "SELECT * FROM customers_info WHERE id = \"{$c_id}\" LIMIT 1";
        $stmt = $pdo->query($sql);
        $c_row = $stmt->fetch();

        $b_id = $user_row['business_id'];
        $b_row = null;
        if($b_id != null) {
            $sql = "SELECT * FROM businesses_info WHERE id = \"{$b_id}\" LIMIT 1";
            $stmt = $pdo->query($sql);
            $b_row = $stmt->fetch();
        }

        print_r($user_row);
        echo "<br><br>";
        print_r($c_row);
        echo "<br><br>";
        print_r($b_row);
        echo "<br><br>";



    }

    catch (PDOException $e) {
        echo "Error: {$e->getMessage()}<br>";
    }
