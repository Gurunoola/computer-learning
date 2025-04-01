# Laravel Base Restful API
This is the official repository [Let’s Build a Base Restful API Using Laravel]()
<br>

## Usage <br>
Setup the repository <br>
```
git clone
cd <project-name>
composer install
cp .env.example .env 
php artisan key:generate
php artisan cache:clear && php artisan config:clear 
php artisan serve 
```
# Laravel Project

## 📌 Prerequisites
Ensure you have the following installed before setting up the project:
- PHP 8.x
- Composer
- MySQL / PostgreSQL / SQLite (as per your configuration)
- Laravel 10.x
- Node.js (for frontend dependencies, if applicable)

## ⚙️ Setup & Configuration

### 1️⃣ Clone the Repository
```sh
git clone https://github.com/your-repo.git
cd your-repo
```

### 2️⃣ Install Dependencies
```sh
composer install
```

### 3️⃣ Configure Environment Variables
```sh
cp .env.example .env
```
Update the `.env` file with your database credentials and other configurations.

### 4️⃣ Generate Application Key
```sh
php artisan key:generate
```

### 5️⃣ Run Database Migrations & Seeders
```sh
php artisan migrate --seed
```

---

## 🚀 How to Start the Service
```sh
php artisan serve
```
This will start the Laravel development server at `http://127.0.0.1:8000`.

If you are using Laravel Sail (Docker):
```sh
./vendor/bin/sail up
```

---

## 📦 How to Add a New Model
```sh
php artisan make:model ModelName -mcr
```
This command will create:
- **Model** (`app/Models/ModelName.php`)
- **Migration** (`database/migrations/xxxx_xx_xx_xxxxxx_create_model_names_table.php`)
- **Controller** (`app/Http/Controllers/ModelNameController.php`)
- **Resource** (`app/Http/Resources/ModelNameResource.php`)

Modify the migration file as needed and run:
```sh
php artisan migrate
```

---

## 📌 How to Run Migrations

### Run All Migrations
```sh
php artisan migrate
```

### Run a Specific Migration
```sh
php artisan migrate --path=database/migrations/2024_01_01_000000_create_example_table.php
```

### Rollback Migrations
```sh
php artisan migrate:rollback
```

### Rollback a Specific Migration
```sh
php artisan migrate:rollback --step=1
```

### Refresh All Migrations (⚠️ This will delete all tables and re-run migrations)
```sh
php artisan migrate:refresh
```

---

## 🔄 How to Clear Cache

### Clear All Caches
```sh
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Clear Individual Cache
#### Config Cache
```sh
php artisan config:clear
```
#### Route Cache
```sh
php artisan route:clear
```
#### View Cache
```sh
php artisan view:clear
```

If you want to rebuild config and route caches after clearing them:
```sh
php artisan config:cache
php artisan route:cache
```

---

## 🎯 Useful Commands
- **Check Laravel Version:** `php artisan --version`
- **List All Routes:** `php artisan route:list`
- **Create a Seeder:** `php artisan make:seeder SeederName`
- **Run a Seeder:** `php artisan db:seed --class=SeederName`
- **Tinker (Interactive Shell):** `php artisan tinker`

---

## 🛠 Troubleshooting

### Database Table Not Found Error
If you encounter an error like `Base table or view not found`, ensure you have migrated your database:
```sh
php artisan migrate
```

### Configurations Not Updating
If changes in `.env` or config files are not reflecting, try clearing the cache:
```sh
php artisan config:clear
```

---

## 📜 License
This project is licensed under the [MIT License](LICENSE).

