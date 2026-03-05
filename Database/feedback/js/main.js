// Дополнительная валидация на клиенте
document.getElementById('feedbackForm').addEventListener('submit', function(e) {
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const message = document.getElementById('message_text').value.trim();
    
    let errors = [];
    
    if (!name) {
        errors.push('Укажите имя');
    }
    
    if (!email) {
        errors.push('Укажите email');
    } else if (!isValidEmail(email)) {
        errors.push('Укажите корректный email');
    }
    
    if (!message) {
        errors.push('Напишите сообщение');
    }
    
    if (errors.length > 0) {
        e.preventDefault();
        showMessage(errors.join('<br>'), 'error');
    }
});

function isValidEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function showMessage(text, type) {
    const messageDiv = document.getElementById('message');
    messageDiv.className = 'message ' + type;
    messageDiv.innerHTML = text;
    messageDiv.style.display = 'block';
    
    setTimeout(() => {
        messageDiv.style.display = 'none';
    }, 5000);
}