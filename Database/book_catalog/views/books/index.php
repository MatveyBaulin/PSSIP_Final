<div class="books-header">
    <h2>Все книги</h2>
    <?php if (isset($search)): ?>
        <p>Результаты поиска по запросу "<?= htmlspecialchars($search) ?>"</p>
    <?php endif; ?>
</div>

<?php if (empty($books)): ?>
    <div class="empty-state">
        <p>Книги не найдены</p>
        <a href="/book-catalog/public/books/create" class="btn">Добавить первую книгу</a>
    </div>
<?php else: ?>
    <div class="books-grid">
        <?php foreach ($books as $book): ?>
            <div class="book-card">
                <h3><?= htmlspecialchars($book['title']) ?></h3>
                <p class="author"><?= htmlspecialchars($book['author']) ?></p>
                
                <?php if ($book['year']): ?>
                    <p class="year">📅 <?= $book['year'] ?> г.</p>
                <?php endif; ?>
                
                <?php if ($book['price']): ?>
                    <p class="price">💰 <?= number_format($book['price'], 0, '.', ' ') ?> ₽</p>
                <?php endif; ?>
                
                <div class="book-actions">
                    <a href="/book-catalog/public/books/show/<?= $book['id'] ?>" class="btn-small">Подробнее</a>
                    <form action="/book-catalog/public/books/delete/<?= $book['id'] ?>" method="POST" 
                          onsubmit="return confirm('Удалить книгу?')">
                        <button type="submit" class="btn-small delete">Удалить</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>