<?php
include_once "dbconnect.php";

date_default_timezone_set("Asia/Kolkata");
function run_mysql_query($query){
    global $mysqli;
    $exec_query = $mysqli->query($query);
    $rowcount = mysqli_num_rows($exec_query);
    if ($exec_query){
        return $rowcount;
    }else{
        return "Error: " . $query . "<br>" . $mysqli -> error;
    }
    
}

function errorlog($msg){
    $username ="Ayesha Shah";
    $date = date("y-m-d H:i:s.");
    $log = $msg."   |  Date:  ".$date."  |  User:  ".$username."\n";
    error_log($log, 3, "../../var/tmp/error-log.txt");

}
    //$sanitize_ph_num = '741852963';
   // $query = "SELECT user_phone_nums from user_detail where user_phone_num = '".$sanitize_ph_num."'";

    //$test = run_mysql_query($query);
    //echo "query run=> ".$test;
?>