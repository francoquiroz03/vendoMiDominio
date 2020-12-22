<?php

$secretGoogle = "";
$keySendgrid = "";

$emailTo = "";
$emailFrom = "";

$message = "";

$data = array(
    'secret' => $secretGoogle,
    'response' => $_POST["g-recaptcha-response"]
);

$email = $_POST["email"];

$verify = curl_init();
curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
curl_setopt($verify, CURLOPT_POST, true);
curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($verify);
$obj = json_decode($response);

if( $obj->success ){

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.sendgrid.com/v3/mail/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\"personalizations\":[{\"to\":[{\"email\":\"$emailTo\",\"name\":\"$emailTo\"}],\"subject\":\"SUBJECT\"}],\"from\":{\"email\":\"$emailFrom\",\"name\":\"contact\"},\"content\":[{\"type\":\"text/plain\",\"value\":\"$message\"}]}",
        CURLOPT_HTTPHEADER => array(
            "authorization: Bearer $keySendgrid",
            "content-type: application/json"
        ),
        ));
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    if ($err) {
        header('Location: ../?success=error');
    }

    header('Location: ../?success=true');
}else{
    header('Location: ../?success=error');
}

?>