document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('feedbackModal');
    const btn = document.getElementById('feedbackBtn');
    const span = document.getElementsByClassName('close')[0];
    const form = document.getElementById('feedbackForm');
    const messageDiv = document.getElementById('formMessage');

    // Открытие модального окна
    btn.onclick = function() {
        modal.style.display = 'block';
    }

    // Закрытие модального окна
    span.onclick = function() {
        modal.style.display = 'none';
        messageDiv.style.display = 'none';
        messageDiv.textContent = '';
    }

    // Закрытие при клике вне окна
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
            messageDiv.style.display = 'none';
            messageDiv.textContent = '';
        }
    }

    // Обработка отправки формы
    form.onsubmit = async function(e) {
        e.preventDefault();
        
        // Показать сообщение о загрузке
        showMessage('Отправка данных...', 'info');
        
        const formData = new FormData(form);
        
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData
            });
            
            // Проверка HTTP статуса
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            
            const result = await response.json();
            
            if (result.success) {
                showMessage('Сообщение успешно отправлено! Мы свяжемся с вами в ближайшее время.', 'success');
                form.reset();
                setTimeout(() => {
                    modal.style.display = 'none';
                    messageDiv.style.display = 'none';
                    messageDiv.textContent = '';
                }, 3000);
            } else {
                showMessage('Ошибка: ' + result.message, 'error');
            }
        } catch (error) {
            console.error('Fetch error:', error);
            
            // Более информативные сообщения об ошибках
            if (error.name === 'TypeError') {
                showMessage('Ошибка подключения к серверу. Проверьте интернет-соединение.', 'error');
            } else {
                showMessage('Произошла ошибка: ' + error.message, 'error');
            }
        }
    }

    // Функция показа сообщений
    function showMessage(text, type) {
        messageDiv.textContent = text;
        messageDiv.style.display = 'block';
        
        switch(type) {
            case 'success':
                messageDiv.style.backgroundColor = '#d4edda';
                messageDiv.style.color = '#155724';
                messageDiv.style.borderLeft = '4px solid #28a745';
                break;
            case 'error':
                messageDiv.style.backgroundColor = '#f8d7da';
                messageDiv.style.color = '#721c24';
                messageDiv.style.borderLeft = '4px solid #dc3545';
                break;
            case 'info':
                messageDiv.style.backgroundColor = '#cce5ff';
                messageDiv.style.color = '#004085';
                messageDiv.style.borderLeft = '4px solid #007bff';
                break;
        }
    }
});