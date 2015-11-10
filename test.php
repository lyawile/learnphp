<?php
include_once './config/dbconnect.php';
function randomNumber(){
    $random = rand(10, 100);
    return $random;
}
echo randomNumber(). '<br/>';

function numberRange($first , $last){
    for($index = $first; $index <= $last; $index++){
        echo $index;
        
    }
}
//numberRange(10, 12);
//echo '<br/>';
//numberRange(8, -1);


