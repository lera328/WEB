# Настройка базы данных для продакшена

## 🚀 Рекомендуемый вариант: PlanetScale (бесплатно)

### Шаг 1: Регистрация на PlanetScale
1. Перейдите на [planetscale.com](https://planetscale.com)
2. Создайте бесплатный аккаунт
3. Подтвердите email

### Шаг 2: Создание базы данных
1. В Dashboard нажмите **"Create database"**
2. Название: `ACCESSORIES` (как локальная)
3. Регион: выберите ближайший
4. Нажмите **"Create database"**

### Шаг 3: Создание таблицы
1. Откройте базу данных
2. Перейдите в **"Console"**
3. Выполните SQL из файла `update_database.sql`:
```sql
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    yandex_id VARCHAR(50) NULL UNIQUE,
    email VARCHAR(255) NULL,
    last_login DATETIME NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE accessories_ (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    picture TEXT,
    har1 TEXT,
    har2 TEXT,
    har3 TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Шаг 4: Получение данных для подключения
1. В PlanetScale перейдите в базу данных
2. Нажмите **"Connect"**
3. Выберите **"@planetScale/database"**
4. Скопируйте **HOSTNAME**, **USERNAME**, **PASSWORD**

### Шаг 5: Настройка в Render
В Environment Variables добавьте:
```
DB_HOST=aws.connect.psdb.cloud
DB_NAME=ACCESSORIES
DB_user=ваш_username
DB_PASSWORD=ваш_пароль
```

## 🔄 Альтернативные варианты:

### Railway (бесплатно)
1. [railway.app](https://railway.app)
2. New Project → Provision MySQL
3. Получите данные подключения

### Supabase (бесплатно)
1. [supabase.com](https://supabase.com)
2. New Project → Create Project
3. Settings → Database → Connection string

### Aiven (бесплатный tier)
1. [aiven.io](https://aiven.io)
2. Создайте MySQL сервис
3. Получите данные подключения

## 📋 Проверка подключения

После настройки БД проверьте:
1. База данных создана
2. Таблицы созданы
3. Переменные окружения добавлены в Render
4. Приложение может подключиться к БД

## 🔧 Тестирование

1. Зайдите на ваш сайт: `https://web-app.onrender.com`
2. Попробуйте авторизоваться
3. Проверьте логи в Render если есть ошибки

## ⚠️ Важно

- Никогда не храните пароли в коде
- Используйте Environment Variables
- Регулярно делайте бэкапы
- Проверьте права доступа к БД
