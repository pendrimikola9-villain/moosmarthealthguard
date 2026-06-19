<?php
function kirimTelegram($pesan) {
    $token = "GANTI_DENGAN_TOKEN_BOT";
    $chatId = "GANTI_DENGAN_CHAT_ID";

    $url = "https://api.telegram.org/bot{$token}/sendMessage";

    $data = [
        "chat_id" => $chatId,
        "text" => $pesan,
    ];

    $options = [
        "http" => [
            "header"  => "Content-Type: application/x-www-form-urlencoded\r\n",
            "method"  => "POST",
            "content" => http_build_query($data),
        ],
    ];

    $context = stream_context_create($options);
    $result = @file_get_contents($url, false, $context);
    return $result;
}
?>