# VPS Installation Guide

This guide will help you install the POS System on a VPS (Virtual Private Server) running Ubuntu 20.04/22.04 or CentOS 7/8.

## Prerequisites

- VPS with Ubuntu 20.04+ or CentOS 7+
- Root or sudo access
- At least 1GB RAM and 10GB storage
- Domain name (optional but recommended)

## Step 1: Server Preparation

### For Ubuntu/Debian

```bash
# Update system packages
sudo apt update && sudo apt upgrade -y

# Install required packages
sudo apt install -y curl wget git unzip software-properties-common apt-transport-https ca-certificates gnupg lsb-release
```

### For CentOS/RHEL

```bash
# Update system packages
sudo yum update -y

# Install EPEL repository
sudo yum install -y epel-release

# Install required packages
sudo yum install -y curl wget git unzip
```

## Step 2: Install PHP 8.2

### Ubuntu/Debian

```bash
# Add PHP repository
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

# Install PHP 8.2 and extensions
sudo apt install -y php8.2 php8.2-fpm php8.2-mysql php8.2-xml php8.2-gd php8.2-curl php8.2-mbstring php8.2-zip php8.2-bcmath php8.2-json php8.2-tokenizer php8.2-fileinfo php8.2-dom php8.2-pcre php8.2-ctype php8.2-openssl

# Verify PHP installation
php -v
```

### CentOS/RHEL

```bash
# Add Remi repository
sudo yum install -y https://rpms.remirepo.net/enterprise/remi-release-8.rpm
sudo yum module enable php:remi-8.2 -y

# Install PHP 8.2 and extensions
sudo yum install -y php php-fpm php-mysql php-xml php-gd php-curl php-mbstring php-zip php-bcmath php-json php-tokenizer php-fileinfo php-dom php-pcre php-ctype php-openssl

# Verify PHP installation
php -v
```

## Step 3: Install Composer

```bash
# Download and install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer

# Verify Composer installation
composer --version
```

## Step 4: Install Node.js and NPM

```bash
# Install Node.js 18.x
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs

# For CentOS/RHEL
curl -fsSL https://rpm.nodesource.com/setup_18.x | sudo bash -
sudo yum install -y nodejs

# Verify installation
node --version
npm --version
```

## Step 5: Install and Configure MySQL

### Ubuntu/Debian

```bash
# Install MySQL
sudo apt install -y mysql-server

# Secure MySQL installation
sudo mysql_secure_installation
```

### CentOS/RHEL

```bash
# Install MySQL
sudo yum install -y mysql-server
sudo systemctl start mysqld
sudo systemctl enable mysqld

# Get temporary root password
sudo grep 'temporary password' /var/log/mysqld.log

# Secure MySQL installation
sudo mysql_secure_installation
```

### Create Database

```bash
# Login to MySQL
mysql -u root -p

# Create database and user
CREATE DATABASE pos_database;
CREATE USER 'pos_user'@'localhost' IDENTIFIED BY 'strong_password_here';
GRANT ALL PRIVILEGES ON pos_database.* TO 'pos_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

## Step 6: Install and Configure Web Server

### Option A: Nginx (Recommended)

```bash
# Install Nginx
sudo apt install -y nginx  # Ubuntu/Debian
sudo yum install -y nginx  # CentOS/RHEL

# Start and enable Nginx
sudo systemctl start nginx
sudo systemctl enable nginx

# Create Nginx configuration
sudo nano /etc/nginx/sites-available/pos-system
```

Add the following configuration:

```nginx
server {
    listen 80;
    server_name your-domain.com www.your-domain.com;
    root /var/www/pos-system/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }

    location ~* \.(css|js|png|jpg|jpeg|gif|ico|svg)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

```bash
# Enable the site
sudo ln -s /etc/nginx/sites-available/pos-system /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### Option B: Apache

```bash
# Install Apache
sudo apt install -y apache2  # Ubuntu/Debian
sudo yum install -y httpd    # CentOS/RHEL

# Enable required modules
sudo a2enmod rewrite
sudo a2enmod ssl

# Create virtual host
sudo nano /etc/apache2/sites-available/pos-system.conf
```

Add the following configuration:

```apache
<VirtualHost *:80>
    ServerName your-domain.com
    ServerAlias www.your-domain.com
    DocumentRoot /var/www/pos-system/public

    <Directory /var/www/pos-system/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/pos-system_error.log
    CustomLog ${APACHE_LOG_DIR}/pos-system_access.log combined
</VirtualHost>
```

```bash
# Enable the site
sudo a2ensite pos-system.conf
sudo systemctl reload apache2
```

## Step 7: Deploy the Application

```bash
# Create web directory
sudo mkdir -p /var/www/pos-system
cd /var/www

# Clone the repository
sudo git clone https://github.com/hasanbisri17/POS.git pos-system
cd pos-system

# Set proper ownership
sudo chown -R www-data:www-data /var/www/pos-system
sudo chmod -R 755 /var/www/pos-system
sudo chmod -R 775 /var/www/pos-system/storage
sudo chmod -R 775 /var/www/pos-system/bootstrap/cache

# Install PHP dependencies
sudo -u www-data composer install --optimize-autoloader --no-dev

# Install Node.js dependencies
sudo -u www-data npm install

# Copy environment file
sudo -u www-data cp .env.example .env

# Generate application key
sudo -u www-data php artisan key:generate
```

## Step 8: Configure Environment

```bash
# Edit environment file
sudo -u www-data nano .env
```

Update the following variables:

```env
APP_NAME="POS System"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pos_database
DB_USERNAME=pos_user
DB_PASSWORD=strong_password_here

# Add your other configurations
```

## Step 9: Complete Installation

```bash
# Run database migrations and seeders
sudo -u www-data php artisan migrate --seed

# Create storage link
sudo -u www-data php artisan storage:link

# Build frontend assets
sudo -u www-data npm run build

# Clear and cache configurations
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache

# Set final permissions
sudo chown -R www-data:www-data /var/www/pos-system
sudo chmod -R 755 /var/www/pos-system
sudo chmod -R 775 /var/www/pos-system/storage
sudo chmod -R 775 /var/www/pos-system/bootstrap/cache
```

## Step 10: Configure SSL (Optional but Recommended)

### Using Let's Encrypt (Free SSL)

```bash
# Install Certbot
sudo apt install -y certbot python3-certbot-nginx  # For Nginx
sudo apt install -y certbot python3-certbot-apache # For Apache

# Obtain SSL certificate
sudo certbot --nginx -d your-domain.com -d www.your-domain.com  # For Nginx
sudo certbot --apache -d your-domain.com -d www.your-domain.com # For Apache

# Test automatic renewal
sudo certbot renew --dry-run
```

## Step 11: Configure Firewall

```bash
# Configure UFW (Ubuntu/Debian)
sudo ufw allow OpenSSH
sudo ufw allow 'Nginx Full'  # or 'Apache Full'
sudo ufw enable

# Configure firewalld (CentOS/RHEL)
sudo firewall-cmd --permanent --add-service=ssh
sudo firewall-cmd --permanent --add-service=http
sudo firewall-cmd --permanent --add-service=https
sudo firewall-cmd --reload
```

## Step 12: Setup Cron Jobs (Optional)

```bash
# Edit crontab for www-data user
sudo crontab -u www-data -e

# Add Laravel scheduler
* * * * * cd /var/www/pos-system && php artisan schedule:run >> /dev/null 2>&1

# Add backup job (if using backup package)
0 2 * * * cd /var/www/pos-system && php artisan backup:run >> /dev/null 2>&1
```

## Step 13: Configure Queue Worker (Optional)

```bash
# Create systemd service for queue worker
sudo nano /etc/systemd/system/pos-queue.service
```

Add the following content:

```ini
[Unit]
Description=POS Queue Worker
After=network.target

[Service]
User=www-data
Group=www-data
Restart=always
ExecStart=/usr/bin/php /var/www/pos-system/artisan queue:work --sleep=3 --tries=3 --max-time=3600
StandardOutput=journal
StandardError=journal

[Install]
WantedBy=multi-user.target
```

```bash
# Enable and start the service
sudo systemctl daemon-reload
sudo systemctl enable pos-queue.service
sudo systemctl start pos-queue.service
```

## Step 14: Final Testing

1. Visit your domain in a web browser
2. You should see the POS System login page
3. Login with default credentials:
   - Email: `admin@example.com`
   - Password: `password`
4. Change the default password immediately
5. Test creating products, categories, and transactions

## Maintenance Commands

```bash
# Update application
cd /var/www/pos-system
sudo -u www-data git pull origin main
sudo -u www-data composer install --optimize-autoloader --no-dev
sudo -u www-data npm install
sudo -u www-data npm run build
sudo -u www-data php artisan migrate
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache

# Backup database
mysqldump -u pos_user -p pos_database > pos_backup_$(date +%Y%m%d_%H%M%S).sql

# Monitor logs
sudo tail -f /var/log/nginx/error.log
sudo tail -f /var/www/pos-system/storage/logs/laravel.log
```

## Troubleshooting

### Common Issues

1. **Permission Errors**
   ```bash
   sudo chown -R www-data:www-data /var/www/pos-system
   sudo chmod -R 775 /var/www/pos-system/storage
   sudo chmod -R 775 /var/www/pos-system/bootstrap/cache
   ```

2. **Database Connection Issues**
   - Check MySQL service: `sudo systemctl status mysql`
   - Verify database credentials in `.env`
   - Test connection: `mysql -u pos_user -p pos_database`

3. **Web Server Issues**
   - Check Nginx: `sudo nginx -t && sudo systemctl status nginx`
   - Check Apache: `sudo apache2ctl configtest && sudo systemctl status apache2`

4. **PHP-FPM Issues**
   ```bash
   sudo systemctl status php8.2-fpm
   sudo systemctl restart php8.2-fpm
   ```

5. **Asset Loading Issues**
   ```bash
   sudo -u www-data npm run build
   sudo -u www-data php artisan storage:link
   ```

## Security Recommendations

1. **Regular Updates**
   ```bash
   sudo apt update && sudo apt upgrade -y
   ```

2. **Fail2Ban Installation**
   ```bash
   sudo apt install -y fail2ban
   sudo systemctl enable fail2ban
   ```

3. **Regular Backups**
   - Set up automated database backups
   - Backup application files regularly
   - Store backups in a secure location

4. **Monitor Logs**
   - Regularly check application and server logs
   - Set up log rotation
   - Monitor for suspicious activities

## Performance Optimization

1. **Enable OPcache**
   ```bash
   sudo nano /etc/php/8.2/fpm/php.ini
   ```
   
   Enable:
   ```ini
   opcache.enable=1
   opcache.memory_consumption=128
   opcache.interned_strings_buffer=8
   opcache.max_accelerated_files=4000
   opcache.revalidate_freq=2
   opcache.fast_shutdown=1
   ```

2. **Configure PHP-FPM**
   ```bash
   sudo nano /etc/php/8.2/fpm/pool.d/www.conf
   ```
   
   Optimize:
   ```ini
   pm = dynamic
   pm.max_children = 50
   pm.start_servers = 5
   pm.min_spare_servers = 5
   pm.max_spare_servers = 35
   ```

3. **Enable Gzip Compression**
   Add to Nginx configuration:
   ```nginx
   gzip on;
   gzip_vary on;
   gzip_min_length 1024;
   gzip_types text/plain text/css text/xml text/javascript application/javascript application/xml+rss application/json;
   ```

Your POS System should now be successfully installed and running on your VPS!
