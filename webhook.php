<?php
$access_token = 'YOUR_ACCESS_TOKEN'; // เปลี่ยนให้เป็น Access Token ของคุณ

// เช็คว่ามีการเข้าถึงจาก Facebook หรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($_GET['hub_mode'] === 'subscribe' && $_GET['hub_verify_token'] === 'YOUR_VERIFY_TOKEN') {
        echo $_GET['hub_challenge'];
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับข้อมูลที่ส่งจาก Messenger
    $input = json_decode(file_get_contents('php://input'), true);
    
    // ตรวจสอบว่ามีข้อมูลข้อความที่ส่งมาหรือไม่
    if (isset($input['entry'][0]['messaging'][0])) {
        $message = $input['entry'][0]['messaging'][0];

        // ตรวจสอบว่ามีข้อความจากผู้ใช้หรือไม่
        if (isset($message['message']['text'])) {
            $sender_id = $message['sender']['id'];
            $text = $message['message']['text'];

            // ตอบกลับข้อความ
            sendMessage($sender_id, "คุณส่งข้อความว่า: " . $text);
        }
    }
}

// ฟังก์ชันส่งข้อความกลับไปยังผู้ใช้
function sendMessage($recipientId, $messageText) {
    global $access_token;
    
    $data = [
        'recipient' => ['id' => $recipientId],
        'message' => ['text' => $messageText]
    ];

    $url = 'https://graph.facebook.com/v12.0/me/messages?access_token=' . $access_token;
    $options = [
        'http' => [
            'header'  => "Content-type: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode($data),
        ],
    ];
    
    $context  = stream_context_create($options);
    file_get_contents($url, false, $context);
}
?>
