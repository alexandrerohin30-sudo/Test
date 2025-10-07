// Модальное окно — общие обработчики (уже в case-opener.js, но оставим для безопасности)
document.addEventListener('DOMContentLoaded', () => {
    // Закрытие по крестику в шапке
    document.querySelector('.close-btn').addEventListener('click', () => {
        alert('You closed the app!');
    });

    // Анимация при наведении на карточки
    document.querySelectorAll('.item-card').forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'scale(1.05)';
        });
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'scale(1)';
        });
    });

    // Кнопка "Deposit"
    document.querySelector('.btn-deposit').addEventListener('click', () => {
        alert('💰 Deposit functionality will be added later!');
    });
});