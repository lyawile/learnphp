<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   $username = $_POST['username'];
   $password = $_POST['password'];
   if($username === 'ally' && $password === 'ally'){
       session_start();
       $_SESSION['loggedIn'] = $username; // set username to a session variable 
       header("location: order.php");
   }else {
       echo 'incorrect username or password';
   }
}
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>Basic Hotel Management</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <div>
            <form method="POST">
                 <label>Username</label>
            <input type="text" name="username" />
            <label>Password</label>
            <input type="password" name="password"/>
            <input type="submit" value="Login"/>
            </form>
        </div>
    </body>
</html>

