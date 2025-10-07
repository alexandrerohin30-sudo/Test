-- Используем уже созданную базу
USE a1160245_123;

-- Пользователи
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    telegram_id BIGINT UNIQUE NOT NULL,
    username VARCHAR(100),
    first_name VARCHAR(100),
    ref_code VARCHAR(8) UNIQUE NOT NULL,
    balance DECIMAL(10,2) DEFAULT 0.00,
    streak INT DEFAULT 0,
    last_visit DATE,
    joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_banned TINYINT(1) DEFAULT 0,
    INDEX idx_telegram (telegram_id),
    INDEX idx_ref_code (ref_code)
);

-- История открытий кейсов
CREATE TABLE IF NOT EXISTS case_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    case_name VARCHAR(100) NOT NULL,
    item_name VARCHAR(100) NOT NULL,
    rarity VARCHAR(50),
    value DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_created (created_at)
);

-- Рефералы
CREATE TABLE IF NOT EXISTS referrals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    referrer_id INT NOT NULL,
    referred_id INT NOT NULL,
    earnings DECIMAL(10,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (referrer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (referred_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_referral (referrer_id, referred_id)
);

-- Достижения
CREATE TABLE IF NOT EXISTS achievements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    achievement_id VARCHAR(50) NOT NULL,
    earned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_achievement (user_id, achievement_id)
);

-- Платежи (NOWPayments)
CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    amount_usd DECIMAL(10,2),
    amount_crypto DECIMAL(15,8),
    crypto_currency VARCHAR(10),
    payment_id VARCHAR(100) UNIQUE,
    status ENUM('pending', 'confirmed', 'failed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    confirmed_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_status (status),
    INDEX idx_payment_id (payment_id)
);

-- Администраторы
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Кейсы
CREATE TABLE IF NOT EXISTS cases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL,
    image_url VARCHAR(255),
    reward_base DECIMAL(10,2),
    is_free TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Предметы в кейсах
CREATE TABLE IF NOT EXISTS case_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    case_name VARCHAR(100) NOT NULL,
    item_name VARCHAR(100) NOT NULL,
    icon VARCHAR(10),
    rarity VARCHAR(50),
    chance DECIMAL(5,2) NOT NULL,
    FOREIGN KEY (case_name) REFERENCES cases(name) ON DELETE CASCADE
);

-- Пуш-уведомления
CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    message TEXT NOT NULL,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_sent TINYINT(1) DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);