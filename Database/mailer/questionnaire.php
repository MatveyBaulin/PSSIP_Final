<?php
// questionnaire.php - Отправка анкеты

require_once 'config.php';
require_once 'sendmail.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Сбор данных анкеты
    $fullname = htmlspecialchars($_POST['fullname'] ?? '');
    $birthdate = $_POST['birthdate'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $education = $_POST['education'] ?? '';
    $experience = $_POST['experience'] ?? '';
    $skills = $_POST['skills'] ?? [];
    $about = htmlspecialchars($_POST['about'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $phone = htmlspecialchars($_POST['phone'] ?? '');
    
    // Валидация
    $errors = [];
    if (empty($fullname)) $errors[] = 'Укажите ФИО';
    if (empty($birthdate)) $errors[] = 'Укажите дату рождения';
    if (empty($gender)) $errors[] = 'Укажите пол';
    if (!$email) $errors[] = 'Укажите корректный email';
    
    if (empty($errors)) {
        // Формирование письма
        $skills_text = !empty($skills) ? implode(', ', $skills) : 'Не указаны';
        
        $content = "<h2>Новая анкета</h2>
                   <h3>Личная информация</h3>
                   <p><strong>ФИО:</strong> $fullname</p>
                   <p><strong>Дата рождения:</strong> $birthdate</p>
                   <p><strong>Пол:</strong> " . ($gender == 'male' ? 'Мужской' : 'Женский') . "</p>
                   <p><strong>Email:</strong> $email</p>
                   <p><strong>Телефон:</strong> " . ($phone ?: 'Не указан') . "</p>
                   
                   <h3>Образование и опыт</h3>
                   <p><strong>Образование:</strong> $education</p>
                   <p><strong>Опыт работы:</strong> $experience</p>
                   <p><strong>Навыки:</strong> $skills_text</p>
                   
                   <h3>О себе</h3>
                   <p>" . nl2br($about) . "</p>
                   
                   <p><strong>Дата заполнения:</strong> " . date('d.m.Y H:i:s') . "</p>
                   <p><strong>IP адрес:</strong> " . $_SERVER['REMOTE_ADDR'] . "</p>";
        
        // Отправка
        $mailer = new MailSender();
        $result = $mailer->send(ADMIN_EMAIL, "Новая анкета: $fullname", $content, $email);
        
        if ($result['success']) {
            // Подтверждение пользователю
            $userContent = "<h3>Здравствуйте, $fullname!</h3>
                           <p>Ваша анкета успешно получена. Мы рассмотрим её в ближайшее время.</p>
                           <p>С уважением, команда " . SITE_NAME . "</p>";
            
            $mailer->send($email, "Анкета получена", $userContent);
            
            $response = [
                'success' => true,
                'message' => 'Анкета отправлена! Спасибо за участие.'
            ];
        } else {
            $response['message'] = 'Ошибка при отправке анкеты';
        }
    } else {
        $response['message'] = implode('<br>', $errors);
    }
    
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
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
    <title>Анкета</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        h1 { text-align: center; color: #333; margin-bottom: 10px; }
        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
        }
        .section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }
        .section h2 {
            font-size: 18px;
            margin-bottom: 20px;
            color: #333;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        .form-group { margin-bottom: 15px; }
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
        textarea { min-height: 100px; resize: vertical; }
        .radio-group {
            display: flex;
            gap: 20px;
            padding: 10px 0;
        }
        .radio-group label {
            display: flex;
            align-items: center;
            gap: 5px;
            font-weight: normal;
        }
        .radio-group input {
            width: auto;
        }
        .checkbox-group {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 10px;
            padding: 10px 0;
        }
        .checkbox-group label {
            display: flex;
            align-items: center;
            gap: 5px;
            font-weight: normal;
        }
        .checkbox-group input {
            width: auto;
        }
        .row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        button {
            width: 100%;
            padding: 15px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover { background: #218838; }
        .message {
            padding: 15px;
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
        @media (max-width: 600px) {
            .row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📋 Анкета кандидата</h1>
        <p class="subtitle">Заполните форму для участия в программе стажировки</p>
        
        <div id="message" class="message"></div>
        
        <form id="questionnaireForm" method="POST" action="questionnaire.php">
            <!-- Личная информация -->
            <div class="section">
                <h2>Личная информация</h2>
                
                <div class="form-group">
                    <label for="fullname">ФИО *</label>
                    <input type="text" id="fullname" name="fullname" required>
                </div>
                
                <div class="row">
                    <div class="form-group">
                        <label for="birthdate">Дата рождения *</label>
                        <input type="date" id="birthdate" name="birthdate" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Пол *</label>
                        <div class="radio-group">
                            <label>
                                <input type="radio" name="gender" value="male" required> Мужской
                            </label>
                            <label>
                                <input type="radio" name="gender" value="female" required> Женский
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Телефон</label>
                        <input type="tel" id="phone" name="phone" placeholder="+7 (999) 123-45-67">
                    </div>
                </div>
            </div>
            
            <!-- Образование -->
            <div class="section">
                <h2>Образование и опыт</h2>
                
                <div class="form-group">
                    <label for="education">Образование</label>
                    <select id="education" name="education">
                        <option value="Среднее">Среднее</option>
                        <option value="Среднее специальное">Среднее специальное</option>
                        <option value="Неоконченное высшее">Неоконченное высшее</option>
                        <option value="Высшее">Высшее</option>
                        <option value="Магистр">Магистр</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="experience">Опыт работы</label>
                    <select id="experience" name="experience">
                        <option value="Нет опыта">Нет опыта</option>
                        <option value="Менее года">Менее года</option>
                        <option value="1-3 года">1-3 года</option>
                        <option value="3-5 лет">3-5 лет</option>
                        <option value="Более 5 лет">Более 5 лет</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Навыки (выберите несколько)</label>
                    <div class="checkbox-group">
                        <label><input type="checkbox" name="skills[]" value="PHP"> PHP</label>
                        <label><input type="checkbox" name="skills[]" value="JavaScript"> JavaScript</label>
                        <label><input type="checkbox" name="skills[]" value="HTML/CSS"> HTML/CSS</label>
                        <label><input type="checkbox" name="skills[]" value="Python"> Python</label>
                        <label><input type="checkbox" name="skills[]" value="Java"> Java</label>
                        <label><input type="checkbox" name="skills[]" value="SQL"> SQL</label>
                        <label><input type="checkbox" name="skills[]" value="Git"> Git</label>
                        <label><input type="checkbox" name="skills[]" value="Laravel"> Laravel</label>
                        <label><input type="checkbox" name="skills[]" value="Vue.js"> Vue.js</label>
                        <label><input type="checkbox" name="skills[]" value="React"> React</label>
                    </div>
                </div>
            </div>
            
            <!-- О себе -->
            <div class="section">
                <h2>О себе</h2>
                
                <div class="form-group">
                    <label for="about">Расскажите о себе, ваших достижениях и целях</label>
                    <textarea id="about" name="about" placeholder="Напишите немного о себе..."></textarea>
                </div>
            </div>
            
            <button type="submit">Отправить анкету</button>
        </form>
    </div>
    
    <script>
        document.getElementById('questionnaireForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const messageDiv = document.getElementById('message');
            
            fetch('questionnaire.php', {
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
                    this.reset();
                    setTimeout(() => {
                        messageDiv.style.display = 'none';
                    }, 5000);
                }
                
                window.scrollTo({ top: 0, behavior: 'smooth' });
            })
            .catch(error => {
                messageDiv.className = 'message error';
                messageDiv.textContent = 'Произошла ошибка при отправке';
                messageDiv.style.display = 'block';
            });
        });
    </script>
</body>
</html>