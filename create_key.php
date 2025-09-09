<?php
// Simple KeyMint API example: Create a license key
// Usage: Set KEYMINT_ACCESS_TOKEN and KEYMINT_PRODUCT_ID as environment variables.

$accessToken = getenv('KEYMINT_ACCESS_TOKEN');
$productId = getenv('KEYMINT_PRODUCT_ID');
$baseUrl = 'https://api.keymint.dev';

if (!$accessToken || !$productId) {
    fwrite(STDERR, "Error: Please set KEYMINT_ACCESS_TOKEN and KEYMINT_PRODUCT_ID environment variables.\n");
    exit(1);
}

// Example: Creating a key for an existing customer
$data = [
    'productId' => $productId,
    // 'customerId' => 'existing_customer_id', // Uncomment and set if you have a customer
    // 'maxActivations' => 3, // Optional: set max activations
    // 'expiryDate' => '2025-12-31T23:59:59Z', // Optional: ISO 8601 format
];

// Example: Creating a key and a new customer at the same time
// $data['newCustomer'] = [
//     'name' => 'Jane Doe',
//     'email' => 'jane@example.com',
// ];

$ch = curl_init("$baseUrl/key");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $accessToken",
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    $result = json_decode($response, true);
    echo "Success! Key created:\n";
    echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
} else {
    $error = json_decode($response, true);
    echo "Error ($httpCode): ";
    if (isset($error['message'])) {
        echo $error['message'] . "\n";
    } else {
        echo $response . "\n";
    }
    exit(1);
}
