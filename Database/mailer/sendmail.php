<?php
// sendmail.php - Класс для отправки писем

class MailSender {
    private $to;
    private $from;
    private $subject;
    private $message;
    private $headers;
    
    public function __construct() {
        $this->from = "noreply@" . $_SERVER['HTTP_HOST'];
    }
    
    /**
     * Отправка письма
     */
    public function send($to, $subject, $message, $from = null) {
        $this->to = $to;
        $this->subject = $subject;
        $this->message = $this->buildMessage($message);
        
        // Заголовки
        $this->headers = "MIME-Version: 1.0\r\n";
        $this->headers .= "Content-type: text/html; charset=utf-8\r\n";
        $this->headers .= "From: " . ($from ?? $this->from) . "\r\n";
        $this->headers .= "Reply-To: " . ($from ?? $this->from) . "\r\n";
        $this->headers .= "X-Mailer: PHP/" . phpversion();
        
        // Отправка
        if (mail($this->to, $this->subject, $this->message, $this->headers)) {
            return ['success' => true, 'message' => 'Письмо отправлено'];
        } else {
            return ['success' => false, 'message' => 'Ошибка отправки'];
        }
    }
    
    /**
     * Формирование HTML письма
     */
    private function buildMessage($content) {
        $html = '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #007bff; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f9f9f9; }
                .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h2>' . SITE_NAME . '</h2>
                </div>
                <div class="content">
                    ' . $content . '
                </div>
                <div class="footer">
                    <p>© ' . date('Y') . ' ' . SITE_NAME . '. Все права защищены.</p>
                </div>
            </div>
        </body>
        </html>';
        
        return $html;
    }
    
    /**
     * Отправка массовой рассылки
     */
    public function sendBulk($recipients, $subject, $message) {
        $results = [];
        foreach ($recipients as $email) {
            $results[$email] = $this->send($email, $subject, $message);
            sleep(1); // Задержка между отправками
        }
        return $results;
    }
}
?>