# API и Админка для мобильного приложения «Блог»

REST API на Laravel 13 + административная панель на Orchid.

## Стек

- PHP 8.5 / Laravel 13
- MySQL 8.0
- Laravel Sanctum (токен-аутентификация)
- Laravel Orchid (административная панель)
- Docker (для базы данных или для всего проекта)

---

## Запуск полностью в Docker

Если нужно запустить всё (приложение + БД) в контейнерах:

```bash
docker-compose up --build
```

Администратор создаётся автоматически:

| Поле | Значение |
|---|---|
| Email | `admin@example.com` |
| Пароль | `password` |

Изменить данные можно в `docker-compose.yml` в переменных `ADMIN_NAME`, `ADMIN_EMAIL`, `ADMIN_PASSWORD`.

---

## Локальный запуск

### Требования

- PHP 8.5+
- Composer
- Docker

### 1. Запустить базу данных

```bash
docker-compose -f docker-compose.db.yml up -d
```

MySQL будет доступен на `localhost:3307`.

### 2. Установить зависимости

```bash
composer install
```

### 3. Настроить окружение

```bash
cp .env.example .env
php artisan key:generate
```

Убедитесь, что в `.env` указаны следующие параметры подключения к БД:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3307
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=secret
```

### 4. Выполнить миграции

```bash
php artisan migrate
```

### 5. Запустить сервер

```bash
php artisan serve
```

Приложение доступно на http://localhost:8000

---


## Административная панель

URL: http://localhost:8000/admin

Вход по email и паролю. Доступ только для пользователей с правами администратора.

### Создание администратора

```bash
php artisan orchid:admin admin admin@example.com password
```

### Возможности

- **Пользователи** — просмотр, создание, редактирование, удаление
- **Публикации** — просмотр, создание, редактирование, удаление
- **Роли и права** — управление доступом

---

## API

Базовый URL: `http://localhost:8000/api`

Все ответы в формате JSON.

### Авторизация

#### Регистрация
```
POST /api/auth/register
Content-Type: application/json

{
    "name": "Иван Иванов",
    "email": "ivan@example.com",
    "password": "password123"
}
```

Ответ `201`:
```json
{
    "accessToken": "1|abc123..."
}
```

#### Вход
```
POST /api/auth/login
Content-Type: application/json

{
    "email": "ivan@example.com",
    "password": "password123"
}
```

Ответ `200`:
```json
{
    "accessToken": "1|abc123..."
}
```

---

### Публикации

Все запросы требуют заголовок:
```
Authorization: Bearer <accessToken>
```

#### Создать публикацию
```
POST /api/posts
Content-Type: application/json

{
    "title": "Заголовок",
    "text": "Текст публикации"
}
```

#### Все публикации
```
GET /api/posts?limit=10&offset=0&sort_by=date&sort_order=desc&date_from=2024-01-01&date_to=2024-12-31
```

| Параметр | Тип | По умолчанию | Описание |
|---|---|---|---|
| `limit` | int | 10 | Кол-во записей (макс. 100) |
| `offset` | int | 0 | Сколько записей пропустить |
| `sort_by` | string | `date` | Сортировка: `date` или `title` |
| `sort_order` | string | `desc` | Порядок: `asc` или `desc` |
| `date_from` | date | — | Фильтр от даты |
| `date_to` | date | — | Фильтр до даты |

#### Мои публикации
```
GET /api/posts/my?limit=10&offset=0
```

Те же параметры фильтрации и сортировки.

---

## Структура проекта

```
app/
├── DTO/                        # Data Transfer Objects
├── Exceptions/                 # AuthException
├── Http/
│   ├── Controllers/Api/        # Тонкие контроллеры
│   ├── Requests/Api/           # Валидация + toDto()
│   ├── Requests/Admin/         # Валидация форм Orchid
│   └── Resources/              # JSON-сериализация
├── Models/                     # User, Post
├── Orchid/
│   ├── Layouts/Post/           # Таблица и форма публикаций
│   └── Screens/Post/           # Экраны управления публикациями
└── Services/                   # AuthService, PostService
```
