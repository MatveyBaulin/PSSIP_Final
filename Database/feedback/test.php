<?php
echo "<h2>📧 Финальная диагностика</h2>";

echo "<h3>Настройки:</h3>";
echo "sendmail_path: <strong>" . ini_get('sendmail_path') . "</strong><br>";

// Проверка sendmail.ini
$ini_content = file_get_contents('C:\xampp\sendmail\sendmail.ini');
echo "<h3>Содержимое sendmail.ini:</h3>";
echo "<pre>" . htmlspecialchars($ini_content) . "</pre>";

// Проверка, видит ли PHP изменения
echo "<h3>Тест отправки:</h3>";
$to = "baulinm2006@gmail.com";
$subject = "=?utf-8?B?" . base64_encode("Тест после исправления") . "?=";
$message = "Если вы видите это письмо - sendmail работает!";
$headers = "From: test@localhost\r\n";
$headers .= "Content-type: text/plain; charset=utf-8\r\n";

if (@mail($to, $subject, $message, $headers)) {
    echo "<span style='color:green'>✅ Письмо отправлено! Проверьте почту.</span>";
} else {
    echo "<span style='color:red'>❌ Ошибка отправки</span>";
    
    $error = error_get_last();
    if ($error) {
        echo "<br><br><b>Ошибка:</b> " . $error['message'];
    }
}

// Проверка лога ошибок sendmail
$log = 'C:\xampp\sendmail\error.log';
if (file_exists($log)) {
    echo "<h3>Лог ошибок sendmail:</h3>";
    echo "<pre>" . file_get_contents($log) . "</pre>";
}
?>