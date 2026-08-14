<?php
/**
 * Test Script for Kegiatan API
 * Run this to test if API is working correctly
 */

// Configuration
$baseUrl = 'http://127.0.0.1:8000/api';
$token = 'YOUR_AUTH_TOKEN_HERE'; // Replace with actual token

echo "=== Testing Kegiatan API ===\n\n";

// Test 1: Get Kegiatan Bulanan
echo "Test 1: GET /api/laporan-kinerja?month=2024-08\n";
$response = makeRequest("GET", "$baseUrl/laporan-kinerja?month=2024-08", $token);
printResponse($response);

// Test 2: Get Rekap
echo "\nTest 2: GET /api/laporan-kinerja/rekap?month=2024-08\n";
$response = makeRequest("GET", "$baseUrl/laporan-kinerja/rekap?month=2024-08", $token);
printResponse($response);

// Test 3: Store Kegiatan Baru
echo "\nTest 3: POST /api/laporan-kinerja/harian\n";
$data = [
    'tanggal' => date('Y-m-d'),
    'items' => [
        ['k' => 'Test Kegiatan API', 'v' => 1, 's' => 'Kegiatan'],
    ]
];
$response = makeRequest("POST", "$baseUrl/laporan-kinerja/harian", $token, $data);
printResponse($response);

// Test 4: Get updated data
echo "\nTest 4: GET /api/laporan-kinerja?month=" . date('Y-m') . "\n";
$response = makeRequest("GET", "$baseUrl/laporan-kinerja?month=" . date('Y-m'), $token);
printResponse($response);

echo "\n=== Tests Completed ===\n";

function makeRequest($method, $url, $token, $data = null) {
    $ch = curl_init();

    $headers = [
        'Accept: application/json',
        'Content-Type: application/json',
        "Authorization: Bearer $token",
    ];

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    } elseif ($method === 'PUT') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    } elseif ($method === 'DELETE') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        return ['error' => $error, 'http_code' => 0];
    }

    return [
        'http_code' => $httpCode,
        'response' => json_decode($response, true),
    ];
}

function printResponse($result) {
    if (isset($result['error'])) {
        echo "ERROR: {$result['error']}\n";
        return;
    }

    echo "HTTP Code: {$result['http_code']}\n";
    echo "Response:\n";
    print_r(json_encode($result['response'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo "\n";
}
