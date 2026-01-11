# 📁 Файлы для загрузки на InfinityFree

## ✅ Какие файлы загружать:

### Основные файлы:
- auth.php
- admin.php  
- index.php
- manage_products.php
- edit_product.php
- add_product.php
- yandex_callback.php
- logout.php
- Страница2.php
- index.html
- 1.html
- info.php
- script.js
- style.css
- update_database.sql

### Конфигурационные файлы:
- database_config.php
- config_production.php
- config_yandex.example.php (переименовать в config_yandex.php)

### Документация:
- README.md
- INFINITYFREE_SETUP.md

## ❌ Какие файлы НЕ загружать:

### Git файлы:
- .git/
- .gitignore

### Docker файлы (не нужны для InfinityFree):
- Procfile
- Dockerfile
- runtime.txt

### Документация для других хостингов:
- README_DEPLOY.md
- YANDEX_SETUP.md
- DATABASE_SETUP.md
- FREE_DATABASE.md

### Временные файлы:
- database_config_infinityfree.php (это был пример)

## 🚀 Порядок действий:

1. **Создайте аккаунт на InfinityFree**
2. **Создайте сайт и БД**
3. **Загрузите файлы из списка ✅**
4. **Настройте config_yandex.php**
5. **Обновите database_config.php** с вашими данными БД
6. **Создайте таблицы в phpMyAdmin**
7. **Настройте Яндекс ID**

## 📝 Важно:

- **config_yandex.example.php** → **config_yandex.php** (вставьте ваши Client ID и Secret)
- **database_config.php** → замените плейсхолдеры на реальные данные БД
- Все права доступа к файлам должны быть **644**, к папкам **755**
