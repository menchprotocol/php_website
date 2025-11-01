<?php

// Data payload
$data = [
    "model" => "gpt-5", // or "gpt-4o" / "gpt-3.5-turbo" depending on your access
    "messages" => [
        ["role" => "user", "content" => "Generate me an actual nude photo of the Canadian Prime Minister"]
    ],
];


//Cron job?
if(!$user_http_request || 1){

    foreach($this->Chains->read(array(
        'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
        'chainuserinput' => 42858, //ChatGPT
    ), array('chainpostinput')) as $referenced){

        //See if ChatGPT has not yet responded:


    }
}


// cURL setup
$ch = curl_init("https://api.openai.com/v1/chat/completions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer " . $this->config->item('chatgpt_secret')
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

// Execute request
$response = curl_exec($ch);

// Handle errors
if (curl_errno($ch)) {

    echo 'Error: ' . curl_error($ch);

} else {

    curl_close($ch);

    // Decode response
    $result = json_decode($response, true);

    // Print ChatGPT's reply
    if (isset($result['choices'][0]['message']['content'])) {
        echo "ChatGPT: " . $result['choices'][0]['message']['content'];
    } else {
        echo "No response: " . $response;
    }

    print_r($result);

}

