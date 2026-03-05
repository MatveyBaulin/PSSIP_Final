<div class="books-header">
    <h2><?php echo $title; ?></h2>
    <?php if (isset($search)): ?>
        <p>По запросу "<?php echo htmlspecialchars($search); ?>" найдено: <?php echo count($books); ?></p>
    <?php endif; ?>
</div>

<?php if (empty($books)): ?>
    <div class="empty-state">
        <p>😕 Книги не найдены</p>
        <a href="/library/books/create" class="btn">Добавить первую книгу</a>
    </div>
<?php else: ?>
    <div class="books-grid">
        <?php foreach ($books as $book): ?>
            <div class="book-card">
                <h3><?php echo htmlspecialchars($book['title']); ?></h3>
                <p class="author">✍️ <?php echo htmlspecialchars($book['author']); ?></p>
                
                <?php if ($book['year']): ?>
                    <p class="year">📅 <?php echo $book['year']; ?> г.</p>
                <?php endif; ?>
                
                <?php if ($book['genre']): ?>
                    <p class="genre">📖 <?php echo htmlspecialchars($book['genre']); ?></p>
                <?php endif; ?>
                
                <div class="book-actions">
                    <a href="/library/books/show/<?php echo $book['id']; ?>" class="btn-small">Подробнее</a>
                    <a href="/library/books/edit/<?php echo $book['id']; ?>" class="btn-small edit">✏️</a>
                    <form action="/library/books/delete/<?php echo $book['id']; ?>" method="POST" 
                          onsubmit="return confirm('Удалить книгу?')">
                        <button type="submit" class="btn-small delete">🗑️</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>