<?php

// Data payload
$data = [
    "model" => "gpt-5", // or "gpt-4o" / "gpt-3.5-turbo" depending on your access
    "messages" => [
        ["role" => "user", "content" => "List all the cities that the 2026 FIFA will take place in"]
    ],
    "temperature" => 0.7
];

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

