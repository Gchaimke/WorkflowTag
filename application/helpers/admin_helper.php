<?php

function admin_log($msg, $level = 0,$user){
    if (!file_exists('application/logs/admin')) {
        mkdir('application/logs/admin', 0770, true);
    }
    $level_arr = array('INFO', 'CREATE', 'TRASH', 'DELETE');
    $log_file = APPPATH . "logs/admin/" . date("m-d-Y") . ".log";
    $fp = fopen($log_file, 'a'); //opens file in append mode  
    fwrite($fp, $level_arr[$level] . " - " . date("H:i:s") . " --> " . $user . " - " . $msg . PHP_EOL);
    fclose($fp);
}
