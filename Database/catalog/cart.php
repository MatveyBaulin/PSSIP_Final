<?php
require_once 'config/database.php';
require_once 'includes/cart_functions.php';

$database = new Database();
$db = $database->getConnection();

$cart_data = getCartDetails($db);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Корзина</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1> Корзина</h1>
        
        <div class="cart-header">
            <a href="index.php" class="back-link">← Вернуться в каталог</a>
        </div>
        
        <?php if (empty($cart_data['items'])): ?>
            <div class="empty-cart">
                <p>Корзина пуста</p>
                <a href="index.php" class="btn">Перейти в каталог</a>
            </div>
        <?php else: ?>
            <div class="cart-table">
                <div class="cart-header-row">
                    <div>Товар</div>
                    <div>Цена</div>
                    <div>Количество</div>
                    <div>Сумма</div>
                    <div>Действие</div>
                </div>
                
                <?php foreach ($cart_data['items'] as $item): ?>
                <div class="cart-item-row">
                    <div><?php echo htmlspecialchars($item['name']); ?></div>
                    <div><?php echo number_format($item['price'], 0, '.', ' '); ?> ₽</div>
                    <div>
                        <form action="update_cart.php" method="POST" class="quantity-form">
                            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                            <input type="number" name="quantity" value="<?php echo $item['cart_quantity']; ?>" min="1" max="99">
                            <button type="submit">Обновить</button>
                        </form>
                    </div>
                    <div><?php echo number_format($item['subtotal'], 0, '.', ' '); ?> ₽</div>
                    <div>
                        <form action="remove_from_cart.php" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                            <button type="submit" class="remove-btn">Удалить</button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <div class="cart-total-row">
                    <div colspan="3" class="total-label"><strong>Итого:</strong></div>
                    <div><strong><?php echo number_format($cart_data['total_price'], 0, '.', ' '); ?> ₽</strong></div>
                    <div></div>
                </div>
                
                <!-- В блоке cart-actions замените checkout-btn -->
                <div class="cart-actions">
                <form action="clear_cart.php" method="POST">
                <button type="submit" class="clear-btn">Очистить корзину</button>
                </form>
                <a href="checkout.php" class="checkout-btn">Оформить заказ</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>