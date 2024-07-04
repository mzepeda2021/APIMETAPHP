<?php

$apikey = '';

$data =[
    'model' => 'gpt-3.5-turbo-instruct',
    'prompt' => 'Describe a Paul McCartney',
    'temperature' => 0.7,
    'max_tokens' => 64,
    'n' => 1,
    'stop' => ['\n']
];

   $ch = curl_init('https://api.openai.com/v1/chat/completions');
   curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_HTTPHEADER, array(
       'Content-Type: application/json',
       'Authorization: Bearer ' .$apikey 
   ));

   $response = curl_exec($ch);
   $responseArr = json_decode($response, true);

   print($response);
?>
