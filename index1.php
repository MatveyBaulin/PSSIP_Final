<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация на сайте</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .registration-form {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 500px;
        }
        .form-section {
            margin-bottom: 20px;
        }
        .form-section h3 {
            color: #333;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box; /* Include padding and border in the element's total width and height */
        }
        .form-group select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-group input[type="date"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-group input[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }
        .form-group input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .error-message {
            color: red;
            font-size: 12px;
            margin-top: 5px;
        }
        .required-asterisk {
            color: red;
        }
    </style>
</head>
<body>

    <div class="registration-form">
        <h2>Регистрация на сайте</h2>

        <?php
        // PHP-обработка формы

        $errors = [];
        $success_message = '';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Получение данных из формы
            $login = $_POST['login'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $dob_day = $_POST['dob_day'] ?? '';
            $dob_month = $_POST['dob_month'] ?? '';
            $dob_year = $_POST['dob_year'] ?? '';

            // Валидация поля "Логин"
            if (empty($login)) {
                $errors['login'] = "Поле 'Логин' обязательно для заполнения.";
            } elseif (strlen($login) < 5) {
                $errors['login'] = "Логин должен содержать не менее 5 символов.";
            } elseif (!preg_match('/^[a-zA-Z0-9-_]+$/', $login)) {
                $errors['login'] = "Логин может содержать только цифры, буквы, тире (-) и нижнее подчеркивание (_).";
            }

            // Валидация поля "Пароль"
            if (empty($password)) {
                $errors['password'] = "Поле 'Пароль' обязательно для заполнения.";
            } elseif (strlen($password) < 6) {
                $errors['password'] = "Пароль должен содержать не менее 6 символов.";
            }

            // Валидация поля "Пароль еще раз"
            if (empty($confirm_password)) {
                $errors['confirm_password'] = "Поле 'Пароль еще раз' обязательно для заполнения.";
            } elseif ($password !== $confirm_password) {
                $errors['confirm_password'] = "Пароли не совпадают.";
            }

            // Валидация поля "Эл. почта" (необязательное, но если заполнено, то валидное)
            if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Некорректный формат электронной почты.";
            }

            // Валидация даты рождения (простая проверка, что все поля заполнены, если хоть одно заполнено)
            $dob_full_date = '';
            if (!empty($dob_day) || !empty($dob_month) || !empty($dob_year)) {
                if (empty($dob_day) || empty($dob_month) || empty($dob_year)) {
                    $errors['dob'] = "Пожалуйста, заполните все поля даты рождения или оставьте их пустыми.";
                } else {
                    // Создаем строку даты для возможного сохранения
                    $dob_full_date = "$dob_year-$dob_month-$dob_day";
                    if (!checkdate((int)$dob_month, (int)$dob_day, (int)$dob_year)) {
                        $errors['dob'] = "Некорректная дата.";
                    }
                }
            }

            // Если нет ошибок, можно сохранить данные (в базу данных или вывести сообщение)
            if (empty($errors)) {
                // Здесь должен быть код для сохранения данных в базу данных
                // Например:
                // $servername = "localhost";
                // $username = "your_db_username";
                // $password_db = "your_db_password";
                // $dbname = "your_db_name";
                // $conn = new mysqli($servername, $username, $password_db, $dbname);
                // if ($conn->connect_error) {
                //     die("Connection failed: " . $conn->connect_error);
                // }
                // $stmt = $conn->prepare("INSERT INTO users (login, password, name, email, dob) VALUES (?, ?, ?, ?, ?)");
                // $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Хэшируем пароль
                // $stmt->bind_param("sssss", $login, $hashed_password, $name, $email, $dob_full_date);
                // $stmt->execute();
                // $stmt->close();
                // $conn->close();

                $success_message = "Регистрация успешно завершена! Добро пожаловать, {$login}!";
                // Можно очистить поля формы после успешной регистрации, перенаправив пользователя
                // header("Location: registration_success.php");
                // exit();
            }
        }
        ?>

        <form action="" method="post">
            <div class="form-section">
                <h3>Основные данные</h3>
                <div class="form-group">
                    <label for="login">Логин <span class="required-asterisk">*</span></label>
                    <input type="text" id="login" name="login" value="<?= htmlspecialchars($login ?? '') ?>">
                    <?php if (isset($errors['login'])): ?>
                        <div class="error-message"><?= $errors['login'] ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="password">Пароль <span class="required-asterisk">*</span></label>
                    <input type="password" id="password" name="password">
                    <?php if (isset($errors['password'])): ?>
                        <div class="error-message"><?= $errors['password'] ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Пароль еще раз <span class="required-asterisk">*</span></label>
                    <input type="password" id="confirm_password" name="confirm_password">
                    <?php if (isset($errors['confirm_password'])): ?>
                        <div class="error-message"><?= $errors['confirm_password'] ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-section">
                <h3>Дополнительные данные</h3>
                <div class="form-group">
                    <label for="name">Имя</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($name ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="email">Эл. почта</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>">
                    <?php if (isset($errors['email'])): ?>
                        <div class="error-message"><?= $errors['email'] ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="dob_day">Дата рождения</label>
                    <div>
                        <select name="dob_day" id="dob_day">
                            <option value="">День</option>
                            <?php for ($i = 1; $i <= 31; $i++): ?>
                                <option value="<?= $i ?>" <?= (isset($_POST['dob_day']) && $_POST['dob_day'] == $i) ? 'selected' : '' ?>><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                        <select name="dob_month" id="dob_month">
                            <option value="">Месяц</option>
                            <option value="01" <?= (isset($_POST['dob_month']) && $_POST['dob_month'] == '01') ? 'selected' : '' ?>>Января</option>
                            <option value="02" <?= (isset($_POST['dob_month']) && $_POST['dob_month'] == '02') ? 'selected' : '' ?>>Февраля</option>
                            <option value="03" <?= (isset($_POST['dob_month']) && $_POST['dob_month'] == '03') ? 'selected' : '' ?>>Марта</option>
                            <option value="04" <?= (isset($_POST['dob_month']) && $_POST['dob_month'] == '04') ? 'selected' : '' ?>>Апреля</option>
                            <option value="05" <?= (isset($_POST['dob_month']) && $_POST['dob_month'] == '05') ? 'selected' : '' ?>>Мая</option>
                            <option value="06" <?= (isset($_POST['dob_month']) && $_POST['dob_month'] == '06') ? 'selected' : '' ?>>Июня</option>
                            <option value="07" <?= (isset($_POST['dob_month']) && $_POST['dob_month'] == '07') ? 'selected' : '' ?>>Июля</option>
                            <option value="08" <?= (isset($_POST['dob_month']) && $_POST['dob_month'] == '08') ? 'selected' : '' ?>>Августа</option>
                            <option value="09" <?= (isset($_POST['dob_month']) && $_POST['dob_month'] == '09') ? 'selected' : '' ?>>Сентября</option>
                            <option value="10" <?= (isset($_POST['dob_month']) && $_POST['dob_month'] == '10') ? 'selected' : '' ?>>Октября</option>
                            <option value="11" <?= (isset($_POST['dob_month']) && $_POST['dob_month'] == '11') ? 'selected' : '' ?>>Ноября</option>
                            <option value="12" <?= (isset($_POST['dob_month']) && $_POST['dob_month'] == '12') ? 'selected' : '' ?>>Декабря</option>
                        </select>
                        <select name="dob_year" id="dob_year">
                            <option value="">Год</option>
                            <?php
                                $current_year = date('Y');
                                for ($i = $current_year - 100; $i <= $current_year; $i++): ?>
                                <option value="<?= $i ?>" <?= (isset($_POST['dob_year']) && $_POST['dob_year'] == $i) ? 'selected' : '' ?>><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <?php if (isset($errors['dob'])): ?>
                        <div class="error-message"><?= $errors['dob'] ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group">
                <input type="submit" value="Зарегистрироваться">
            </div>
        </form>

        <?php if (!empty($success_message)): ?>
            <div class="success-message" style="color: green; font-weight: bold; margin-top: 20px;">
                <?= $success_message ?>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>
