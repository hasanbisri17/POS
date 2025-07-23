# Troubleshooting Guide

This guide covers common issues you might encounter when installing or running the POS System and their solutions.

## Table of Contents

1. [Installation Issues](#installation-issues)
2. [Database Issues](#database-issues)
3. [Permission Issues](#permission-issues)
4. [Web Server Issues](#web-server-issues)
5. [PHP Issues](#php-issues)
6. [Asset Loading Issues](#asset-loading-issues)
7. [Performance Issues](#performance-issues)
8. [Authentication Issues](#authentication-issues)
9. [File Upload Issues](#file-upload-issues)
10. [Email Issues](#email-issues)
11. [Backup and Recovery](#backup-and-recovery)
12. [Debugging Tools](#debugging-tools)

## Installation Issues

### Issue: Composer Install Fails

**Symptoms:**
- `composer install` command fails
- Memory limit errors
- Timeout errors

**Solutions:**

1. **Increase PHP memory limit:**
```bash
php -d memory_limit=2G composer install
```

2. **Use Composer with no-dev flag:**
```bash
composer install --no-dev --optimize-autoloader
```

3. **Clear Composer cache:**
```bash
composer clear-cache
composer install
```

4. **Update Composer:**
```bash
composer self-update
```

### Issue: NPM Install Fails

**Symptoms:**
- `npm install` command fails
- Node.js version errors
- Permission errors

**Solutions:**

1. **Update Node.js to version 18+:**
```bash
# Ubuntu/Debian
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs

# Check version
node --version
npm --version
```

2. **Clear NPM cache:**
```bash
npm cache clean --force
npm install
```

3. **Use different package manager:**
```bash
# Try with Yarn
npm install -g yarn
yarn install
```

### Issue: Laravel Key Generation Fails

**Symptoms:**
- `php artisan key:generate` fails
- APP_KEY not set error

**Solutions:**

1. **Manual key generation:**
```bash
# Generate key manually
php -r "echo 'base64:'.base64_encode(random_bytes(32)).PHP_EOL;"

# Add to .env file
APP_KEY=base64:your_generated_key_here
```

2. **Check file permissions:**
```bash
chmod 644 .env
php artisan key:generate
```

## Database Issues

### Issue: Database Connection Failed

**Symptoms:**
- "SQLSTATE[HY000] [2002] Connection refused"
- "Access denied for user"
- "Unknown database"

**Solutions:**

1. **Check MySQL service:**
```bash
# Ubuntu/Debian
sudo systemctl status mysql
sudo systemctl start mysql

# CentOS/RHEL
sudo systemctl status mysqld
sudo systemctl start mysqld
```

2. **Verify database credentials:**
```bash
# Test connection
mysql -u your_username -p your_database

# Check .env file
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pos_database
DB_USERNAME=pos_user
DB_PASSWORD=your_password
```

3. **Create database and user:**
```sql
CREATE DATABASE pos_database;
CREATE USER 'pos_user'@'localhost' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON pos_database.* TO 'pos_user'@'localhost';
FLUSH PRIVILEGES;
```

4. **Try different host:**
```env
# Instead of localhost, try:
DB_HOST=127.0.0.1
# Or for some hosting providers:
DB_HOST=mysql
```

### Issue: Migration Fails

**Symptoms:**
- "Table already exists" errors
- "Column not found" errors
- Migration timeout

**Solutions:**

1. **Reset migrations:**
```bash
php artisan migrate:reset
php artisan migrate --seed
```

2. **Fresh migration:**
```bash
php artisan migrate:fresh --seed
```

3. **Check database charset:**
```sql
ALTER DATABASE pos_database CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

4. **Increase timeout:**
```bash
php -d max_execution_time=300 artisan migrate
```

## Permission Issues

### Issue: Storage Permission Denied

**Symptoms:**
- "Permission denied" errors
- Cannot write to storage directory
- Log files not created

**Solutions:**

1. **Set correct permissions:**
```bash
# For VPS/Linux
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# For shared hosting
chmod -R 755 storage bootstrap/cache
```

2. **Check web server user:**
```bash
# Find web server user
ps aux | grep -E '(apache|nginx|httpd)'

# Set ownership accordingly
sudo chown -R webserver_user:webserver_group storage bootstrap/cache
```

3. **SELinux issues (CentOS/RHEL):**
```bash
# Check SELinux status
sestatus

# Set SELinux context
sudo setsebool -P httpd_can_network_connect 1
sudo chcon -R -t httpd_exec_t storage/
```

### Issue: File Upload Permission Denied

**Symptoms:**
- Cannot upload product images
- "Move uploaded file failed" error

**Solutions:**

1. **Check upload directory permissions:**
```bash
chmod -R 775 storage/app/public
chown -R www-data:www-data storage/app/public
```

2. **Create storage link:**
```bash
php artisan storage:link
```

3. **Check PHP upload settings:**
```ini
; In php.ini
upload_max_filesize = 10M
post_max_size = 10M
max_file_uploads = 20
```

## Web Server Issues

### Issue: 500 Internal Server Error

**Symptoms:**
- White screen with 500 error
- Server error in browser

**Solutions:**

1. **Check error logs:**
```bash
# Nginx
sudo tail -f /var/log/nginx/error.log

# Apache
sudo tail -f /var/log/apache2/error.log

# Laravel logs
tail -f storage/logs/laravel.log
```

2. **Check .htaccess file (Apache):**
```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

3. **Check PHP-FPM:**
```bash
sudo systemctl status php8.2-fpm
sudo systemctl restart php8.2-fpm
```

### Issue: 404 Not Found for Routes

**Symptoms:**
- Homepage works but other routes return 404
- Admin panel not accessible

**Solutions:**

1. **Enable mod_rewrite (Apache):**
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

2. **Check Nginx configuration:**
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

3. **Clear route cache:**
```bash
php artisan route:clear
php artisan route:cache
```

## PHP Issues

### Issue: PHP Version Incompatibility

**Symptoms:**
- "This package requires php ^8.2" error
- Syntax errors in modern PHP code

**Solutions:**

1. **Update PHP to 8.2+:**
```bash
# Ubuntu/Debian
sudo add-apt-repository ppa:ondrej/php
sudo apt update
sudo apt install php8.2

# CentOS/RHEL
sudo yum install php82
```

2. **Switch PHP version:**
```bash
# Ubuntu with multiple PHP versions
sudo update-alternatives --config php
```

### Issue: Missing PHP Extensions

**Symptoms:**
- "Class not found" errors
- Extension-specific errors

**Solutions:**

1. **Install required extensions:**
```bash
# Ubuntu/Debian
sudo apt install php8.2-mysql php8.2-xml php8.2-gd php8.2-curl php8.2-mbstring php8.2-zip php8.2-bcmath

# CentOS/RHEL
sudo yum install php82-mysql php82-xml php82-gd php82-curl php82-mbstring php82-zip php82-bcmath
```

2. **Check installed extensions:**
```bash
php -m | grep extension_name
```

### Issue: PHP Memory Limit

**Symptoms:**
- "Fatal error: Allowed memory size exhausted"
- Composer fails with memory errors

**Solutions:**

1. **Increase memory limit:**
```bash
# Temporarily
php -d memory_limit=512M artisan command

# Permanently in php.ini
memory_limit = 512M
```

2. **For Composer:**
```bash
COMPOSER_MEMORY_LIMIT=-1 composer install
```

## Asset Loading Issues

### Issue: CSS/JS Files Not Loading

**Symptoms:**
- Unstyled pages
- JavaScript not working
- 404 errors for assets

**Solutions:**

1. **Build assets:**
```bash
npm run build
```

2. **Check public path:**
```bash
# Ensure assets are in public directory
ls -la public/build/
```

3. **Clear view cache:**
```bash
php artisan view:clear
php artisan config:clear
```

4. **Check asset URL in .env:**
```env
APP_URL=https://yourdomain.com
ASSET_URL=https://yourdomain.com
```

### Issue: Images Not Displaying

**Symptoms:**
- Product images show broken links
- Uploaded files not accessible

**Solutions:**

1. **Create storage link:**
```bash
php artisan storage:link
```

2. **Check storage permissions:**
```bash
chmod -R 775 storage/app/public
```

3. **Verify storage configuration:**
```env
FILESYSTEM_DISK=public
```

## Performance Issues

### Issue: Slow Page Loading

**Symptoms:**
- Pages take long to load
- Database queries are slow
- High server resource usage

**Solutions:**

1. **Enable caching:**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

2. **Enable OPcache:**
```ini
; In php.ini
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=4000
```

3. **Optimize database:**
```sql
-- Add indexes to frequently queried columns
CREATE INDEX idx_products_category_id ON products(category_id);
CREATE INDEX idx_transactions_created_at ON transactions(created_at);
```

4. **Use queue for heavy tasks:**
```bash
php artisan queue:work
```

### Issue: High Memory Usage

**Symptoms:**
- Server runs out of memory
- PHP memory limit errors

**Solutions:**

1. **Optimize queries:**
```php
// Use pagination instead of loading all records
$products = Product::paginate(20);

// Use select to limit columns
$products = Product::select('id', 'name', 'price')->get();
```

2. **Clear unnecessary caches:**
```bash
php artisan cache:clear
php artisan view:clear
```

## Authentication Issues

### Issue: Cannot Login to Admin Panel

**Symptoms:**
- Login form doesn't work
- "Invalid credentials" error
- Redirected back to login

**Solutions:**

1. **Check default credentials:**
```
Email: admin@example.com
Password: password
```

2. **Reset admin password:**
```bash
php artisan tinker
```
```php
$user = App\Models\User::where('email', 'admin@example.com')->first();
$user->password = bcrypt('newpassword');
$user->save();
```

3. **Check session configuration:**
```env
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

4. **Clear sessions:**
```bash
php artisan session:clear
```

### Issue: CSRF Token Mismatch

**Symptoms:**
- "CSRF token mismatch" error
- Forms not submitting

**Solutions:**

1. **Check session configuration:**
```env
SESSION_DOMAIN=.yourdomain.com
SESSION_SECURE_COOKIE=true
```

2. **Clear cache:**
```bash
php artisan cache:clear
php artisan config:clear
```

## File Upload Issues

### Issue: File Upload Fails

**Symptoms:**
- "The file failed to upload" error
- Large files not uploading

**Solutions:**

1. **Check PHP upload limits:**
```ini
; In php.ini
upload_max_filesize = 10M
post_max_size = 10M
max_file_uploads = 20
max_execution_time = 300
```

2. **Check web server limits:**
```nginx
# Nginx
client_max_body_size 10M;
```

```apache
# Apache
LimitRequestBody 10485760
```

3. **Check storage permissions:**
```bash
chmod -R 775 storage/app/public
```

## Email Issues

### Issue: Emails Not Sending

**Symptoms:**
- Password reset emails not received
- Notification emails not working

**Solutions:**

1. **Check mail configuration:**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
```

2. **Test email configuration:**
```bash
php artisan tinker
```
```php
Mail::raw('Test email', function($msg) {
    $msg->to('test@example.com')->subject('Test');
});
```

3. **Check mail logs:**
```bash
tail -f storage/logs/laravel.log | grep mail
```

## Backup and Recovery

### Database Backup

```bash
# Create backup
mysqldump -u username -p database_name > backup_$(date +%Y%m%d_%H%M%S).sql

# Restore backup
mysql -u username -p database_name < backup_file.sql
```

### File Backup

```bash
# Create full backup
tar -czf pos_backup_$(date +%Y%m%d_%H%M%S).tar.gz /var/www/pos-system

# Restore backup
tar -xzf pos_backup_file.tar.gz -C /var/www/
```

### Automated Backup Script

```bash
#!/bin/bash
# backup.sh

BACKUP_DIR="/backups"
PROJECT_DIR="/var/www/pos-system"
DB_NAME="pos_database"
DB_USER="pos_user"
DB_PASS="your_password"
DATE=$(date +%Y%m%d_%H%M%S)

# Create backup directory
mkdir -p $BACKUP_DIR

# Database backup
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME > $BACKUP_DIR/db_backup_$DATE.sql

# Files backup
tar -czf $BACKUP_DIR/files_backup_$DATE.tar.gz $PROJECT_DIR

# Keep only last 7 days of backups
find $BACKUP_DIR -name "*.sql" -mtime +7 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +7 -delete

echo "Backup completed: $DATE"
```

## Debugging Tools

### Enable Debug Mode

```env
APP_DEBUG=true
APP_ENV=local
```

**⚠️ Warning:** Never enable debug mode in production!

### Laravel Telescope (Development)

```bash
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate
```

### Log Monitoring

```bash
# Real-time log monitoring
tail -f storage/logs/laravel.log

# Search for specific errors
grep -i "error" storage/logs/laravel.log

# Monitor web server logs
sudo tail -f /var/log/nginx/error.log
sudo tail -f /var/log/apache2/error.log
```

### Database Query Debugging

```php
// In your controller or model
DB::enableQueryLog();
// Your database operations
dd(DB::getQueryLog());
```

### Performance Profiling

```bash
# Install Debugbar for development
composer require barryvdh/laravel-debugbar --dev
```

## Getting Help

### Check System Status

```bash
# Check all services
sudo systemctl status nginx mysql php8.2-fpm

# Check disk space
df -h

# Check memory usage
free -h

# Check PHP configuration
php --ini
php -m
```

### Collect System Information

```bash
# Create system info file
echo "=== System Information ===" > system_info.txt
uname -a >> system_info.txt
php -v >> system_info.txt
mysql --version >> system_info.txt
nginx -v >> system_info.txt
echo "=== PHP Extensions ===" >> system_info.txt
php -m >> system_info.txt
echo "=== Disk Space ===" >> system_info.txt
df -h >> system_info.txt
echo "=== Memory Usage ===" >> system_info.txt
free -h >> system_info.txt
```

### Support Channels

1. **GitHub Issues**: https://github.com/hasanbisri17/POS/issues
2. **Documentation**: Check README.md and installation guides
3. **Laravel Community**: https://laravel.com/docs
4. **Stack Overflow**: Tag your questions with `laravel` and `pos-system`

### Before Asking for Help

1. Check this troubleshooting guide
2. Search existing GitHub issues
3. Check Laravel documentation
4. Provide system information and error logs
5. Include steps to reproduce the issue

Remember to never share sensitive information like passwords or API keys when asking for help!
