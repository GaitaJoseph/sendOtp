<?php
// Your API credentials
$apikey = "8c5e80c31dc3ac6ff8aff5a5adb43c30"; // Your new API key
$partnerID = "12633"; // Your Partner ID
$shortcode = "TextSMS"; // Your Sender ID (shortcode)

// Log the received data to check if the backend is receiving the data
error_log("Received POST data: " . print_r($_POST, true));

// Get the phone number from the frontend (POST request)
$phoneNumber = isset($_POST['mobile']) ? $_POST['mobile'] : '';

// Log the received phone number
error_log("Received phone number: " . $phoneNumber);

// Basic check to see if the phone number starts with 254 and has a reasonable length
// We expect a number starting with 254 followed by 9 digits (12 characters total)
if (empty($phoneNumber) || !is_numeric($phoneNumber) || strlen($phoneNumber) != 12 || strpos($phoneNumber, '254') !== 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid phone number. Please provide a valid phone number.']);
    exit;
}

// API URL
$url = "https://sms.textsms.co.ke/api/services/sendsms/";

// Prepare the POST data
$data = [
    'apikey' => $apikey, // Your API key
    'partnerID' => $partnerID, // Your Partner ID
    'message' => "Your OTP is 123456",  // Modify the OTP message as needed
    'shortcode' => $shortcode, // Your Sender ID (shortcode)
    'mobile' => $phoneNumber
];

// Set the cURL options for sending the POST request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

// Execute the cURL request
$response = curl_exec($ch);

// Log cURL response
error_log("cURL response: " . $response);

// Check if any cURL error occurred
if(curl_errno($ch)) {
    error_log("cURL Error: " . curl_error($ch));
    echo json_encode(['status' => 'error', 'message' => 'Failed to send OTP. Please try again later.']);
    curl_close($ch);
    exit;
}

// Close cURL session
curl_close($ch);

// Parse the response from the Bulk SMS API
$responseData = json_decode($response, true);

// Log the response from the Bulk SMS API
error_log("API Response: " . print_r($responseData, true));

// Check if the request was successful
if (isset($responseData['responses']) && $responseData['responses'][0]['respose-code'] == 200) {
    // OTP sent successfully
    echo json_encode(['status' => 'success', 'message' => 'OTP sent successfully.']);
} else {
    // Error in sending OTP
    $errorDescription = $responseData['responses'][0]['response-description'] ?? 'Unknown error';
    echo json_encode(['status' => 'error', 'message' => 'Failed to send OTP: ' . $errorDescription]);
}
?>
