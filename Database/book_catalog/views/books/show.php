<div class="book-detail">
    <div class="book-header">
        <h2><?= htmlspecialchars($book['title']) ?></h2>
        <a href="/book-catalog/public/" class="back-link">← Назад</a>
    </div>
    
    <div class="book-info">
        <p><strong>Автор:</strong> <?= htmlspecialchars($book['author']) ?></p>
        
        <?php if ($book['year']): ?>
            <p><strong>Год издания:</strong> <?= $book['year'] ?></p>
        <?php endif; ?>
        
        <?php if ($book['price']): ?>
            <p><strong>Цена:</strong> <?= number_format($book['price'], 0, '.', ' ') ?> ₽</p>
        <?php endif; ?>
        
        <?php if ($book['description']): ?>
            <div class="description">
                <strong>Описание:</strong>
                <p><?= nl2br(htmlspecialchars($book['description'])) ?></p>
            </div>
        <?php endif; ?>
        
        <p class="date">
            <small>Добавлено: <?= date('d.m.Y H:i', strtotime($book['created_at'])) ?></small>
        </p>
    </div>
    
    <div class="book-actions">
        <a href="#" class="btn" onclick="alert('Редактирование в разработке')">Редактировать</a>
        <form action="/book-catalog/public/books/delete/<?= $book['id'] ?>" method="POST" 
              onsubmit="return confirm('Удалить книгу?')">
            <button type="submit" class="btn delete">Удалить</button>
        </form>
    </div>
</div>