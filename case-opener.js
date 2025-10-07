import { caseItems, populateItemsGrid } from './case.js';

let currentCaseType = '';
let isSpinning = false;

// Инициализация модального окна
export function initCaseOpener() {
    const modal = document.getElementById('caseModal');
    const backBtn = document.getElementById('backBtn');
    const closeModalBtn = document.getElementById('closeModal');
    const openCaseBtn = document.getElementById('openCaseBtn');
    const claimBtn = document.getElementById('claimBtn');

    // Закрытие модалки
    [backBtn, closeModalBtn].forEach(btn => {
        btn.addEventListener('click', closeModal);
    });

    // Закрытие по клику вне
    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });

    // Открыть кейс
    openCaseBtn.addEventListener('click', startRoulette);

    // Забрать награду
    claimBtn.addEventListener('click', () => {
        alert(`🎉 You claimed your reward!`);
        closeModal();
    });

    // Обработчик навигации
    document.querySelectorAll('.nav-item').forEach(item => {
        item.addEventListener('click', () => {
            document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));
            item.classList.add('active');
        });
    });

    // Обработчики на карточки кейсов
    document.querySelectorAll('.case-card').forEach(card => {
        card.addEventListener('click', () => {
            const caseType = card.getAttribute('data-case');
            openCaseModal(caseType);
        });
    });

    // Обработчики на кнопки "Open for free"
    document.querySelectorAll('.btn-open').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const caseCard = btn.closest('.case-card');
            const caseType = caseCard.getAttribute('data-case');
            openCaseModal(caseType);
        });
    });
}

// Открыть модальное окно кейса
function openCaseModal(caseType) {
    currentCaseType = caseType;
    const modal = document.getElementById('caseModal');
    const title = document.getElementById('modalTitle');
    const dailyInfo = document.getElementById('dailyInfo');
    const rouletteWheel = document.getElementById('rouletteWheel');

    // Очистка
    rouletteWheel.innerHTML = '';
    populateItemsGrid(caseType);

    // Установка заголовка
    switch (caseType) {
        case 'promocode':
            title.textContent = 'Promocode Box';
            dailyInfo.style.display = 'none';
            break;
        case 'daily':
            title.textContent = 'Daily';
            dailyInfo.style.display = 'block';
            break;
        case 'deposit1k':
        case 'deposit2k':
        case 'deposit5k':
        case 'deposit10k':
            title.textContent = caseType.replace('deposit', 'Deposit ').toUpperCase();
            dailyInfo.style.display = 'none';
            break;
    }

    // Заполнение рулетки
    const items = caseItems[caseType] || caseItems.daily;
    items.forEach(item => {
        const slot = document.createElement('div');
        slot.className = 'roulette-slot';
        slot.innerHTML = `<img src="${item.img}" alt="${item.name}" title="${item.name}">`;
        rouletteWheel.appendChild(slot);
    });

    // Сброс результата
    document.getElementById('resultBox').style.display = 'none';
    isSpinning = false;

    // Показать модалку
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

// Закрыть модалку
function closeModal() {
    document.getElementById('caseModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    isSpinning = false;
}

// Запуск рулетки
function startRoulette() {
    if (isSpinning) return;

    isSpinning = true;
    const rouletteWheel = document.getElementById('rouletteWheel');
    const items = caseItems[currentCaseType] || caseItems.daily;
    const totalItems = items.length;

    // Случайный угол остановки
    const spinCount = 5;
    const spinDistance = spinCount * 360 + Math.floor(Math.random() * 360);
    const spinDuration = 3000;

    // Анимация
    rouletteWheel.style.transition = `transform ${spinDuration}ms cubic-bezier(0.2, 0.8, 0.3, 1)`;
    rouletteWheel.style.transform = `rotate(${spinDistance}deg)`;

    // Остановка и показ результата
    setTimeout(() => {
        isSpinning = false;

        const randomIndex = Math.floor(Math.random() * totalItems);
        const reward = items[randomIndex];

        showResult(reward);
    }, spinDuration);
}

// Показать результат
function showResult(reward) {
    const resultBox = document.getElementById('resultBox');
    const resultImage = document.getElementById('resultImage');
    const resultName = document.getElementById('resultName');
    const resultPrice = document.getElementById('resultPrice');

    resultImage.src = reward.img;
    resultName.textContent = reward.name;
    resultPrice.textContent = `${reward.price} `;

    resultBox.style.display = 'block';
    resultBox.style.opacity = '0';
    resultBox.style.transform = 'scale(0.8)';

    setTimeout(() => {
        resultBox.style.transition = 'all 0.5s ease-out';
        resultBox.style.opacity = '1';
        resultBox.style.transform = 'scale(1)';
    }, 10);
}

// Инициализируем при загрузке
document.addEventListener('DOMContentLoaded', initCaseOpener);