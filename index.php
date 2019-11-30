<?php

define('FILE_PREAPPROVAL_STATUS', 'preappstatus.txt');
define('FILE_CHARGING_AUTH_STATUS', 'chargeauthstatus.txt');
define('FILE_CHARGING_CHARGE_STATUS', 'chargingcharge.txt');
define('FILE_LOG', 'log.txt');

// MARK: Set methods

function clearLog(){
    unlink(FILE_LOG);
}

function clearAllStates(){
    set_error_handler(function() { /* ignore errors */ });
    unlink(FILE_PREAPPROVAL_STATUS);
    unlink(FILE_CHARGING_AUTH_STATUS);
    unlink(FILE_CHARGING_CHARGE_STATUS);
    restore_error_handler();
}

function logMessage($text){
    file_put_contents(FILE_LOG, '----' . PHP_EOL . date('m/d/Y H:i:s', time()) . $text . PHP_EOL, FILE_APPEND);
}

function setPreapprovalStatus(){
    logMessage( ' - Setting preapproval status - message: '  . $_POST['status_message'] );
    file_put_contents(FILE_PREAPPROVAL_STATUS, json_encode($_POST, JSON_PRETTY_PRINT));
}

function setChargingAuthStatus($jsonResponse){
    $arr = json_decode($jsonResponse, true);
    logMessage(' - Setting payment/oauth status - access token: '  . $arr['access_token'] );
    file_put_contents(FILE_CHARGING_AUTH_STATUS, json_encode($arr, JSON_PRETTY_PRINT));
}

function setChargingChargeStatus($jsonResponse){
    $arr = json_decode($jsonResponse, true);
    logMessage(' - Setting payment/charege status - message: '  . $arr['msg'] );
    file_put_contents(FILE_CHARGING_CHARGE_STATUS, json_encode($arr, JSON_PRETTY_PRINT));
}

// MARK: Get Methods

function getPreapprovalStatus(){
    if (file_exists(FILE_PREAPPROVAL_STATUS)){
        $text = file_get_contents(FILE_PREAPPROVAL_STATUS);
        $arr = json_decode($text, true);
        $ret = 'status_code:' . $arr['status_code'] . PHP_EOL . 
            'customer_token:' . $arr['customer_token'] . PHP_EOL .
            'payment_id:' . $arr['payment_id'];
        return $ret;
    }
    return 'no contents found';
}

function getChargingAuthStatus(){
    if (file_exists(FILE_CHARGING_AUTH_STATUS)){
        return file_get_contents(FILE_CHARGING_AUTH_STATUS);
    }
    return 'no contents found';
}

function getChargingChargeStatus(){
    if (file_exists(FILE_CHARGING_CHARGE_STATUS)){
        return file_get_contents(FILE_CHARGING_CHARGE_STATUS);
    }
    return 'no contents found';
}

// MARK: Log methods

function getLog(){
    if (file_exists(FILE_LOG)){
        return file_get_contents(FILE_LOG);
    }
    return 'no contents found';
}

// MARK: Routing based on GET parameters

if (isset($_GET['method'])){
    if ($_GET['method'] == 'notifypreapprove'){
        setPreapprovalStatus();
        die();
    }
    else if($_GET['method'] == 'chargeauth'){
        include './chargeauth.php'; // Redirects to Location: ./
    }
    else if($_GET['method'] == 'chargingcharge'){
        include './chargecharge.php'; // Redirects to Location: ./
    }
    else if ($_GET['method'] == 'clearstates'){
        clearAllStates();
        return header("Location: ./");
    }
    else if ($_GET['method'] == 'clearlog'){
        clearLog();
        return header("Location: ./");
    }
}

// MARK: interface.html render

$content = file_get_contents('./interface.html');

// Infix Status - preapproval
$content = str_replace('{{status_preapproval}}', getPreapprovalStatus(), $content);

// Infix Status - charging - auth
$content = str_replace('{{status_charge_auth}}', getChargingAuthStatus(), $content);

// Infix Status - charging - charge
$content = str_replace('{{status_charge_charge}}', getChargingChargeStatus(), $content);

// Infix Log
$content = str_replace('{{log}}', getLog(), $content);

echo $content;

?>