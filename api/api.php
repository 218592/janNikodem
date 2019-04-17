<?php
header("Content-Type: application/json");

// Very basic auth, set to something secure
$api_secret = "";


// Exit with json
function exit_json($out) {
    $arr = array(
        "status" => "error",
        "error" => $out
    );
    exit(json_encode($arr));
}


// Echo with json
function echo_json($out = NULL) {
    if($out != NULL) {
        $arr = array(
            array(
                "status" => "success"
            ),
            array(
                "status" => "data",
                "data" => $out
            ));
    }
    else {
        $arr = array(
            "status" => "success"
        );
    }
    echo json_encode($arr);
}


// Execute MySQL query
function exec_query($conn, $query, $ret = NULL, $out = NULL) {
    $q = mysqli_query($conn, $query);

    if(!$q) {
        exit_json("MySQL query failed: ".mysqli_error($conn));
    }

    if(isset($ret)) {
        return mysqli_fetch_assoc($q);
    }

    if(isset($out)) {
        echo_json($out);
    }
    else {
        echo_json();
    }
}


// Get wristband ID
function get_wristband_id() {
    if(!ctype_digit($_POST["band_id"])) {
        exit_json("Wristband ID must be integer.");
    }
    
    if(!empty($_POST["band_id"])) {
        return $_POST["band_id"]; 
    }
    else {
        exit_json("No wristband ID.");
    }
}


// Get pulse
function get_pulse() {
    if(!ctype_digit($_POST["pulse"])) {
        exit_json("Pulse must be integer.");
    }

    if(!empty($_POST["pulse"])) {
        return $_POST["pulse"];
    }
    else {
        exit_json("No pulse.");
    }
}


// Get ID
function get_id() {
    if(!ctype_digit($_POST["id"])) {
        exit_json("ID must be integer.");
    }

    if(!empty($_POST["id"])) {
        return $_POST["id"];
    }
    else {
        exit_json("No ID.");
    }
}


// Get rescuer ID
function get_resc_id() {
    if(!ctype_digit($_POST["resc_id"])) {
        exit_json("Rescuer ID must be integer.");
    }

    if(!empty($_POST["resc_id"])) {
        return $_POST["resc_id"];
    }
    else {
        exit_json("No rescuer ID.");
    }
}


// Get ID from database
function get_id_db($injured_table, $conn, $band_id) {
    valid_init($injured_table, $conn, $band_id);

    // Get ID from database
    $result = exec_query($conn, "select id from $injured_table where opaska_id = $band_id and nadawanie = false and aktywna_opaska = false and w_akcji = true", TRUE);
    $id = $result['id'];
    return $id;
}


// Validate wristband enabling
function valid_init($injured_table, $conn, $band_id) {
    $result = exec_query($conn, "select count(opaska_id) as count from $injured_table where w_akcji = true and opaska_id = $band_id", TRUE);
    $count_in_action = $result['count'];

    // Check for duplicate in action wristband entries
    if($count_in_action > 1) {
        exit_json("Duplicate in action entries for wristband with ID $band_id.");
    }

    // Check if entry exist for given wristband ID
    if($count_in_action == 0) {
        exit_json("In action entry for wristband with ID $band_id doesn't exist.");
    }

    $result = exec_query($conn, "select count(opaska_id) as count from $injured_table where nadawanie = true and opaska_id = $band_id", TRUE);
    $count_broadcasting = $result['count'];

    // Check if wristband with given ID is already broadcasting
    if($count_in_action == 1 && $count_broadcasting > 0) {
        exit_json("Wristband with ID $band_id is already broadcasting.");
    }

    $result = exec_query($conn, "select count(opaska_id) as count from $injured_table where aktywna_opaska = true and opaska_id = $band_id", TRUE);
    $count_active = $result['count'];

    // Check if wristband with given ID is already active
    if($count_active > 0) {
        exit_json("Wristband with ID $band_id is already active.");
    }

    $result = exec_query($conn, "select count(opaska_id) as count from $injured_table where opaska_id = $band_id and nadawanie = false and aktywna_opaska = false and w_akcji = true", TRUE);
    $valid = $result['count'];

    // Check if ready to enable
    if($valid != 1) {
        exit_json("No valid entry for wristband with ID $band_id.");
    }
}


// Validate update request
function valid_update($injured_table, $conn, $id) {
    $result = exec_query($conn, "select count(id) as count from $injured_table where id = $id and w_akcji = true", TRUE);
    $valid = $result['count'];

    // Check if ready to update
    if($valid != 1) {
        exit_json("Entry with ID $id unavailable for updating.");
    }
    else {
        echo_json();
    }
}


// Validate login request
function valid_login($resc_table, $conn, $resc_id) {
    $result = exec_query($conn, "select count(ratownik_id) as count from $resc_table where ratownik_id = $resc_id", TRUE);
    $valid = $result['count'];

    // Check if ready to login
    if($valid != 1) {
        exit_json("No valid entry for rescuer ID $resc_id.");
    }
    else {
        echo_json();
    }
}


// Receive json
$_POST = json_decode(file_get_contents("php://input"), true);


// MAIN
if($_POST["secret"] == $api_secret) {
    if(isset($_POST["mode"])) {
        $host = "";
        $user = "";
        $pass = "";
        $db = "myDB";
        $injured_table = "rani";
        $resc_table = "ratownicy";

        // Connect to DB
        $conn = mysqli_connect($host, $user, $pass) or exit_json("Connection failed.");
        mysqli_select_db($conn, $db) or exit_json("Couldn't select database.");

        switch($_POST["mode"]) {
            // Enable wristband with given ID
            case "enable-band":
                $band_id = get_wristband_id();
                $pulse = get_pulse();

                $id = get_id_db($injured_table, $conn, $band_id);
                
                $out = array(
                    "id" => $id
                );

                // Enable wristband
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

            // Disable wristband with given ID
            case "disable-band":
                $id = get_id();

                // Disable wristband
                exec_query($conn, "update $injured_table set nadawanie = false, aktywna_opaska = false where id = $id");
                break;

            // Validate login request
            case "verify-login":
                $resc_id = get_resc_id();

                // Validate
                valid_login($resc_table, $conn, $resc_id);
                break;

            default:
            exit_json("Invalid mode.");
            break;
        }

        mysqli_close($conn);
    }
    else {
        exit_json("Invalid POST parameters.");
    }
}
else {
    exit_json("Permission denied.");
}
