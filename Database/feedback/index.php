<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Обратная связь</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1>📧 Обратная связь</h1>
            <p class="subtitle">Напишите нам, и мы ответим в ближайшее время</p>
            
            <!-- Блок для сообщений -->
            <div id="message" class="message" style="display: none;"></div>
            
            <!-- Форма обратной связи -->
            <form id="feedbackForm" method="POST" action="send.php">
                <div class="form-group">
                    <label for="name">Ваше имя *</label>
                    <input type="text" id="name" name="name" required 
                           placeholder="Иван Иванов"
                           value="<?php echo htmlspecialchars($_SESSION['form_data']['name'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="email">Ваш Email *</label>
                    <input type="email" id="email" name="email" required 
                           placeholder="ivan@example.com"
                           value="<?php echo htmlspecialchars($_SESSION['form_data']['email'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="subject">Тема сообщения</label>
                    <select id="subject" name="subject">
                        <option value="Общий вопрос">Общий вопрос</option>
                        <option value="Техническая поддержка">Техническая поддержка</option>
                        <option value="Сотрудничество">Сотрудничество</option>
                        <option value="Жалоба">Жалоба</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="message_text">Сообщение *</label>
                    <textarea id="message_text" name="message_text" rows="5" required 
                              placeholder="Напишите ваше сообщение..."><?php echo htmlspecialchars($_SESSION['form_data']['message_text'] ?? ''); ?></textarea>
                </div>
                
                <button type="submit" class="submit-btn">Отправить сообщение</button>
            </form>
            
            <div class="contact-info">
                <p>📞 +375 (29) 8393444</p>
                <p>✉️ baulinm2006@gmail.com</p>
                <a href="./test.php">Тест</a>
            </div>
        </div>
    </div>
    
    <?php
    // Показываем сообщение об успехе/ошибке из сессии
    if (isset($_SESSION['feedback_message'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const messageDiv = document.getElementById('message');
                messageDiv.className = 'message <?php echo $_SESSION['feedback_type']; ?>';
                messageDiv.innerHTML = '<?php echo $_SESSION['feedback_message']; ?>';
                messageDiv.style.display = 'block';
                
                <?php if ($_SESSION['feedback_type'] == 'success'): ?>
                    setTimeout(() => {
                        messageDiv.style.display = 'none';
                    }, 5000);
                <?php endif; ?>
            });
        </script>
    <?php 
        unset($_SESSION['feedback_message']);
        unset($_SESSION['feedback_type']);
        unset($_SESSION['form_data']);
    endif; 
    ?>
    
    <script src="js/main.js"></script>
</body>
</html>