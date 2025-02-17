<?php
// =============================
// chatbot.php
// =============================

// API anahtarınızı ve model adınızı burada tanımlayın
$apiKey = 'sk-or-v1-****';  // Buraya kendi geçerli API Anahtarınızı girin
$modelName = 'qwen/qwen2.5-vl-72b-instruct:free';

// Sunucuya POST yöntemiyle geldiğini kontrol ediyoruz
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // JSON formatında gelen veriyi alıyoruz
    $input = json_decode(file_get_contents('php://input'), true);
    $apiEndpoint = 'https://openrouter.ai/api/v1/chat/completions';

    // cURL isteğini hazırlıyoruz
    $ch = curl_init($apiEndpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $apiKey,
        'Content-Type: application/json',
        'HTTP-Referer: https://www.patoloji.com.tr',  // Kendi domain’inize göre düzenleyebilirsiniz
        'X-Title: SiteName'
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'model'    => $modelName,
        'messages' => $input['messages']
    ]));

    // Yanıtı alıp kapatıyoruz
    $response = curl_exec($ch);
    curl_close($ch);

    // Sunucu tarafında JSON döndürüyoruz
    header('Content-Type: application/json');
    echo $response;
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>ChatBot Uygulaması</title>
    <!-- Bootstrap kütüphanesi -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .container {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        #response {
            flex: 1;
            overflow-y: auto;
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .chat-message {
            margin-bottom: 10px;
            padding: 5px;
            border-radius: 5px;
        }
        .chat-message.user {
            background-color: #f0f0f0;
            text-align: right;
        }
        .chat-message.bot {
            background-color: #e0e0e0;
        }
        .loader {
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
            display: none;
        }
        @keyframes spin {
            0% { transform: rotate(0deg);}
            100% { transform: rotate(360deg);}
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mt-3">ChatBot Uygulaması</h2>
        <div id="response"></div>
        <div class="form-group mt-3">
            <input type="text" class="form-control" id="userInput" placeholder="Bir soru sorun..." />
        </div>
        <button class="btn btn-success" onclick="sendMessage()">Gönder</button>
        <div id="loading" class="loader"></div>
    </div>

    <script>
        let conversationHistory = [];

        async function sendMessage() {
            const inputField = document.getElementById('userInput');
            const inputText = inputField.value;
            const responseDiv = document.getElementById('response');
            const loadingDiv = document.getElementById('loading');

            if (!inputText) {
                responseDiv.innerHTML += '<div class="chat-message">Lütfen bir soru girin.</div>';
                return;
            }

            // Kullanıcı mesajı ekrana yazdır
            addMessage(inputText, 'user');
            conversationHistory.push({ role: 'user', content: inputText });

            // Yükleniyor animasyonunu göster
            loadingDiv.style.display = 'block';

            try {
                // Aynı sayfaya (chatbot.php) POST isteği gönderiliyor
                const response = await fetch(window.location.href, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ messages: conversationHistory })
                });

                if (!response.ok) {
                    throw new Error(`HTTP Hatası: ${response.status}`);
                }

                const data = await response.json();
                // OpenRouter yanıtında data.choices[0].message.content içinde cevap geliyor
                const botResponse = data.choices[0].message.content;
                conversationHistory.push({ role: 'assistant', content: botResponse });
                addMessage(botResponse, 'bot');
            } catch (error) {
                console.error('Error:', error);
                addMessage('Bir hata oluştu. Lütfen daha sonra tekrar deneyin.', 'bot');
            } finally {
                // Yükleniyor animasyonunu gizle
                loadingDiv.style.display = 'none';
                // Metin kutusunu temizle
                inputField.value = '';
            }
        }

        function addMessage(message, role) {
            const responseDiv = document.getElementById('response');
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('chat-message', role);

            if (role === 'user') {
                // Kullanıcı mesajını güvenli şekilde textContent olarak ekleyin
                messageDiv.textContent = message;
            } else {
                // Bot yanıtlarında satır sonlarını korumak için \n'leri <br> ile değiştiriyoruz
                messageDiv.innerHTML = message.replace(/\n/g, '<br>');
            }

            responseDiv.appendChild(messageDiv);
            responseDiv.scrollTop = responseDiv.scrollHeight;
        }
    </script>
</body>
</html>
