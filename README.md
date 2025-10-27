# Laravel Vue CRM

Простая CRM система для управления клиентами, сделками и задачами.

## Что это

Базовый CRM на Laravel + Vue.js. Можно добавлять клиентов, создавать сделки и ставить задачи. Авторизация через API токены (Laravel Sanctum).

## Требования

Нужно установить:
- Docker и Docker Compose (рекомендуется)
- Node.js 18+ и npm

Либо без Docker:
- PHP 8.2+ и Composer
- PostgreSQL или SQLite

## Запуск с Docker

```bash
git clone <repository-url>
cd laravel-vue-crm
```

Проверьте что файл `.env` существует и содержит настройки для PostgreSQL:

```env
DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=laravel
```

Запустите контейнеры:

```bash
docker-compose up -d
```

Выполните миграции:

```bash
docker-compose exec app php artisan migrate:fresh --seed
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
```

Установите npm пакеты и запустите фронтенд:

```bash
npm install
npm run dev
```

Откройте http://localhost:5174 в браузере.

Для входа используйте:
- Email: test@test.com
- Password: password

## Полезные команды Docker

```bash
docker-compose ps                                            # статус
docker-compose logs -f app                                   # логи
docker-compose down                                          # остановить
docker-compose restart                                       # перезапустить
docker-compose exec app php artisan migrate:fresh --seed    # пересоздать БД
```

## Запуск без Docker

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Настройте БД в `.env`. Для SQLite:

```bash
touch database/database.sqlite
```

```env
DB_CONNECTION=sqlite
DB_DATABASE=/полный/путь/к/database.sqlite
```

Для PostgreSQL:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=laravel_crm
DB_USERNAME=postgres
DB_PASSWORD=password
```

Выполните миграции:

```bash
php artisan migrate:fresh --seed
```

Запустите серверы в двух терминалах:

```bash
# Терминал 1
php artisan serve --port=8000

# Терминал 2
npm run dev
```

Откройте http://localhost:5173

## Структура

```
app/Http/Controllers/Api/  - API контроллеры
app/Models/                - модели
app/Policies/              - правила доступа
resources/js/components/   - Vue компоненты
resources/js/router/       - маршруты Vue
database/migrations/       - миграции
database/seeders/          - тестовые данные
```

## Порты

- 5174 - фронтенд (Vite)
- 8080 - API (Docker)
- 8000 - API (без Docker)
- 5433 - PostgreSQL (Docker)

## Прокси

В `vite.config.js` настроен прокси для перенаправления `/api` запросов на бэкенд:

```javascript
proxy: {
    '/api': {
        target: 'http://localhost:8080',
        changeOrigin: true,
    },
}
```

Это нужно чтобы фронтенд мог общаться с бэкендом без CORS проблем.

## API

Все эндпоинты (кроме login/register) требуют Bearer token в заголовке Authorization.

```
POST /api/register
POST /api/login
POST /api/logout

GET    /api/clients
POST   /api/clients
GET    /api/clients/{id}
PUT    /api/clients/{id}
DELETE /api/clients/{id}

GET    /api/deals
POST   /api/deals
GET    /api/deals/{id}
PUT    /api/deals/{id}
DELETE /api/deals/{id}

GET    /api/tasks
POST   /api/tasks
GET    /api/tasks/{id}
PUT    /api/tasks/{id}
DELETE /api/tasks/{id}
```

## Частые проблемы

**Ошибка 500:**
```bash
docker-compose exec app php artisan migrate
docker-compose exec app php artisan config:clear
docker-compose restart
```

**Ошибка 401 (не авторизован):**
Войдите через страницу логина. Проверьте что токен сохранен в localStorage (DevTools → Application → Local Storage).

**Database file at path [laravel] does not exist:**
Проверьте `.env` - должно быть `DB_CONNECTION=pgsql` и правильные данные для подключения к PostgreSQL.

**Docker не запускается:**
```bash
docker-compose ps        # проверить статус
docker-compose up -d     # запустить
docker-compose logs -f   # посмотреть логи
```

**Vite не запускается:**
```bash
rm -rf node_modules package-lock.json
npm install
npm run dev
```

**CORS ошибки:**
Убедитесь что запущен `npm run dev` и в `vite.config.js` настроен прокси на `http://localhost:8080`.

## Тестирование API

Примеры запросов через curl:

```bash
# Регистрация
curl -X POST http://localhost:8080/api/register \
  -H "Content-Type: application/json" \
  -d '{"name":"User","email":"user@test.com","password":"password"}'

# Логин
curl -X POST http://localhost:8080/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@test.com","password":"password"}'

# Получить клиентов (нужен токен)
curl http://localhost:8080/api/clients \
  -H "Authorization: Bearer YOUR_TOKEN"

# Добавить клиента
curl -X POST http://localhost:8080/api/clients \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{"name":"Company","email":"test@test.com","phone":"123","notes":"note"}'
```

## Технологии

- Laravel 12
- Vue.js 3 + Vuetify 3
- PostgreSQL 15
- Laravel Sanctum
- Vite, Docker
