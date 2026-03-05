// Основной JavaScript файл для приложения Library

// Ждем загрузки DOM
document.addEventListener('DOMContentLoaded', function() {
    console.log('📚 Библиотека загружена');
    
    // Инициализация всех компонентов
    initAutoHideAlerts();
    initFormValidation();
    initSearchForm();
    initTooltips();
    initApiButtons();
});

// Автоматическое скрытие уведомлений через 5 секунд
function initAutoHideAlerts() {
    const alerts = document.querySelectorAll('.alert');
    
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            
            setTimeout(() => {
                alert.style.display = 'none';
            }, 500);
        }, 5000);
    });
}

// Валидация форм на клиенте
function initFormValidation() {
    const bookForm = document.querySelector('.book-form');
    
    if (bookForm) {
        bookForm.addEventListener('submit', function(e) {
            const title = document.getElementById('title');
            const author = document.getElementById('author');
            const year = document.getElementById('year');
            let isValid = true;
            let errors = [];
            
            // Проверка названия
            if (!title.value.trim()) {
                isValid = false;
                errors.push('Название книги обязательно');
                highlightField(title, false);
            } else {
                highlightField(title, true);
            }
            
            // Проверка автора
            if (!author.value.trim()) {
                isValid = false;
                errors.push('Автор обязателен');
                highlightField(author, false);
            } else {
                highlightField(author, true);
            }
            
            // Проверка года (если введен)
            if (year.value) {
                const currentYear = new Date().getFullYear();
                const yearNum = parseInt(year.value);
                
                if (yearNum < 1000 || yearNum > currentYear) {
                    isValid = false;
                    errors.push(`Год должен быть между 1000 и ${currentYear}`);
                    highlightField(year, false);
                } else {
                    highlightField(year, true);
                }
            }
            
            if (!isValid) {
                e.preventDefault();
                showValidationErrors(errors);
            }
        });
    }
}

// Подсветка полей
function highlightField(field, isValid) {
    if (isValid) {
        field.style.borderColor = '#28a745';
        field.style.backgroundColor = '#f8fff8';
    } else {
        field.style.borderColor = '#dc3545';
        field.style.backgroundColor = '#fff8f8';
        
        // Добавляем анимацию ошибки
        field.style.animation = 'shake 0.5s';
        setTimeout(() => {
            field.style.animation = '';
        }, 500);
    }
}

// Анимация тряски для ошибочных полей
const style = document.createElement('style');
style.textContent = `
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }
`;
document.head.appendChild(style);

// Показ ошибок валидации
function showValidationErrors(errors) {
    // Создаем или обновляем блок с ошибками
    let errorDiv = document.querySelector('.validation-errors');
    
    if (!errorDiv) {
        errorDiv = document.createElement('div');
        errorDiv.className = 'alert error validation-errors';
        const form = document.querySelector('.book-form');
        form.parentNode.insertBefore(errorDiv, form);
    }
    
    let errorHtml = '<ul>';
    errors.forEach(error => {
        errorHtml += `<li>${error}</li>`;
    });
    errorHtml += '</ul>';
    
    errorDiv.innerHTML = errorHtml;
    errorDiv.style.display = 'block';
    
    // Прокрутка к ошибкам
    errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
    
    // Автоматическое скрытие через 5 секунд
    setTimeout(() => {
        errorDiv.style.opacity = '0';
        setTimeout(() => {
            errorDiv.style.display = 'none';
            errorDiv.style.opacity = '1';
        }, 500);
    }, 5000);
}

// Улучшенный поиск с debounce
function initSearchForm() {
    const searchInput = document.querySelector('.search input[name="q"]');
    const searchForm = document.querySelector('.search form');
    
    if (searchInput) {
        // Добавляем индикатор загрузки
        const spinner = document.createElement('div');
        spinner.className = 'spinner';
        spinner.style.display = 'none';
        spinner.style.width = '20px';
        spinner.style.height = '20px';
        spinner.style.margin = '0';
        
        const searchButton = document.querySelector('.search button');
        if (searchButton) {
            searchButton.parentNode.insertBefore(spinner, searchButton.nextSibling);
        }
        
        // Debounce функция для поиска
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            
            searchTimeout = setTimeout(() => {
                if (this.value.trim()) {
                    // Показываем спиннер
                    spinner.style.display = 'inline-block';
                    
                    // Имитация задержки загрузки (опционально)
                    setTimeout(() => {
                        searchForm.submit();
                    }, 300);
                }
            }, 500);
        });
        
        // Очистка поиска
        const clearButton = document.createElement('button');
        clearButton.type = 'button';
        clearButton.className = 'search-clear';
        clearButton.innerHTML = '✕';
        clearButton.style.cssText = `
            position: absolute;
            right: 60px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #999;
            cursor: pointer;
            font-size: 14px;
            display: ${searchInput.value ? 'block' : 'none'};
        `;
        
        searchInput.parentNode.style.position = 'relative';
        searchInput.parentNode.appendChild(clearButton);
        
        clearButton.addEventListener('click', function() {
            searchInput.value = '';
            this.style.display = 'none';
            searchInput.focus();
            
            // Если есть активный поиск, перезагружаем страницу без параметров
            if (window.location.search.includes('q=')) {
                window.location.href = '/library/';
            }
        });
        
        searchInput.addEventListener('input', function() {
            clearButton.style.display = this.value ? 'block' : 'none';
        });
    }
}

// Инициализация тултипов
function initTooltips() {
    const elements = document.querySelectorAll('[title]');
    
    elements.forEach(el => {
        const title = el.getAttribute('title');
        el.setAttribute('data-tooltip', title);
        el.removeAttribute('title');
    });
}

// Кнопки для работы с API
function initApiButtons() {
    // Кнопка для просмотра статистики
    const statsBtn = document.getElementById('show-stats');
    
    if (statsBtn) {
        statsBtn.addEventListener('click', function(e) {
            e.preventDefault();
            loadStats();
        });
    }
    
    // Кнопка для экспорта в JSON
    const exportBtn = document.getElementById('export-json');
    
    if (exportBtn) {
        exportBtn.addEventListener('click', function(e) {
            e.preventDefault();
            exportToJson();
        });
    }
}

// Загрузка статистики через API
function loadStats() {
    showLoading('Загрузка статистики...');
    
    fetch('/library/api/stats')
        .then(response => response.json())
        .then(data => {
            hideLoading();
            showStatsModal(data);
        })
        .catch(error => {
            hideLoading();
            console.error('Ошибка:', error);
            showError('Не удалось загрузить статистику');
        });
}

// Экспорт в JSON
function exportToJson() {
    showLoading('Подготовка данных...');
    
    fetch('/library/api/books')
        .then(response => response.json())
        .then(data => {
            hideLoading();
            
            const jsonString = JSON.stringify(data, null, 2);
            const blob = new Blob([jsonString], { type: 'application/json' });
            const url = URL.createObjectURL(blob);
            
            const a = document.createElement('a');
            a.href = url;
            a.download = `books_${new Date().toISOString().slice(0,10)}.json`;
            a.click();
            
            URL.revokeObjectURL(url);
        })
        .catch(error => {
            hideLoading();
            console.error('Ошибка:', error);
            showError('Не удалось экспортировать данные');
        });
}

// Показ модального окна со статистикой
function showStatsModal(stats) {
    // Создаем модальное окно
    const modal = document.createElement('div');
    modal.className = 'modal';
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        animation: fadeIn 0.3s;
    `;
    
    const content = document.createElement('div');
    content.style.cssText = `
        background: white;
        padding: 30px;
        border-radius: 20px;
        max-width: 500px;
        width: 90%;
        max-height: 80vh;
        overflow-y: auto;
        animation: slideUp 0.3s;
    `;
    
    // Формируем HTML для статистики
    let statsHtml = '<h2 style="margin-bottom:20px">📊 Статистика библиотеки</h2>';
    
    statsHtml += `<p><strong>Всего книг:</strong> ${stats.total}</p>`;
    
    if (stats.top_authors && stats.top_authors.length) {
        statsHtml += '<h3 style="margin:15px 0">Топ авторы:</h3><ul>';
        stats.top_authors.forEach(author => {
            statsHtml += `<li>${author.author}: ${author.count} книг</li>`;
        });
        statsHtml += '</ul>';
    }
    
    if (stats.by_genre && stats.by_genre.length) {
        statsHtml += '<h3 style="margin:15px 0">Книги по жанрам:</h3><ul>';
        stats.by_genre.forEach(genre => {
            statsHtml += `<li>${genre.genre || 'Без жанра'}: ${genre.count}</li>`;
        });
        statsHtml += '</ul>';
    }
    
    if (stats.years) {
        statsHtml += `<p><strong>Годы изданий:</strong> ${stats.years.min_year || '??'} - ${stats.years.max_year || '??'}</p>`;
    }
    
    statsHtml += '<button onclick="this.closest(\'.modal\').remove()" style="margin-top:20px;padding:10px 20px;background:#667eea;color:white;border:none;border-radius:5px;cursor:pointer">Закрыть</button>';
    
    content.innerHTML = statsHtml;
    modal.appendChild(content);
    document.body.appendChild(modal);
    
    // Закрытие по клику вне модального окна
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.remove();
        }
    });
}

// Показ загрузки
function showLoading(message = 'Загрузка...') {
    const loader = document.createElement('div');
    loader.id = 'global-loader';
    loader.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255,255,255,0.8);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        z-index: 2000;
    `;
    
    loader.innerHTML = `
        <div class="spinner"></div>
        <p style="margin-top:20px;color:#333">${message}</p>
    `;
    
    document.body.appendChild(loader);
}

// Скрытие загрузки
function hideLoading() {
    const loader = document.getElementById('global-loader');
    if (loader) {
        loader.remove();
    }
}

// Показ ошибки
function showError(message) {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'alert error';
    errorDiv.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1500;
        min-width: 300px;
    `;
    errorDiv.textContent = message;
    
    document.body.appendChild(errorDiv);
    
    setTimeout(() => {
        errorDiv.style.opacity = '0';
        setTimeout(() => {
            errorDiv.remove();
        }, 500);
    }, 5000);
}

// Подтверждение удаления
function confirmDelete(bookTitle, bookId) {
    if (confirm(`Вы уверены, что хотите удалить книгу "${bookTitle}"?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/library/books/delete/${bookId}`;
        document.body.appendChild(form);
        form.submit();
    }
}

// Копирование в буфер обмена
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showNotification('Скопировано!', 'success');
    }).catch(() => {
        showNotification('Ошибка копирования', 'error');
    });
}

// Показ уведомления
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert ${type}`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1500;
        min-width: 300px;
        animation: slideIn 0.3s;
    `;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => {
            notification.remove();
        }, 500);
    }, 3000);
}

// Добавляем анимацию для уведомлений
const notificationStyle = document.createElement('style');
notificationStyle.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
`;
document.head.appendChild(notificationStyle);

// Экспортируем функции для глобального использования
window.confirmDelete = confirmDelete;
window.copyToClipboard = copyToClipboard;
window.showNotification = showNotification;