# 🆓 Настройка InfinityFree + MySQL

## 🚀 Пошаговая инструкция

### Шаг 1: Регистрация на InfinityFree
1. Перейдите на [infinityfree.com](https://infinityfree.com)
2. Нажмите **"Sign Up"**
3. Заполните форму регистрации
4. Подтвердите email

### Шаг 2: Создание сайта
1. В Dashboard нажмите **"Create New Website"**
2. Выберите **"Upload Own Website"**
3. Выберите поддомен (например, `lerashop.infinityfree.com`)
4. Нажмите **"Create Website"**

### Шаг 3: Получение данных MySQL
1. В Dashboard найдите ваш сайт
2. Перейдите в **"MySQL Databases"**
3. Нажмите **"Create Database"**
4. Дайте имя: `ACCESSORIES`
5. После создания вы увидите данные подключения:
```
Hostname: sqlXXX.epizy.com
Database: epiz_XXX_ACCESSORIES
Username: epiz_XXX
Password: (показан в интерфейсе)
```

### Шаг 4: Загрузка файлов на InfinityFree
1. В Dashboard вашего сайта нажмите **"File Manager"**
2. Удалите все файлы по умолчанию
3. Нажмите **"Upload"**
4. Загрузите все файлы из папки `D:\site` КРОМЕ:
   - `.git` папки
   - `Procfile`
   - `Dockerfile`
   - `runtime.txt`
   - `README_DEPLOY.md`
   - `YANDEX_SETUP.md`
   - `DATABASE_SETUP.md`

### Шаг 5: Настройка конфигурации БД
1. Откройте `database_config_infinityfree.php`
2. Замените плейсхолдеры на ваши данные:
```php
$db_host = 'sqlXXX.epizy.com'; // Ваш hostname
$db_name = 'epiz_XXX_ACCESSORIES'; // Ваше имя БД
$db_user = 'epiz_XXX'; // Ваш username
$db_password = 'ваш_пароль'; // Ваш пароль
```
3. Переименуйте файл в `database_config.php`

### Шаг 6: Создание таблиц в БД
1. В InfinityFree Dashboard → **"MySQL Databases"**
2. Нажмите **"phpMyAdmin"**
3. Выберите вашу базу данных
4. Перейдите в **"SQL"** таб
5. Вставьте и выполните:
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

### Шаг 7: Настройка Яндекс ID
1. Перейдите на [oauth.yandex.ru/client/new](https://oauth.yandex.ru/client/new)
2. В **Redirect URI** укажите:
   ```
   https://lerashop.infinityfree.com/yandex_callback.php
   ```
3. В **Suggest Hostname** укажите:
   ```
   lerashop.infinityfree.com
   ```
4. Получите Client ID и Client Secret
5. Создайте `config_yandex.php` с вашими данными

### Шаг 8: Обновите все PHP файлы
Замените в каждом PHP файле:
```php
require_once 'database_config.php';
```

### Шаг 9: Проверка работы
1. Откройте ваш сайт: `https://lerashop.infinityfree.com`
2. Проверьте авторизацию
3. Проверьте админ-панель
4. Проверьте управление товарами

## 🎯 Результат
- ✅ Полностью бесплатный хостинг
- ✅ MySQL база данных
- ✅ HTTPS автоматически
- ✅ Поддомен вашего сайта
- ✅ Полная функциональность

## ⚠️ Важно
- InfinityFree имеет лимиты (1000MB хранилища, 40000 hits/месяц)
- Для небольших проектов этого достаточно
- Регулярно делайте бэкапы через cPanel

## 📞 Поддержка
Если возникнут проблемы:
1. Проверьте права доступа к файлам (755 для папок, 644 для файлов)
2. Проверьте данные подключения к БД
3. Посмотрите логи ошибок в cPanel
