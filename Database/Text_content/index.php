<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мой контент из БД</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        h1 {
            text-align: center;
            color: white;
            margin-bottom: 30px;
            font-size: 2.5em;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .content-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            transition: transform 0.3s ease;
        }
        
        .content-card:hover {
            transform: translateY(-5px);
        }
        
        .content-title {
            color: #333;
            margin-bottom: 15px;
            font-size: 1.5em;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        
        .content-text {
            color: #666;
            line-height: 1.6;
            font-size: 1.1em;
        }
        
        .content-date {
            margin-top: 15px;
            color: #999;
            font-size: 0.9em;
            text-align: right;
        }
        
        .no-content {
            text-align: center;
            color: white;
            font-size: 1.2em;
            padding: 40px;
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Наш контент</h1>
        
        <?php
// Подключаемся к базе данных
require_once 'config.php';

try {
    // Запрос на получение всех записей (используем MySQLi вместо PDO)
    $result = $conn->query("SELECT * FROM text_content ORDER BY created_at DESC");
    
    if (!$result) {
        throw new Exception($conn->error);
    }
    
    // Проверяем, есть ли записи
    if ($result->num_rows > 0) {
        // Выводим каждую запись
        while ($item = $result->fetch_assoc()) {
            echo '<div class="content-card">';
            echo '<h2 class="content-title">' . htmlspecialchars($item['title']) . '</h2>';
            echo '<div class="content-text">' . nl2br(htmlspecialchars($item['content'])) . '</div>';
            echo '<div class="content-date"> ' . date('d.m.Y H:i', strtotime($item['created_at'])) . '</div>';
            echo '</div>';
        }
    } else {
        echo '<div class="no-content"> Пока нет контента в базе данных</div>';
    }
    
} catch(Exception $e) {
    echo '<div class="no-content" style="background: #ff6b6b;"> Ошибка базы данных: ' . $e->getMessage() . '</div>';
}
?>
        
    </div>
</body>
</html>