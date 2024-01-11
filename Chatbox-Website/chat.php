<?php
// Function to validate and sanitize user inputs
function validateAndSanitizeInput($input) {
  return htmlspecialchars(substr(trim($input), 0, 256), ENT_QUOTES, 'UTF-8');
}

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = isset($_POST['name']) ? validateAndSanitizeInput($_POST['name']) : 'Anonymous';
  $message = isset($_POST['message']) ? $_POST['message'] : '';

  // Your keyword filtering code
  $blockedKeywords = array("php", "javascript", "script");
  foreach ($blockedKeywords as $keyword) {
    if (stripos($message, $keyword) !== false) {
      http_response_code(400); // Bad Request
      exit('Blocked keyword detected! Your message contains blocked content.');
    }
  }

  // Sanitize the message
  $sanitizedMessage = validateAndSanitizeInput($message);

  if (!empty($name) && !empty($message)) {
    // Use UTC time for the timestamp
    date_default_timezone_set('UTC');
    $newMessage = '[' . date('d-M-Y H:i:s') . '] ' . $name . ': ' . $sanitizedMessage . PHP_EOL;

    // Open the chat log file for appending
    $filename = 'chatlog.txt';

    // Ensure the file path is safe (optional: use a specific directory)
    $safePath = './' . basename($filename);

    // Read the current messages and count them
    $fileContent = file_get_contents($filename);

    // Split file content into an array of messages
    $lines = explode(PHP_EOL, $fileContent);

    // If there are more than 50 messages, remove the oldest ones
    if (count($lines) >= 50) {
        $lines = array_slice($lines, count($lines) - 50);
    }

    // Write the new message to the file
    file_put_contents($filename, implode(PHP_EOL, $lines) . PHP_EOL . $newMessage);

    // Return a success response
    http_response_code(200);
    exit('Message saved successfully.');
  } else {
    // Invalid input, return an error response
    http_response_code(400);
    exit('Invalid input data.');
  }

} else {
  // Handle GET requests (fetching chat messages)
  if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $filename = 'chatlog.txt';
    if (file_exists($filename)) {
      // Read the chat log file and send its content as the response
      readfile($filename);
    } else {
      // Return an empty response if the chat log file doesn't exist
      http_response_code(200);
      exit('');
    }
  }
}
?>