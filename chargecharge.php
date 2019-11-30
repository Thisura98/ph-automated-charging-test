<?php

    $preapprovalStatus = json_decode(file_get_contents(FILE_PREAPPROVAL_STATUS), true);
    $chargeStatus = json_decode(getChargingAuthStatus(), true);

    $orderId = $preapprovalStatus['order_id'];
    $customerToken = $preapprovalStatus['customer_token'];
    $authToken = $chargeStatus['access_token'];

    $url = 'https://sandbox.payhere.lk/merchant/v1/payment/charge';
    $header = array(
        'Authorization: Bearer ' . $authToken,
        'Content-Type: application/json'
    );
    $body = array(
        "order_id" => $orderId,
        "items" => 'SLT Bill Payment',
        "currency" => 'LKR',
        "amount" => '1000.00',
        "customer_token" => $customerToken
    );

    
    // create curl resource
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

    // $output contains the output string
    $response = curl_exec($ch);

    // close curl resource to free up system resources
    curl_close($ch);

    // Write response to FILE
    setChargingChargeStatus($response);

    header('Location: ./');

?>