<?php
if(isset($_COOKIE['USER_INFO'])) {
    header('Location: home.php');
}
?>
<form name="registerForm" method="post" action="connection.php">
    <table>
        <tr>
            <td><label for="hostField">Name</label></td><td><input name="nameField" type="text"></td>
        </tr>
        <tr>
            <td><label for="surnameField">Last Name</label></td><td><input name="surnameField" type="text"></td>
        </tr>
        <tr>
            <td><label for="dateField">Birthdate</label></td><td><input name="dateField" type="date"></td>
        </tr>
        <tr>
            <td><label for="userField">Username</label></td><td><input name="userField" type="text"></td>
        </tr>
        <tr>
            <td><label for="passField">Password</label></td><td><input name="passField" type="password"></td>
        </tr>
        <tr>
            <td><input type="submit" name="registerButton" value="Register"></td><td><input type="reset" name="resetButton" value="Clear"></td>
        </tr>
        <input type="hidden" name="registrationFlag" value="true">
    </table>
</form>
