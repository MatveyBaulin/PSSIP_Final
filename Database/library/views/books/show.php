<div class="book-detail">
    <div class="book-header">
        <h2><?php echo htmlspecialchars($book['title']); ?></h2>
        <a href="/library/" class="back-link">← Назад</a>
    </div>
    
    <div class="book-info">
        <p><strong>✍️ Автор:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
        
        <?php if ($book['year']): ?>
            <p><strong>📅 Год издания:</strong> <?php echo $book['year']; ?></p>
        <?php endif; ?>
        
        <?php if ($book['genre']): ?>
            <p><strong>📖 Жанр:</strong> <?php echo htmlspecialchars($book['genre']); ?></p>
        <?php endif; ?>
        
        <?php if ($book['description']): ?>
            <div class="description">
                <strong>📝 Описание:</strong>
                <p><?php echo nl2br(htmlspecialchars($book['description'])); ?></p>
            </div>
        <?php endif; ?>
        
        <p class="date">
            <small>Добавлено: <?php echo date('d.m.Y H:i', strtotime($book['created_at'])); ?></small>
        </p>
    </div>
    
    <div class="book-actions">
        <a href="/library/books/edit/<?php echo $book['id']; ?>" class="btn">✏️ Редактировать</a>
        <form action="/library/books/delete/<?php echo $book['id']; ?>" method="POST" 
              onsubmit="return confirm('Удалить книгу?')">
            <button type="submit" class="btn delete">🗑️ Удалить</button>
        </form>
    </div>
</div>