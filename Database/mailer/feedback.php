<?php
// feedback.php - Форма обратной связи

require_once 'config.php';
require_once 'sendmail.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars(trim($_POST['name'] ?? ''));
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $phone = htmlspecialchars(trim($_POST['phone'] ?? ''));
    $subject = htmlspecialchars(trim($_POST['subject'] ?? 'Обратная связь'));
    $message = htmlspecialchars(trim($_POST['message'] ?? ''));
    
    // Валидация
    $errors = [];
    if (empty($name)) $errors[] = 'Укажите имя';
    if (!$email) $errors[] = 'Укажите корректный email';
    if (empty($message)) $errors[] = 'Напишите сообщение';
    
    if (empty($errors)) {
        // Отправка письма администратору
        $mailer = new MailSender();
        
        $content = "<h3>Новое сообщение с сайта</h3>
                   <p><strong>Имя:</strong> $name</p>
                   <p><strong>Email:</strong> $email</p>
                   <p><strong>Телефон:</strong> " . ($phone ?: 'Не указан') . "</p>
                   <p><strong>Тема:</strong> $subject</p>
                   <p><strong>Сообщение:</strong></p>
                   <p>" . nl2br($message) . "</p>
                   <p><strong>Дата:</strong> " . date('d.m.Y H:i:s') . "</p>
                   <p><strong>IP:</strong> " . $_SERVER['REMOTE_ADDR'] . "</p>";
        
        $result = $mailer->send(ADMIN_EMAIL, "Обратная связь: $subject", $content, $email);
        
        if ($result['success']) {
            // Отправка подтверждения пользователю
            $userContent = "<h3>Здравствуйте, $name!</h3>
                           <p>Спасибо за ваше сообщение. Мы ответим вам в ближайшее время.</p>
                           <p><strong>Ваше сообщение:</strong></p>
                           <p>" . nl2br($message) . "</p>";
            
            $mailer->send($email, "Ваше сообщение получено", $userContent);
            
            $response = [
                'success' => true,
                'message' => 'Сообщение отправлено! Мы ответим вам в ближайшее время.'
            ];
        } else {
            $response['message'] = 'Ошибка при отправке сообщения';
        }
    } else {
        $response['message'] = implode('<br>', $errors);
    }
    
    // AJAX ответ
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Обратная связь</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        h1 { text-align: center; color: #333; margin-bottom: 30px; }
        .form-group { margin-bottom: 20px; }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
        }
        input, textarea, select {
            width: 100%;
            padding: 10px;
            border: 2px solid #e0e0e0;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
            font-family: inherit;
        }
        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: #667eea;
        }
        textarea { min-height: 120px; resize: vertical; }
        button {
            width: 100%;
            padding: 12px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover { background: #764ba2; }
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            display: none;
        }
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        @media (max-width: 500px) {
            .row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📨 Обратная связь</h1>
        
        <div id="message" class="message"></div>
        
        <form id="feedbackForm" method="POST" action="feedback.php">
            <div class="row">
                <div class="form-group">
                    <label for="name">Имя *</label>
                    <input type="text" id="name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="phone">Телефон</label>
                <input type="tel" id="phone" name="phone" placeholder="+7 (999) 123-45-67">
            </div>
            
            <div class="form-group">
                <label for="subject">Тема</label>
                <select id="subject" name="subject">
                    <option value="Общий вопрос">Общий вопрос</option>
                    <option value="Техническая поддержка">Техническая поддержка</option>
                    <option value="Сотрудничество">Сотрудничество</option>
                    <option value="Жалоба">Жалоба</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="message">Сообщение *</label>
                <textarea id="message" name="message" required></textarea>
            </div>
            
            <button type="submit">Отправить сообщение</button>
        </form>
    </div>
    
    <script>
        document.getElementById('feedbackForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const messageDiv = document.getElementById('message');
            
            fetch('feedback.php', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                messageDiv.className = 'message ' + (data.success ? 'success' : 'error');
                messageDiv.innerHTML = data.message;
                messageDiv.style.display = 'block';
                
                if (data.success) {
                    document.getElementById('feedbackForm').reset();
                    setTimeout(() => {
                        messageDiv.style.display = 'none';
                    }, 5000);
                }
            })
            .catch(error => {
                messageDiv.className = 'message error';
                messageDiv.textContent = 'Произошла ошибка';
                messageDiv.style.display = 'block';
            });
        });
    </script>
</body>
</html>