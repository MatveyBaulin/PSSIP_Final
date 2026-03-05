<?php
// subscribe.php - Подписка на рассылку

require_once 'config.php';
require_once 'sendmail.php';

session_start();

// Подключение к БД (если нужно хранить подписчиков)
try {
    $pdo = new PDO("mysql:host=localhost;dbname=newsletter_db", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Создание таблицы если не существует
    $pdo->exec("CREATE TABLE IF NOT EXISTS subscribers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) UNIQUE NOT NULL,
        name VARCHAR(255),
        subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        is_active BOOLEAN DEFAULT TRUE
    )");
} catch(PDOException $e) {
    // Если нет БД, просто продолжаем без сохранения
}

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $name = htmlspecialchars($_POST['name'] ?? '');
    
    if (!$email) {
        $response['message'] = 'Введите корректный email';
    } else {
        try {
            // Сохранение в БД
            if (isset($pdo)) {
                $stmt = $pdo->prepare("INSERT INTO subscribers (email, name) VALUES (?, ?)");
                $stmt->execute([$email, $name]);
            }
            
            // Отправка приветственного письма
            $mailer = new MailSender();
            $subject = "Подтверждение подписки на рассылку";
            
            $message = "<h3>Здравствуйте" . ($name ? " , $name" : "") . "!</h3>
                       <p>Вы успешно подписались на нашу рассылку.</p>
                       <p>Теперь вы будете получать самые свежие новости и предложения.</p>
                       <p>Если вы не подписывались на рассылку, просто проигнорируйте это письмо.</p>";
            
            $result = $mailer->send($email, $subject, $message);
            
            if ($result['success']) {
                // Отправка уведомления администратору
                $adminMsg = "<h3>Новый подписчик!</h3>
                            <p><strong>Email:</strong> $email</p>
                            <p><strong>Имя:</strong> " . ($name ?: 'Не указано') . "</p>
                            <p><strong>Дата:</strong> " . date('d.m.Y H:i:s') . "</p>";
                
                $mailer->send(ADMIN_EMAIL, "Новый подписчик на сайте", $adminMsg);
                
                $response = [
                    'success' => true,
                    'message' => 'Спасибо за подписку! Проверьте вашу почту.'
                ];
            } else {
                $response['message'] = 'Ошибка при отправке письма';
            }
            
        } catch (Exception $e) {
            if (DEBUG_MODE) {
                $response['message'] = 'Ошибка: ' . $e->getMessage();
            } else {
                $response['message'] = 'Произошла ошибка при подписке';
            }
        }
    }
    
    // Для AJAX запросов
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}

// Если не AJAX, показываем страницу
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Подписка на рассылку</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            max-width: 500px;
            width: 90%;
        }
        h1 { text-align: center; color: #333; margin-bottom: 30px; }
        .form-group { margin-bottom: 20px; }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
        }
        input {
            width: 100%;
            padding: 10px;
            border: 2px solid #e0e0e0;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        input:focus {
            outline: none;
            border-color: #667eea;
        }
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
        button:hover {
            background: #764ba2;
        }
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
    </style>
</head>
<body>
    <div class="container">
        <h1>📧 Подписка на новости</h1>
        
        <div id="message" class="message"></div>
        
        <form id="subscribeForm" method="POST" action="subscribe.php">
            <div class="form-group">
                <label for="name">Ваше имя (необязательно)</label>
                <input type="text" id="name" name="name" placeholder="Иван Иванов">
            </div>
            
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" required 
                       placeholder="ivan@example.com">
            </div>
            
            <button type="submit">Подписаться на рассылку</button>
        </form>
        
        <p style="text-align: center; margin-top: 20px; color: #666;">
            <small>Мы не рассылаем спам. Отписаться можно в любой момент.</small>
        </p>
    </div>
    
    <script>
        document.getElementById('subscribeForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const messageDiv = document.getElementById('message');
            
            fetch('subscribe.php', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                messageDiv.className = 'message ' + (data.success ? 'success' : 'error');
                messageDiv.textContent = data.message;
                messageDiv.style.display = 'block';
                
                if (data.success) {
                    document.getElementById('subscribeForm').reset();
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