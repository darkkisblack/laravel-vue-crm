# Laravel Vue CRM

Простая CRM система для управления клиентами, сделками и задачами.

## Что это

Базовый CRM на Laravel + Vue.js. Можно добавлять клиентов, создавать сделки и ставить задачи.

## Запуск

### С Docker

```bash
docker-compose up -d
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate
```

Откроется на http://localhost:8080

### Без Docker

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

## API

Все эндпоинты требуют авторизации через Bearer token.

**Аутентификация:**
- `POST /api/register` - регистрация
- `POST /api/login` - вход  
- `POST /api/logout` - выход

**Клиенты:**
- `GET /api/clients` - список
- `POST /api/clients` - создать
- `GET /api/clients/{id}` - получить
- `PUT /api/clients/{id}` - обновить
- `DELETE /api/clients/{id}` - удалить

**Сделки:**
- `GET /api/deals` - список
- `POST /api/deals` - создать
- `GET /api/deals/{id}` - получить
- `PUT /api/deals/{id}` - обновить
- `DELETE /api/deals/{id}` - удалить

**Задачи:**
- `GET /api/tasks` - список
- `POST /api/tasks` - создать
- `GET /api/tasks/{id}` - получить
- `PUT /api/tasks/{id}` - обновить
- `DELETE /api/tasks/{id}` - удалить

## Структура

- `app/Http/Controllers/Api/` - API контроллеры
- `app/Models/` - модели
- `app/Policies/` - авторизация
- `database/migrations/` - миграции

## Технологии

- Laravel 12
- Vue.js 3
- Vuetify 3
- PostgreSQL
- Docker
