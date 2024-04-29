<?php
include_once ('variables.php');
global $host, $user, $password, $db, $port;
$name = null;
$surname = null;
$date = null;
$log = null;
$USER_INFO = json_decode($_COOKIE['USER_INFO'], true);

if(!isset($_COOKIE['USER_INFO'])) {
    header('Location: menu.php');
}
else {
    $name = $USER_INFO[0];
    $surname = $USER_INFO[1];
    $date = $USER_INFO[2];
}
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['logoutButton'])) {
        setcookie('USER_INFO', '', time() - 3600, '/');
        header('Location: menu.php');
    }
    if(isset($_POST['modifyButton']) && isset($_COOKIE['USER_INFO'])) {
        $name = $_POST['nameField'];
        $surname = $_POST['surnameField'];
        $date = $_POST['dateField'];
        $sql = "UPDATE phptable SET NAME = '{$name}', LASTNAME = '{$surname}', BIRTHDATE = '{$date}' WHERE USERNAME = '{$USER_INFO[3]}'";
        $conn = new mysqli($host, $user, $password, $db, $port);
        if(!$conn->connect_error && $conn->query($sql) === true) {
            $USER_INFO[0] = $name;
            $USER_INFO[1] = $surname;
            $USER_INFO[2] = $date;
            setcookie('USER_INFO', json_encode($USER_INFO), time() + 3600, '/');
            $log .= "Entries updated successfully.<br>";
        }
        else {
            die("Failed to update data, database connection/query error");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>DBMS Assignment 01</title>
</head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" href="styles.css">
<body>
<form method="post">
    <div>
        <input name="logoutButton" type="submit" value="Logout">
    </div>
</form>
<?php
echo "Welcome back, {$USER_INFO[0]} {$USER_INFO[1]}. <br>";
echo "Username: {$USER_INFO[3]}. <br>";
?>

<p>Modify profile</p>
<form method="post">
    <table>
        <tr>
            <td><label for="nameField">Name:</label></td><td><input type="text" name="nameField" value="<?php echo $name; ?>"></td>
        </tr>
        <tr>
            <td><label for="surnameField">Last Name:</label></td><td><input type="text" name="surnameField" value="<?php echo $surname; ?>"></td>
        </tr>
        <tr>
            <td><label for="dateField">Birthdate:</label></td><td><input type="date" name="dateField" value="<?php echo $date; ?>"></td>
        </tr>
        <tr>
            <td></td><td><input type="submit" name="modifyButton" value="Commit Changes"></td>
        </tr>
    </table>
</form>
<?php echo $log; ?>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>