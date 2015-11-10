<?php
$connection = @ mysqli_connect('localhost', 'root','','hotel_db');
if(!$connection){
    echo 'connection not successful';
}

