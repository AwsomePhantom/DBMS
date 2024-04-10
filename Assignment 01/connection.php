<?php
include_once ('variables.php');
global $host, $user, $password, $db, $port;


if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['registrationFlag'])) {
        //register
        $RINFO = array($_POST['nameField'], $_POST['surnameField'], $_POST['dateField'], $_POST['userField'], $_POST['passField']);
        $conn = new mysqli($host, $user, $password, $db, $port);
        if($conn->connect_error) {
            echo "Failed to connect to the database.<br><a href='#' onclick='history.back()'>Go back</a>";
            return;
        }
        $sql = "SELECT USERNAME FROM phptable WHERE USERNAME = '{$RINFO[3]}' LIMIT 0, 1";
        $result = $conn->query($sql);
        if($result && $result->num_rows > 0) {
            echo "Username already used.<br><a href='#' onclick='history.back()'>Go back</a>";
            return;
        }
        $sql = "INSERT INTO phptable values (null, '{$RINFO[0]}', '{$RINFO[1]}', '{$RINFO[2]}', '{$RINFO[3]}', PASSWORD('{$RINFO[4]}'), NOW())";
        if($conn->query($sql) === true) {
            echo "Registration successful<br><a href='#' onclick='history.back()'>Go back</a>";
            return;
        }
        else {
            echo "Query processing error: $conn->errno.<br><a href='#' onclick='history.back()'>Go back</a>";
        }
    }
    else if(isset($_POST['loginFlag'])) {
        //login
        $LINFO = array($_POST['userField'], $_POST['passField']);
        $conn = new mysqli($host, $user, $password, $db, $port);
        if($conn->connect_error) {
            echo "Failed to connect to the database.<br><a href='#' onclick='history.back()'>Go back</a>";
            return;
        }
        $sql = "SELECT NAME, USERNAME, LASTNAME, BIRTHDATE FROM phptable WHERE USERNAME = '{$LINFO[0]}' AND PASSWORD = PASSWORD('{$LINFO[1]}') LIMIT 0, 1";
        $result = $conn->query($sql);
        if($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $USER_INFO = array($row['NAME'], $row['LASTNAME'], $row['BIRTHDATE'], $row['USERNAME']);
            setcookie('USER_INFO', json_encode($USER_INFO), time() + 3600, '/');
            echo "Login successful.<br>";
            sleep(3);
            header('Location: articles.php');
        }
        else {
            echo "Username/Password mismatch!<br><a href='#' onclick='history.back()'>Go back</a>";
        }
    }
}
