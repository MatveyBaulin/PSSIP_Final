<?php
require_once 'config/database.php';
require_once 'includes/cart_functions.php';

// Параметры пагинации
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Параметры сортировки
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'name';
$order = isset($_GET['order']) ? $_GET['order'] : 'ASC';

// Параметры фильтрации
$category = isset($_GET['category']) ? $_GET['category'] : '';
$level = isset($_GET['level']) ? $_GET['level'] : '';
$price_min = isset($_GET['price_min']) ? (float)$_GET['price_min'] : 0;
$price_max = isset($_GET['price_max']) ? (float)$_GET['price_max'] : 100000;

// Поиск
$search = isset($_GET['search']) ? $_GET['search'] : '';

$database = new Database();
$db = $database->getConnection();

// Формирование WHERE условия
$where_conditions = [];
$params = [];

if (!empty($search)) {
    $where_conditions[] = "name LIKE :search";
    $params[':search'] = "%$search%";
}

if (!empty($category)) {
    $where_conditions[] = "category = :category";
    $params[':category'] = $category;
}

if (!empty($level)) {
    $where_conditions[] = "level = :level";
    $params[':level'] = $level;
}

if ($price_min > 0) {
    $where_conditions[] = "price >= :price_min";
    $params[':price_min'] = $price_min;
}

if ($price_max < 100000) {
    $where_conditions[] = "price <= :price_max";
    $params[':price_max'] = $price_max;
}

$where_sql = "";
if (count($where_conditions) > 0) {
    $where_sql = "WHERE " . implode(" AND ", $where_conditions);
}

// Подсчет общего количества записей
$count_query = "SELECT COUNT(*) as total FROM products $where_sql";
$count_stmt = $db->prepare($count_query);
foreach ($params as $key => $value) {
    $count_stmt->bindValue($key, $value);
}
$count_stmt->execute();
$total_records = $count_stmt->fetch(PDO::FETCH_ASSOC)['total'];
$total_pages = ceil($total_records / $limit);

// Получение данных с пагинацией и сортировкой
$query = "SELECT * FROM products $where_sql ORDER BY $sort $order LIMIT :limit OFFSET :offset";
$stmt = $db->prepare($query);

foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Получение уникальных категорий для фильтра
$categories_query = "SELECT DISTINCT category FROM products ORDER BY category";
$categories_stmt = $db->query($categories_query);
$categories = $categories_stmt->fetchAll(PDO::FETCH_COLUMN);

// Получение уникальных уровней для фильтра
$levels_query = "SELECT DISTINCT level FROM products ORDER BY level";
$levels_stmt = $db->query($levels_query);
$levels = $levels_stmt->fetchAll(PDO::FETCH_COLUMN);

// Получаем количество товаров в корзине для отображения в шапке
$cart_count = getCartCount();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Каталог продукции</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📚 Каталог курсов</h1>
            <div class="cart-info">
                <a href="cart.php" class="cart-link">
                    🛒 Корзина 
                    <?php if ($cart_count > 0): ?>
                        <span class="cart-badge"><?php echo $cart_count; ?></span>
                    <?php endif; ?>
                </a>
            </div>
        </div>
        
        <!-- Поиск -->
        <div class="search-section">
            <form action="index.php" method="GET" class="search-form">
                <input type="text" name="search" placeholder="Поиск курсов..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit">Найти</button>
                <?php if (!empty($search)): ?>
                    <a href="index.php" class="clear-btn">Сбросить</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Фильтры -->
        <div class="filters-section">
            <form action="index.php" method="GET" class="filters-form">
                <div class="filter-group">
                    <label>Категория:</label>
                    <select name="category">
                        <option value="">Все категории</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo $category == $cat ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Уровень:</label>
                    <select name="level">
                        <option value="">Все уровни</option>
                        <?php foreach ($levels as $lvl): ?>
                            <option value="<?php echo htmlspecialchars($lvl); ?>" <?php echo $level == $lvl ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($lvl); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Цена от:</label>
                    <input type="number" name="price_min" value="<?php echo $price_min; ?>" min="0">
                </div>

                <div class="filter-group">
                    <label>Цена до:</label>
                    <input type="number" name="price_max" value="<?php echo $price_max; ?>" min="0">
                </div>

                <?php
                // Сохраняем параметры для ссылок сортировки
                $query_params = [
                    'search' => $search,
                    'category' => $category,
                    'level' => $level,
                    'price_min' => $price_min,
                    'price_max' => $price_max
                ];
                $query_string = http_build_query($query_params);
                ?>
                
                <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sort); ?>">
                <input type="hidden" name="order" value="<?php echo htmlspecialchars($order); ?>">
                
                <button type="submit">Применить фильтры</button>
                <?php if (!empty($category) || !empty($level) || $price_min > 0 || $price_max < 100000): ?>
                    <a href="index.php" class="clear-btn">Сбросить фильтры</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Таблица каталога -->
        <section class="pricing-table">
            <div class="table-header">
                <p>
                    Название курса 
                    <a href="?sort=name&order=ASC&<?php echo $query_string; ?>">↑</a>
                    <a href="?sort=name&order=DESC&<?php echo $query_string; ?>">↓</a>
                </p>
                <p>
                    Стоимость (₽)
                    <a href="?sort=price&order=ASC&<?php echo $query_string; ?>">↑</a>
                    <a href="?sort=price&order=DESC&<?php echo $query_string; ?>">↓</a>
                </p>
                <p>
                    Время (мес.)
                    <a href="?sort=duration&order=ASC&<?php echo $query_string; ?>">↑</a>
                    <a href="?sort=duration&order=DESC&<?php echo $query_string; ?>">↓</a>
                </p>
                <p>Действие</p>
            </div>
            
            <div id="table-body">
                <?php foreach ($products as $product): ?>
                <div class="product-row">
                    <p><?php echo htmlspecialchars($product['name']); ?></p>
                    <p><?php echo number_format($product['price'], 0, '.', ' '); ?> ₽</p>
                    <p><?php echo $product['duration']; ?></p>
                    <form action="add_to_cart.php" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="add-to-cart">Добавить</button>
                    </form>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Итоговая строка -->
            <?php
            $total_price = array_sum(array_column($products, 'price'));
            $total_duration = array_sum(array_column($products, 'duration'));
            ?>
            <div class="total-row" id="total-row">
                <p><b>Итого по странице</b></p>
                <p><b><?php echo number_format($total_price, 0, '.', ' '); ?> ₽</b></p>
                <p><b><?php echo $total_duration; ?> мес.</b></p>
                <p></p>
            </div>
        </section>

        <!-- Пагинация -->
        <?php if ($total_pages > 1): ?>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=1&sort=<?php echo urlencode($sort); ?>&order=<?php echo urlencode($order); ?>&<?php echo $query_string; ?>">«</a>
                <a href="?page=<?php echo $page - 1; ?>&sort=<?php echo urlencode($sort); ?>&order=<?php echo urlencode($order); ?>&<?php echo $query_string; ?>">‹</a>
            <?php endif; ?>

            <?php
            $start = max(1, $page - 2);
            $end = min($total_pages, $page + 2);
            for ($i = $start; $i <= $end; $i++):
            ?>
                <a href="?page=<?php echo $i; ?>&sort=<?php echo urlencode($sort); ?>&order=<?php echo urlencode($order); ?>&<?php echo $query_string; ?>" class="<?php echo $i == $page ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="?page=<?php echo $page + 1; ?>&sort=<?php echo urlencode($sort); ?>&order=<?php echo urlencode($order); ?>&<?php echo $query_string; ?>">›</a>
                <a href="?page=<?php echo $total_pages; ?>&sort=<?php echo urlencode($sort); ?>&order=<?php echo urlencode($order); ?>&<?php echo $query_string; ?>">»</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>