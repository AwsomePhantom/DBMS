<?php
    static $pdo;
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=dbmsproject;charset=utf8mb4', 'root', 'root',
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);

        if($_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['personId']) &&
            isset($_POST['phone']) &&
            isset($_POST['submit'])) {

            $a = $_POST['personId'];
            $b = $_POST['phone'];

            unset($_POST['personId']);
            unset($_POST['phone']);

            $sql = 'INSERT INTO contacts VALUES (null, ?, ?)';
            $stmt = $pdo->prepare($sql);
            $res = $stmt->execute([
                [$a, $b]
            ]);
            if(!$res) {
                echo 'failed to insert values<br>';
            }
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['deleteButton'])) {
                $row = $_POST['deleteButton'];
                $sql = 'DELETE FROM contacts WHERE id = ?';
                $stmt = $pdo->prepare($sql);
                $res = $stmt->execute($row);
            }
    }
    catch (PDOException $e) {
        echo "Error: {$e->getMessage()}<br>{$e->getCode()}";
    }
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <!-- Bootstrap 5.3.3 import CDNs -->

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" integrity="sha384-4LISF5TTJX/fLmGSxO53rV4miRxdg84mZsxmO8Rx5jGtp/LbrixFETvWa5a6sESd" crossorigin="anonymous">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap');
        body {
            font-family: "Rubik", sans-serif;
        }
        legend {
            font-weight: lighter;
        }
        input[type='submit'] input[type='text'] {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }
        input[type='number'] input[type='text'] {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</head>
<body>
<div class="container w-75 my-3">
    <form name="form">
        <div class="input-group">
            <input name="personId" class="form-control" type="number" placeholder="103">
            <input name="phone" class="form-control" type="text" placeholder="Phone number">
            <input name="submit" class="btn btn-block btn-primary" type="submit" value="Enter">
        </div>
    </form>
</div>
<div class="container-fluid">
    <form name="deleteForm" method="post"></form>
    <table class="table table-striped">
        <thead>
        <tr>
            <td>Entry ID</td>
            <td>Person ID</td>
            <td>Phone Number</td>
            <td>Options</td>
        </tr>
        </thead>
        <tbody>
        <?php
        try {
            $sql = 'SELECT * FROM contacts';
            $resultSet = $pdo->query($sql);
            $count = 1;
            while($x = $resultSet->fetch()) {
                echo '<tr>';
                echo "<td row=\"{$count}\">{$x['id']}</td>";
                echo "<td>{$x['person_id']}</td>";
                echo "<td>{$x['phone']}</td>";
                echo "<td><button form=\"deleteForm\" name=\"deleteButton\" class=\"btn btn-sm btn-secondary\" type=\"submit\" value=\"{$count}\">Delete</button></td>";
                echo '</tr>';
                $count++;
            }
        }
        catch (PDOException $e) {
            echo "Error: {$e->getMessage()}<br>{$e->getCode()}";
        }
        ?>
        </tbody>
    </table>
</div>
</body>
</html>