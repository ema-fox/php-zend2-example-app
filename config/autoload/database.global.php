<?php
if (!empty($_SERVER['HTTP_HOST']) && isset($_ENV['CRED_FILE'])) {
    // read the credentials file
    $string = file_get_contents($_ENV['CRED_FILE'], false);
    if ($string == false) {
        throw new Exception('Could not read credentials file');
    }
    // the file contains a JSON string, decode it and return an associative array
    $creds = json_decode($string, true);

    $error = json_last_error();
    if ($error != JSON_ERROR_NONE){
        $json_errors = array(
            JSON_ERROR_DEPTH => 'The maximum stack depth has been exceeded',
            JSON_ERROR_STATE_MISMATCH => 'Invalid or malformed JSON',
            JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
            JSON_ERROR_SYNTAX => 'Syntax error',
            JSON_ERROR_UTF8 => 'Malformed UTF-8 characters, possibly incorrectly encoded'
        );
        throw new Exception(sprintf('A json error occured while reading the credentials file: %s', $json_errors[$error]));
    }

    if (!array_key_exists('MYSQLS', $creds)){
        throw new Exception('No MySQL credentials found. Please make sure you have added the mysqls addon.');
    }

    $database_host = $creds["MYSQLS"]["MYSQLS_HOSTNAME"];
    $database_name = $creds["MYSQLS"]["MYSQLS_DATABASE"];
    $database_user = $creds["MYSQLS"]["MYSQLS_USERNAME"];
    $database_password = $creds["MYSQLS"]["MYSQLS_PASSWORD"];
} else {
    $database_host = 'localhost';
    $database_name = '<local_zf2_database_name>';
    $database_user = '<local_zf2_database_user>';
    $database_password = '<local_zf2_database_password>';
}

return array(
    'session_db' => array(
        'driver'         => 'Pdo',
        'dsn'            => sprintf('mysql:dbname=%s;host=%s', $database_name, $database_host),
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
        'username' => $database_user,
        'password' => $database_password,
    ),
);