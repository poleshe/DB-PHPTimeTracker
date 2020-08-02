<?php
//
// PHP Script to use the time tracker over command line.
//

// Switch case for the different argument options.
if(isset($argv[1])){
    switch ($argv[1]) {
        case "start":
            start($argv[2]);
            break;
        case "stop":
            stop($argv[2]);
            break;
        case "list":
            listitems();
            break;
        default:
            help();
            break;
    }
} else {
    help();
}
// Start a Time Tracker on a Task using CURL.
function start($name){
    print("Starting Time Tracker on Task ".$name).".\n";
    // Initialize curl
    $ch = curl_init();
    // Set curl params
    curl_setopt($ch, CURLOPT_URL,"http://localhost:8001/start");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "name=".$name);
    // Receive server response
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec($ch);
    // Close curl
    curl_close ($ch);
    // Managing results
    var_dump($server_output);
}

// Stop a Time Tracker on a Task using CURL.
function stop($name){
    print("Stopping Time Tracker on Task ".$name).".\n";
    // Initialize curl
    $ch = curl_init();
    // Set curl params
    curl_setopt($ch, CURLOPT_URL,"http://localhost:8001/");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "name=".$name);
    // Receive server response
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec($ch);
    // Close curl
    curl_close ($ch);
    // Managing results
    print("Stopped succesfully \n");
}

// Get an SQL-Like list from all items on the DB using CURL.
function listitems(){
    // Initialize curl
    $ch = curl_init();
    // Set curl params
    curl_setopt($ch, CURLOPT_URL,"http://localhost:8001/list");
    curl_setopt($ch, CURLOPT_POST, 1);
    // Receive server response
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec($ch);
    // Close curl
    curl_close ($ch);
    // Managing results
    $times_list = json_decode($server_output);
    // Print out a table with the data.
    print("# NAME # STATUS # START TIME # END TIME # TOTAL TIME # \n");
    print("----------------------------------------------------------------------------------------\n");
    foreach ($times_list as $time) {
        // Swap boolean for string
        if($time->status == 0){
            $status = "Stopped";
        } else {
            $status = "Counting";
        }
        // Get total hours
        $totalhours = substr($time->total_time->date, 0, 19);
        $totalhours = substr($totalhours, 11);

        print("|".$time->name." | ".$status." | ".$time->start_time->date." | ".$time->end_time->date." | ".$totalhours."|\n");
        print("----------------------------------------------------------------------------------------\n");
    }
}

// Print the help panel. Also appears if there is no arguments.
function help(){
    print("############################################\n");
    print("# Arguments for this script:               #\n");
    print("# start (name): Start a Task               #\n");
    print("# stop (name): Stop a Task                 #\n");
    print("# list: Displays a list with all the Tasks #\n");
    print("# help: Displays this information panel    #\n");
    print("############################################\n");
}

?>