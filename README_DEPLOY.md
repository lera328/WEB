# Деплой на Render.com

## 🚀 Быстрый старт:

### 1. Создайте аккаунт на [Render.com](https://render.com)

### 2. Fork этого репозитория
```bash
git clone https://github.com/yourusername/site.git
cd site
```

### 3. Создайте Web Service на Render
- Тип: **Web Service**
- Репозиторий: ваш fork
- Build Command: `php -v`
- Start Command: `php -S 0.0.0.0:$PORT -t .`
- Instance Type: **Free**

### 4. Настройте переменные окружения
В Render Dashboard → Environment Variables добавьте:
```
YANDEX_CLIENT_ID=ваш_client_id
YANDEX_CLIENT_SECRET=ваш_client_secret
```

### 5. Настройка Яндекс ID
После деплоя у вас будет URL вида: `https://yourapp.onrender.com`

В Яндекс ID укажите:
- **Suggest Hostname**: `yourapp.onrender.com`
- **Redirect URI**: `https://yourapp.onrender.com/yandex_callback.php`

## 📁 Структура проекта:
```
site/
├── auth.php                 # Авторизация
├── admin.php                # Админ-панель
├── manage_products.php      # Управление товарами
├── edit_product.php         # Редактирование
├── add_product.php          # Добавление
├── yandex_callback.php      # OAuth callback
├── config_production.php    # Конфиг продакшена
├── config_yandex.php        # Конфиг для локалки
├── style.css                # Стили
├── Procfile                 # Конфиг Render
└── update_database.sql      # SQL для БД
```

## 🗄️ База данных:
Render предоставляет PostgreSQL, но нам нужна MySQL.
Используйте бесплатные сервисы:
- [PlanetScale](https://planetscale.com/) (MySQL)
- [Railway](https://railway.app/) (MySQL)
- [Aiven](https://aiven.io/) (бесплатный tier)

## 🔧 Альтернативные хостинги:

### InfinityFree (бесплатно):
- PHP + MySQL бесплатно
- Поддомен: `yourname.infinityfree.com`
- FTP доступ

### 000webhost (бесплатно):
- PHP + MySQL бесплатно
- Поддомен: `yourname.000webhostapp.com`
- Панель управления

### Timeweb (платно):
- Надежный российский хостинг
- ~150 руб/месяц
- Поддержка 24/7
