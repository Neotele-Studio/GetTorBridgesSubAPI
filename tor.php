<?php
header('Content-Type: application/json'); // Устанавливаем тип контента на JSON

if (!isset($_GET['transport'])) {
    echo json_encode(["error" => "Invalid transport type. Use 'obfs4' or 'webtunnel'."]);
    exit;
}

$transport_type = $_GET['transport'];

switch ($transport_type) {
    case 'obfs4':
        $url = 'https://bridges.torproject.org/bridges?transport=obfs4';
        break;
    
    case 'webtunnel':
        $url = 'https://bridges.torproject.org/bridges?transport=webtunnel';
        break;
    
    default:
        echo json_encode(["error" => "Invalid transport type. Use 'obfs4' or 'webtunnel'."]);
        exit;
}

$response = file_get_contents($url);

if ($response === FALSE) {
    echo json_encode(["error" => "Error retrieving data."]);
    exit;
}

// Load the response into a DOMDocument
libxml_use_internal_errors(true);
$dom = new DOMDocument();
$dom->loadHTML($response);
libxml_clear_errors();

// Find the div with id "bridgelines"
$bridgelines = $dom->getElementById('bridgelines');

if ($bridgelines) {
    // Get the text content and strip HTML tags
    $textContent = strip_tags($bridgelines->textContent);

    // Split the content into individual lines
    $lines = explode("\n", trim($textContent));
    
    // Prepare the response in JSON format
    $result = [];
    
    foreach ($lines as $line) {
        if (!empty(trim($line))) {
            // Add each bridge to the result array
            $result[] = ["bridge" => trim($line)];
        }
    }

    // Return JSON response without escaping slashes
    echo json_encode($result, JSON_UNESCAPED_SLASHES);
} else {
    echo json_encode(["error" => "No content found for the specified ID."]);
}
?>
