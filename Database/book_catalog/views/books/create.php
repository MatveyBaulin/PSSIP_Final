<div class="form-container">
    <h2>Добавить новую книгу</h2>
    
    <form action="/book-catalog/public/books/store" method="POST" class="book-form">
        <div class="form-group">
            <label for="title">Название *</label>
            <input type="text" id="title" name="title" required>
        </div>
        
        <div class="form-group">
            <label for="author">Автор *</label>
            <input type="text" id="author" name="author" required>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="year">Год издания</label>
                <input type="number" id="year" name="year" min="1000" max="<?= date('Y') ?>">
            </div>
            
            <div class="form-group">
                <label for="price">Цена (₽)</label>
                <input type="number" id="price" name="price" min="0" step="0.01">
            </div>
        </div>
        
        <div class="form-group">
            <label for="description">Описание</label>
            <textarea id="description" name="description" rows="4"></textarea>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn">Сохранить</button>
            <a href="/book-catalog/public/" class="btn cancel">Отмена</a>
        </div>
    </form>
</div>