<?php

// Very basic auth, set to something secure
$api_secret = "";


// Execute MySQL query
function exec_query($conn, $query, $ret = NULL, $out = NULL) {
    $q = mysqli_query($conn, $query);

    if(!$q) {
        exit("MySQL query failed: ".mysqli_error($conn));
    }

    if(isset($ret)) {
        return mysqli_fetch_assoc($q);
    }

    if(isset($out)) {
        echo json_encode($out);
    }
}


// Get wristband ID
function get_wristband_id() {
    if(!ctype_digit($_POST["band_id"])) {
        exit("Wristband ID must be integer.");
    }
    
    if(!empty($_POST["band_id"])) {
        return $_POST["band_id"]; 
    }
    else {
        exit("No wristband ID.");
    }
}


// Get pulse
function get_pulse() {
    if(!ctype_digit($_POST["pulse"])) {
        exit("Pulse must be integer.");
    }

    if(!empty($_POST["pulse"])) {
        return $_POST["pulse"];
    }
    else {
        exit("No pulse.");
    }
}

// Get ID
function get_id() {
    if(!ctype_digit($_POST["id"])) {
        exit("ID must be integer.");
    }

    if(!empty($_POST["id"])) {
        return $_POST["id"];
    }
    else {
        exit("No ID.");
    }
}


// Get ID from database
function get_id_db($injured_table, $conn, $band_id) {
    valid_init($injured_table, $conn, $band_id);

    // Get ID from database
    $result = exec_query($conn, "select id from $injured_table where opaska_id = $band_id and nadawanie = false and aktywna_opaska = false and w_akcji = true", TRUE);
    $id = $result['id'];
    //echo "\nID: ".$id;
    return $id;
}


// Validate wristband activation
function valid_init($injured_table, $conn, $band_id) {
    $result = exec_query($conn, "select count(opaska_id) as count from $injured_table where w_akcji = true and opaska_id = $band_id", TRUE);
    $count_in_action = $result['count'];
    //echo "In action: ".$count_in_action;

    // Check for duplicate in action wristband entries
    if($count_in_action > 1) {
        exit("Duplicate in action entries for wristband with ID $band_id.");
    }

    // Check if entry exist for given wristband ID
    if($count_in_action == 0) {
        exit("In action entry for wristband with ID $band_id doesn't exist.");
    }

    $result = exec_query($conn, "select count(opaska_id) as count from $injured_table where nadawanie = true and opaska_id = $band_id", TRUE);
    $count_broadcasting = $result['count'];
    //echo "\nBroadcasting: ".$count_broadcasting;

    // Check if wristband with given ID is already broadcasting
    if($count_in_action == 1 && $count_broadcasting > 0) {
        exit("Wristband with ID $band_id is already broadcasting.");
    }

    $result = exec_query($conn, "select count(opaska_id) as count from $injured_table where aktywna_opaska = true and opaska_id = $band_id", TRUE);
    $count_active = $result['count'];
    //echo "\nActive: ".$count_active;

    // Check if wristband with given ID is already active
    if($count_active > 0) {
        exit("Wristband with ID $band_id is already active.");
    }

    $result = exec_query($conn, "select count(opaska_id) as count from $injured_table where opaska_id = $band_id and nadawanie = false and aktywna_opaska = false and w_akcji = true", TRUE);
    $valid = $result['count'];
    //echo "\nValid: ".$valid;

    // Check if ready to activate
    if($valid != 1) {
        exit("No valid entry for wristband with ID $band_id.");
    }
}


// Validate update request
function valid_update($injured_table, $conn, $id) {
    $result = exec_query($conn, "select count(id) as count from $injured_table where id = $id and nadawanie = true and aktywna_opaska = false and w_akcji = true", TRUE);
    $valid = $result['count'];
    //echo "\nValid: ".$valid;

    // Check if ready to update
    if($valid != 1) {
        exit("No valid entry with ID $id for updating.");
    }
}


// Receive json
$_POST = json_decode(file_get_contents("php://input"), true);


// MAIN
if($_POST["secret"] == $api_secret) {
    if(isset($_POST["mode"])) {
        $host="";
        $user="";
        $pass="";
        $db="myDB";
        $injured_table="rani";

        // Connect to DB
        $conn = mysqli_connect($host, $user, $pass) or exit("Connection failed.");
        mysqli_select_db($conn, $db) or exit("Couldn't select database.");

        switch($_POST["mode"]) {
            // Activate wristband with given ID
            case "activate-band":
                $band_id = get_wristband_id();
                $pulse = get_pulse();

                $id = get_id_db($injured_table, $conn, $band_id);
                
                $out = array(
                    "id" => $id
                );

                // Activate wristband
                exec_query($conn, "update $injured_table set nadawanie = true, tetno = $pulse where id = $id", NULL, $out);
                break;

            // Update wristband data with given ID
            case "update-band":
                $id = get_id();
                $pulse = get_pulse();

                valid_update($injured_table, $conn, $id);

                // Update data
                exec_query($conn, "update $injured_table set tetno = $pulse where id = $id");
                break;

            // Deactivate wristband with given ID
            case "deactivate-band":
                $id = get_id();

                // Deactivate wristband
                exec_query($conn, "update $injured_table set nadawanie = false, aktywna_opaska = false where id = $id");
                break;

            default:
            exit("Invalid mode.");
            break;
        }

        mysqli_close($conn);
    }
    else {
        exit("Invalid POST parameters.");
    }
}
else {
    exit("Permission denied.");
}
