<?php
if(isset($_COOKIE['USER_INFO'])) {
    header('Location: home.php');
}
?>
<form name="loginForm" method="post" action="connection.php">
    <table>
        <tr>
            <td><label for="hostField">Username</label></td><td><input name="userField" type="text"></td>
        </tr>
        <tr>
            <td><label for="userField">Password</label></td><td><input name="passField" type="password"></td>
        </tr>
        <tr>
            <td><input type="submit" name="loginButton" value="Login"></td><td><input type="reset" name="resetButton" value="Clear"></td>
        </tr>
        <input type="hidden" name="loginFlag" value="true">
    </table>
</form>
