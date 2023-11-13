<?php

// Include the necessary libraries
include 'vendor/autoload.php';

// Set up the API endpoint URL
$apiEndpoint = 'https://germinal-flowise.hf.space/api/v1/prediction/fe4c8a18-af3c-4f81-9af2-575a9ba1cde6';

// Set up the authorization token for the API request
$authorizationToken = 'FVXruV8Z1cTqSMqqS7n43rcpMKCTMnyN2ZOTtYXW+6w=';

// Function to send a message to Flowise and receive the processed message back
function sendMessageToFlowise($message) {
  // Convert the message to JSON format
  $data = json_encode([
    'question' => $message,
    'overrideConfig' => [
      'url' => 'example',
      'description' => 'example',
      'headers' => 'example',
      'url' => 'example',
    ],
  ]);

  // Send the API request to Flowise
  $ch = curl_init($apiEndpoint);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $authorizationToken,
    'Content-Type: application/json',
  ]);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  $response = curl_exec($ch);
  curl_close($ch);

  // Decode the JSON response from Flowise
  $result = json_decode($response, true);

  // Return the processed message from Flowise
  return $result['predictions'][0]['text'];
}

// Modify the script to call sendMessageToFlowise() for each incoming message
$data = json_decode(file_get_contents('php://input'), true);

if (!empty($data['query']['message'])) {
  $message = $data['query']['message'];
  $processedMessage = sendMessageToFlowise($message);
  echo json_encode(['replies' => [
    ['message' => $processedMessage],
  ]]);
} else {
  http_response_code(400);
  echo json_encode(['error' => 'Invalid request']);
}
