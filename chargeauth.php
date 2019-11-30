<?php 
    
    $authCode = 'NE9WeDM4OUh2dnM0RHpkWm5sZGlSYzNMTjo0a3BHOXVGbzR0cThjUVM5c3I4YVAzOGduQkpTOFVmaDY4Umt0NTAzSzNGcw==';

    $url = 'https://sandbox.payhere.lk/merchant/v1/oauth/token';
    $header = array(
        'Authorization: Basic ' . $authCode
    );
    $body = array(
        'grant_type' => 'client_credentials'
    );

    // create curl resource
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($body));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

    // $output contains the output string
    $response = curl_exec($ch);

    // close curl resource to free up system resources
    curl_close($ch);

    // Write response to FILE
    setChargingAuthStatus($response);

    header('Location: ./');

?>