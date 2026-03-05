<div class="form-container">
    <h2>✏️ Редактировать книгу</h2>
    
    <form action="/library/books/update/<?php echo $book['id']; ?>" method="POST" class="book-form">
        <div class="form-group">
            <label for="title">Название *</label>
            <input type="text" id="title" name="title" 
                   value="<?php echo htmlspecialchars($_SESSION['old']['title'] ?? $book['title']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="author">Автор *</label>
            <input type="text" id="author" name="author" 
                   value="<?php echo htmlspecialchars($_SESSION['old']['author'] ?? $book['author']); ?>" required>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="year">Год издания</label>
                <input type="number" id="year" name="year" min="1000" max="<?php echo date('Y'); ?>"
                       value="<?php echo htmlspecialchars($_SESSION['old']['year'] ?? $book['year']); ?>">
            </div>
            
            <div class="form-group">
                <label for="genre">Жанр</label>
                <input type="text" id="genre" name="genre" 
                       value="<?php echo htmlspecialchars($_SESSION['old']['genre'] ?? $book['genre']); ?>">
            </div>
        </div>
        
        <div class="form-group">
            <label for="description">Описание</label>
            <textarea id="description" name="description" rows="4"><?php 
                echo htmlspecialchars($_SESSION['old']['description'] ?? $book['description']); 
            ?></textarea>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn">💾 Сохранить изменения</button>
            <a href="/library/books/show/<?php echo $book['id']; ?>" class="btn cancel">❌ Отмена</a>
        </div>
    </form>
</div>

<?php 
unset($_SESSION['old']);
?>