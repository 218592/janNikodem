<?php

$api_secret = '';

// Execute MySQL query
function exec_query($conn, $query) {
    mysqli_query($conn, $query) or exit('MySQL query failed: '.mysqli_error($conn));
}

// Get wristband ID
function get_wristband_id() {
    if(!ctype_digit($_POST['band_id'])) {
        exit('Wristband ID must be integer.');
    }
    
    if (!empty($_POST['band_id'])) {
        return $_POST['band_id']; 
    }
    else {
        exit('No wristband ID.');
    }
}

// Get pulse
function get_pulse() {
    if(!ctype_digit($_POST['pulse'])) {
        exit('Pulse must be integer.');
    }

    if (!empty($_POST['pulse'])) {
        return $_POST['pulse'];
    }
    else {
        exit('No pulse.');
    }
}

$_POST = json_decode(file_get_contents('php://input'), true);

// Very basic auth
if ($_POST['secret'] == $api_secret) {
    if(isset($_POST['mode'])) {
        $host='';
        $user='';
        $pass='';
        $db='myDB';
        $injured_table='rani';

        // Connect to DB
        $conn = mysqli_connect($host, $user, $pass) or exit('Connection failed.');
        mysqli_select_db($conn, $db) or exit('Couldn\'t select database.');

        switch ($_POST['mode']) {
            // Insert wristband data with given ID
            case 'insert-band':
                $band_id = get_wristband_id();
                $pulse = get_pulse();

                // Insert data
                exec_query($conn, "insert into $injured_table (opaska_id, tetno, broadcast) values ($band_id, $pulse, true)");
                break;

            // Update wristband data with given ID
            case 'update-band':
                $band_id = get_wristband_id();
                $pulse = get_pulse();

                // Update data
                exec_query($conn, "update $injured_table set tetno = $pulse where opaska_id = $band_id");
                break;

            // Remove wristband with given ID
            case 'remove-band':
                $band_id = get_wristband_id();

                // Remove wristband
                exec_query($conn, "delete from $injured_table where opaska_id = $band_id");
                break;

            default:
            exit('Invalid mode.');
            break;
        }

        mysqli_close($conn);
    }
    else {
        exit('Invalid POST parameters.');
    }
}
else {
    exit('Permission denied.');
}