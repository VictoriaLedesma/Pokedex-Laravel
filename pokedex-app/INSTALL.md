# Installation Guide

## Quick Start (5 minutes)

### Prerequisites Check

Before starting, verify you have:

```bash
php --version  # Should be 8.2 or higher
composer --version  # Should be 2.x
```

If you don't have Composer installed, visit: https://getcomposer.org/download/

### Installation Steps

1. **Navigate to project directory**
```bash
cd pokedex-app
```

2. **Install PHP dependencies**
```bash
composer install
```

This will take a few minutes as it downloads all Laravel packages.

3. **Set up environment file**
```bash
# On Windows PowerShell:
copy env.example .env

# On Linux/Mac:
cp env.example .env
```

4. **Generate application key**
```bash
php artisan key:generate
```

5. **Create database file (SQLite)**
```bash
# On Windows PowerShell:
New-Item -Path database -Name database.sqlite -ItemType File

# On Linux/Mac:
touch database/database.sqlite
```

6. **Create storage directories**
```bash
# On Windows PowerShell:
New-Item -Path storage/framework/cache -ItemType Directory -Force
New-Item -Path storage/framework/sessions -ItemType Directory -Force
New-Item -Path storage/framework/views -ItemType Directory -Force
New-Item -Path storage/logs -ItemType Directory -Force

# On Linux/Mac:
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
```

7. **Set permissions (Linux/Mac only)**
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

8. **Start the development server**
```bash
php artisan serve
```

9. **Open in browser**
```
http://localhost:8000
```

## Alternative: Using MySQL/PostgreSQL

If you prefer using MySQL or PostgreSQL instead of SQLite:

### MySQL Setup

1. Create a database:
```sql
CREATE DATABASE pokedex CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Update `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pokedex
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### PostgreSQL Setup

1. Create a database:
```sql
CREATE DATABASE pokedex;
```

2. Update `.env`:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=pokedex
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

## Troubleshooting

### "composer: command not found"

**Solution**: Install Composer from https://getcomposer.org/download/

### "Class 'XXX' not found"

**Solution**: Run autoload dump
```bash
composer dump-autoload
```

### "Permission denied" on storage folders

**Solution** (Linux/Mac):
```bash
sudo chmod -R 775 storage
sudo chmod -R 775 bootstrap/cache
```

### Port 8000 already in use

**Solution**: Use a different port
```bash
php artisan serve --port=8080
```

### Cache issues

**Solution**: Clear all caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## Verification

To verify the installation was successful:

1. **Check homepage loads**
   - Visit `http://localhost:8000`
   - You should see a list of Pokémon

2. **Check search works**
   - Search for "pikachu" in the search bar
   - Should show Pikachu

3. **Check detail page**
   - Click on any Pokémon
   - Should show full details with stats

4. **Run tests**
```bash
php artisan test
```
All tests should pass ✓

## Configuration Options

You can customize the application by editing `.env`:

```env
# Cache duration for API responses (in seconds)
POKEAPI_CACHE_TTL=3600  # 1 hour (default)

# API request timeout (in seconds)
POKEAPI_TIMEOUT=30  # 30 seconds (default)

# Change app name
APP_NAME="My Custom Pokédex"

# Enable debug mode (development only!)
APP_DEBUG=true
```

## Next Steps

Once installed:

1. Read the [README.md](README.md) for feature overview
2. Read [ARCHITECTURE.md](ARCHITECTURE.md) for architecture deep dive
3. Explore the code structure in `/app`
4. Run tests: `php artisan test`

## Support

If you encounter issues:

1. Check this guide first
2. Clear caches: `php artisan cache:clear`
3. Regenerate autoload: `composer dump-autoload`
4. Check Laravel logs: `storage/logs/laravel.log`

## Production Deployment

For production deployment:

1. Set `APP_ENV=production` in `.env`
2. Set `APP_DEBUG=false` in `.env`
3. Optimize application:
```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

4. Set up proper web server (Nginx/Apache)
5. Configure HTTPS
6. Set up proper caching (Redis recommended)

---

**Happy coding! 🚀**

