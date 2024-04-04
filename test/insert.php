<?php
    define("URI", "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    function resetPost() {
        unset($_POST);
        header("Location: " . URI);
    }
    function setLog($str) {
        global $log;
        $log = 'Log message: ' . $str;
    }


    static $pdo;
    static $log = 'Log Message: none';

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

            $sql = 'INSERT INTO contacts VALUES (null, ?, ?)';
            $stmt = $pdo->prepare($sql);
            $res = $stmt->execute([
                $a, $b
            ]);
            if(!$res) {
                setLog('Failed to insert value. ' . $stmt->errorInfo());
            }
            else setLog(null);
            resetPost();
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['deleteButton'])) {
                $row = $_POST['deleteButton'];

                $sql = 'DELETE FROM contacts WHERE id = ?';
                $stmt = $pdo->prepare($sql);
                $res = $stmt->execute([$row]);
                if(!$res) {
                    setLog($stmt->errorInfo());
                }
                else setLog(null);
                resetPost();
        }

    }
    catch (PDOException $e) {
        $log = 'Log Message: ';
        $log .= "Error: {$e->getMessage()}<br>{$e->getCode()}";
        resetPost();
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
    <form method="POST">
        <div class="input-group">
            <input name="personId" class="form-control" type="number" placeholder="Person ID: [1-9]+">
            <input name="phone" class="form-control" type="text" placeholder="Phone number: [1-9]+">
            <input name="submit" class="btn btn-block btn-primary" type="submit" value="Enter">
        </div>
    </form>
</div>
<div class="container-fluid">
    <form method="POST" id="deleteForm"></form>
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
            if($resultSet->rowCount() == 0) {
                $pdo->query('ALTER TABLE contacts AUTO_INCREMENT = 1');
            }
            while($x = $resultSet->fetch()) {
                echo '<tr>';
                echo "<td>{$x['id']}</td>";
                echo "<td>{$x['person_id']}</td>";
                echo "<td>{$x['phone']}</td>";
                echo "<td><button form=\"deleteForm\" name=\"deleteButton\" class=\"btn btn-sm btn-secondary\" type=\"submit\" value=\"{$x['id']}\">Delete</button></td>";
                echo '</tr>';
            }
        }
        catch (PDOException $e) {
            $log = 'Log message: ';
            $log .= 'Code ' . $e->getCode() . '&#9;' . $e->getMessage();
        }
        ?>
        </tbody>
    </table>
</div>
<footer class="container-fluid bg-dark-subtle" style="bottom: 0;position: absolute">
    <small><?php echo $log . '<br>'; ?></small>
</footer>
</body>
</html>