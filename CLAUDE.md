# CLAUDE.md — AI Assistant Guide for MoviesApp

## Project Overview

MoviesApp is a **Yii 2 Basic** PHP web application for managing a personal collection of movies, books, and authors. It supports user authentication (via `dektrium/yii2-user`), role-based access control (RBAC), file uploads for covers/posters, commenting, and ratings.

- **Language**: PHP (>=5.4.0)
- **Framework**: Yii 2 (`yiisoft/yii2`)
- **Database**: MySQL (Active Record ORM)
- **UI**: Bootstrap + AdminLTE theme (`dmstr/yii2-adminlte-asset`)
- **Auth**: `dektrium/yii2-user` + `yii2mod/yii2-rbac`
- **Testing**: Codeception + PHPUnit
- **Language of UI/commit messages**: Brazilian Portuguese (pt-BR)

---

## Repository Structure

```
moviesapp/
├── assets/             # Asset bundles (AppAsset.php registers CSS/JS)
├── commands/           # Console commands (yii console entry point)
├── config/
│   ├── web.php         # Main web app config (routes, modules, components)
│   ├── console.php     # Console app config
│   ├── params.php      # Shared parameters (adminEmail, etc.)
│   ├── db.php          # Database credentials — GITIGNORED, create locally
│   ├── test.php        # Test environment overrides
│   └── test_db.php     # Test database config
├── controllers/        # Web controllers (one per resource)
├── migrations/         # Database migrations (run with `./yii migrate`)
├── models/             # Active Record models + Search models + Form models
├── tests/
│   ├── unit/           # PHPUnit unit tests
│   ├── functional/     # Codeception functional tests
│   └── acceptance/     # Codeception acceptance tests (requires Selenium)
├── views/
│   ├── layouts/        # main.php, header.php, left.php, content.php
│   ├── filme/          # Movie CRUD views
│   ├── livro/          # Book CRUD views
│   ├── autor/          # Author CRUD views
│   ├── site/           # Public pages (index, login, contact, about, error)
│   └── user/           # Overrides for dektrium/user module views
├── web/
│   ├── index.php       # Web entry point
│   ├── css/site.css    # Custom styles
│   └── uploads/        # User-uploaded images (filmes/, livros/)
├── widgets/            # Custom Yii widgets (Alert.php)
├── composer.json
├── docker-compose.yml
└── Vagrantfile
```

---

## Development Setup

### Prerequisites

- PHP >= 5.4.0 with extensions: `pdo_mysql`, `gd`, `mbstring`, `intl`
- MySQL
- Composer

### Local Installation

```bash
# Install PHP dependencies
composer install

# Create database config (not in git)
cp config/db.php.example config/db.php   # or create manually

# config/db.php template:
# <?php
# return [
#     'class' => 'yii\db\Connection',
#     'dsn' => 'mysql:host=localhost;dbname=yii2basic',
#     'username' => 'root',
#     'password' => '',
#     'charset' => 'utf8',
# ];

# Run migrations
./yii migrate

# Serve locally (PHP built-in server)
php -S localhost:8080 -t web
```

### Docker

```bash
docker-compose up -d
# App available at http://localhost:8000
```

---

## Key Conventions

### Models (`models/`)

There are three model categories:

| Type | Example | Purpose |
|------|---------|---------|
| Active Record | `Filme.php`, `Livro.php`, `Autor.php` | Map to DB tables, define validation rules and relations |
| Search models | `FilmeSearch.php`, `LivroSearch.php` | Extend base models, return `ActiveDataProvider` for grid filtering |
| Form models | `LoginForm.php`, `ContactForm.php` | Standalone form logic, no DB table |

**Conventions:**
- Table names are lowercase singular (e.g., `filme`, `livro`, `autor`)
- `Autor` uses non-standard PK: `id_autor` — always use `Autor::primaryKey()` when joining
- File uploads save to `web/uploads/{resource}/` with pattern `{titulo}{rand}.{ext}`
- Portuguese field names (e.g., `titulo`, `sinopse`, `assistido`, `capa`, `sobrenome`)
- `assistido` field on Filme = watched flag; on Livro = read flag (named confusingly)

### Controllers (`controllers/`)

All CRUD controllers follow the same pattern:

```php
public function behaviors()
{
    return [
        'access' => [
            'class' => AccessControl::class,
            'rules' => [['allow' => true, 'roles' => ['@']]],  // Auth required
        ],
        'verbs' => [
            'class' => VerbFilter::class,
            'actions' => ['delete' => ['POST']],
        ],
    ];
}
```

- All CRUD routes require authentication (`@` role)
- DELETE must be submitted via POST (CSRF-protected forms)
- `LivroController` has an additional `actionValidate()` for AJAX form validation returning JSON

### URL Rules

Defined in `config/web.php`:

```
autores           → autor/index
<controller>s     → <controller>/index   (pluralized listing routes)
<controller>/<id> → <controller>/view    (detail routes)
```

Pretty URLs are enabled. No trailing `.php`.

### Views (`views/`)

- Each resource has: `index.php`, `view.php`, `create.php`, `update.php`, `_form.php`, `_search.php`
- Layout: AdminLTE sidebar layout with `left.php` for navigation
- Alert flash messages rendered via custom `widgets/Alert.php`
- Rich text editor for descriptions: `2amigos/yii2-ckeditor-widget`

### File Uploads

- Images are stored in `web/uploads/filmes/` and `web/uploads/livros/`
- The `capa` attribute holds the filename only (not a full path)
- When displaying: prepend `/uploads/filmes/{capa}` to build the URL
- Validate file extensions in the model's `rules()` using `FileValidator`

---

## Database Schema

| Table | Key Columns |
|-------|-------------|
| `filme` | `id`, `capa`, `titulo`, `sinopse`, `link_imdb`, `assistido`, `data_criacao`, `data_edicao` |
| `livro` | `id`, `capa`, `titulo`, `autor_id`, `descricao`, `assistido`, `data_criacao`, `data_edicao` |
| `autor` | `id_autor`, `nome`, `sobrenome`, `biografia` |
| `user` | `id`, `username`, `email`, `password_hash`, `auth_key`, `status`, `created_at`, `updated_at` |
| `comentario_filme` | `filme_id`, `user_id`, `comentario`, `data_criacao` |
| `comentario_livro` | `livro_id`, `user_id`, `comentario`, `data_criacao` |

**Note:** `nota_filme` and `nota_livro` tables are referenced in model relations but migrations may not exist — verify before using.

### Migrations

```bash
./yii migrate          # Apply all pending migrations
./yii migrate/create   # Create a new migration
./yii migrate/down     # Roll back last migration
```

---

## Authentication & Authorization

- **User module**: `dektrium/yii2-user` handles registration, login, password reset
  - Registration is **disabled** (`enableRegistration: false`)
  - Email confirmation is **disabled** (`enableConfirmation: false`)
- **RBAC**: `yii2mod/yii2-rbac` manages roles/permissions via DB
  - `AuthManager` class: `yii\rbac\DbManager`
  - Manage permissions via: `./yii rbac/...` console commands
- **Access rules** in controllers use `roles: ['@']` (any authenticated user)

---

## Testing

### Setup

```bash
# Create test database
mysql -u root -e "CREATE DATABASE yii2_basic_tests;"

# Apply migrations to test DB
./yii_test migrate

# Run all tests
vendor/bin/codecept run
```

### Test Suites

```bash
vendor/bin/codecept run unit          # Unit tests (models, components)
vendor/bin/codecept run functional    # Functional tests (controllers, forms)
vendor/bin/codecept run acceptance    # Browser tests (requires Selenium)
```

### Existing Tests

- `tests/unit/UserTest.php` — Tests `User` model: `findIdentity()`, `findByUsername()`, auth key and password validation
- `tests/functional/LoginFormCest.php` — Login form workflow
- `tests/functional/ContactFormCest.php` — Contact form + email

**Conventions:**
- Unit tests use Codeception's `expect_that()` / `expect()` assertion style
- Fixtures: defined in `_support/` directories per suite
- Test DB: `yii2_basic_tests` (separate from development DB)

---

## Important Files to Know

| File | Purpose |
|------|---------|
| `config/web.php` | Central config: routes, modules, components, SMTP |
| `config/db.php` | DB credentials — NOT in git, create locally |
| `models/User.php` | Custom identity + auth methods |
| `models/Filme.php` | Main movie entity |
| `models/Livro.php` | Main book entity |
| `controllers/FilmeController.php` | Movie CRUD with file upload |
| `controllers/LivroController.php` | Book CRUD with AJAX validation |
| `web/index.php` | Application entry point |
| `migrations/` | Schema version control |

---

## Common Gotchas

1. **`config/db.php` is gitignored** — always create it manually on new environments.
2. **Autor's PK is `id_autor`**, not `id` — relations and queries must use this key explicitly.
3. **`assistido` on `Livro` means "read"** (lido), not "watched" — the field name is reused from the Filme model.
4. **File deletion on update is not automatic** — when replacing a cover image, manually delete the old file or it orphans in `web/uploads/`.
5. **Cookie validation key** in `config/web.php` is hardcoded — change this in production.
6. **SMTP credentials** in `config/web.php` are placeholders — configure before using contact/email features.
7. **dektrium/user module** views are overridden in `views/user/` — edit there, not in vendor.
8. **Acceptance tests require Selenium** — they are disabled by default and need additional setup.

---

## Extending the Application

### Adding a New Resource (e.g., `Serie`)

1. **Model**: Create `models/Serie.php` extending `yii\db\ActiveRecord`
2. **Search model**: Create `models/SerieSearch.php` extending `Serie`
3. **Controller**: Create `controllers/SerieController.php` with CRUD actions + access behaviors
4. **Views**: Create `views/serie/` with `index.php`, `view.php`, `create.php`, `update.php`, `_form.php`, `_search.php`
5. **Migration**: Add a migration for the `serie` table
6. **URL rules**: Add `'series' => 'serie/index'` in `config/web.php`
7. **Navigation**: Add link in `views/layouts/left.php`

### Adding a Migration

```bash
./yii migrate/create add_column_to_filme
# Edit the generated file in migrations/
./yii migrate
```

---

## Git Workflow

- Main branch: `master`
- Feature branches follow: `claude/<description>-<id>` naming
- Commit messages are in **Brazilian Portuguese**
- Always run tests before pushing: `vendor/bin/codecept run`

---

## Dependencies Summary

| Package | Purpose |
|---------|---------|
| `yiisoft/yii2` | Core framework |
| `yiisoft/yii2-bootstrap` | Bootstrap UI components |
| `dmstr/yii2-adminlte-asset` | AdminLTE admin theme |
| `dektrium/yii2-user` | User auth module |
| `yii2mod/yii2-rbac` | RBAC management |
| `2amigos/yii2-ckeditor-widget` | Rich text editor |
| `yiisoft/yii2-swiftmailer` | Email via SMTP |
| `yiisoft/yii2-jui` | jQuery UI widgets |
| `codeception/codeception` | Testing framework (dev) |
| `yiisoft/yii2-debug` | Debug toolbar (dev only) |
| `yiisoft/yii2-gii` | Code generator (dev only) |
