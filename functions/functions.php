<?php
function clean_input($connection, $input){
    return mysqli_real_escape_string($connection , $input);
    
}


