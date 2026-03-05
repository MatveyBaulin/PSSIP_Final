<?php
// send.php - обработчик формы
session_start();
require_once 'config.php';

// Функция для очистки данных
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Функция отправки письма
function send_mail($to, $subject, $message, $from) {
    // Заголовки письма
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "From: " . $from . "\r\n";
    $headers .= "Reply-To: " . $from . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    // Отправка
    return mail($to, $subject, $message, $headers);
}

// Проверяем, что форма отправлена
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Получаем и очищаем данные
    $name = clean_input($_POST['name'] ?? '');
    $email = clean_input($_POST['email'] ?? '');
    $subject = clean_input($_POST['subject'] ?? 'Общий вопрос');
    $message_text = clean_input($_POST['message_text'] ?? '');
    
    // Сохраняем данные в сессию на случай ошибки
    $_SESSION['form_data'] = [
        'name' => $name,
        'email' => $email,
        'message_text' => $message_text
    ];
    
    // Валидация
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Укажите ваше имя";
    }
    
    if (empty($email)) {
        $errors[] = "Укажите email";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Укажите корректный email";
    }
    
    if (empty($message_text)) {
        $errors[] = "Напишите сообщение";
    }
    
    // Если есть ошибки
    if (!empty($errors)) {
        $_SESSION['feedback_message'] = implode('<br>', $errors);
        $_SESSION['feedback_type'] = 'error';
        header("Location: index.php");
        exit;
    }
    
    // Формируем HTML-письмо для администратора
    $admin_message = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #007bff; color: white; padding: 20px; text-align: center; }
            .content { padding: 20px; background: #f9f9f9; }
            .field { margin-bottom: 15px; }
            .label { font-weight: bold; color: #555; }
            .value { margin-top: 5px; padding: 10px; background: white; border-radius: 5px; }
            .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>Новое сообщение с сайта</h2>
            </div>
            <div class='content'>
                <div class='field'>
                    <div class='label'>Имя:</div>
                    <div class='value'>" . $name . "</div>
                </div>
                
                <div class='field'>
                    <div class='label'>Email:</div>
                    <div class='value'>" . $email . "</div>
                </div>
                
                <div class='field'>
                    <div class='label'>Тема:</div>
                    <div class='value'>" . $subject . "</div>
                </div>
                
                <div class='field'>
                    <div class='label'>Сообщение:</div>
                    <div class='value'>" . nl2br($message_text) . "</div>
                </div>
                
                <div class='field'>
                    <div class='label'>Дата:</div>
                    <div class='value'>" . date('d.m.Y H:i:s') . "</div>
                </div>
                
                <div class='field'>
                    <div class='label'>IP адрес:</div>
                    <div class='value'>" . $_SERVER['REMOTE_ADDR'] . "</div>
                </div>
            </div>
            <div class='footer'>
                <p>Сообщение отправлено с формы обратной связи</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // Формируем письмо для пользователя (подтверждение)
    $user_message = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #28a745; color: white; padding: 20px; text-align: center; }
            .content { padding: 20px; background: #f9f9f9; }
            .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>Спасибо за обращение!</h2>
            </div>
            <div class='content'>
                <p>Здравствуйте, <strong>" . $name . "</strong>!</p>
                <p>Мы получили ваше сообщение и ответим вам в ближайшее время.</p>
                
                <p><strong>Тема:</strong> " . $subject . "</p>
                <p><strong>Ваше сообщение:</strong></p>
                <p>" . nl2br($message_text) . "</p>
                
                <p>С уважением,<br>команда " . SITE_NAME . "</p>
            </div>
            <div class='footer'>
                <p>Это автоматическое сообщение, пожалуйста, не отвечайте на него.</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // Отправляем письмо администратору
    $admin_subject = "=?utf-8?B?" . base64_encode("Обратная связь: $subject") . "?=";
    $admin_sent = send_mail(TO_EMAIL, $admin_subject, $admin_message, $email);
    
    // Отправляем письмо пользователю
    $user_subject = "=?utf-8?B?" . base64_encode("Ваше сообщение получено") . "?=";
    $user_sent = send_mail($email, $user_subject, $user_message, FROM_EMAIL);
    
    // Проверяем результат
    if ($admin_sent && $user_sent) {
        $_SESSION['feedback_message'] = "Сообщение успешно отправлено! Проверьте вашу почту.";
        $_SESSION['feedback_type'] = 'success';
        // Очищаем сохраненные данные
        unset($_SESSION['form_data']);
    } elseif ($admin_sent) {
        $_SESSION['feedback_message'] = "Сообщение отправлено, но не удалось отправить подтверждение на ваш email.";
        $_SESSION['feedback_type'] = 'warning';
    } else {
        if (DEBUG_MODE) {
            $_SESSION['feedback_message'] = "Ошибка при отправке. Проверьте настройки почты в XAMPP.";
        } else {
            $_SESSION['feedback_message'] = "Произошла ошибка. Попробуйте позже.";
        }
        $_SESSION['feedback_type'] = 'error';
    }
    
    // Перенаправляем обратно на форму
    header("Location: index.php");
    exit;
    
} else {
    // Если кто-то зашел напрямую
    header("Location: index.php");
    exit;
}
?>