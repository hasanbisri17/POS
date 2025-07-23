# Shared Hosting Installation Guide

This guide will help you install the POS System on shared hosting providers like cPanel, Hostinger, Namecheap, GoDaddy, and others.

## Prerequisites

- Shared hosting account with PHP 8.2+ support
- MySQL database access
- File manager or FTP access
- At least 500MB storage space
- Domain or subdomain configured

## Supported Hosting Providers

This guide has been tested with:
- ✅ cPanel-based hosting
- ✅ Hostinger
- ✅ Namecheap
- ✅ GoDaddy
- ✅ Bluehost
- ✅ SiteGround
- ✅ A2 Hosting
- ✅ InMotion Hosting

## Step 1: Check Hosting Requirements

Before starting, verify your hosting meets these requirements:

### PHP Requirements
- PHP 8.2 or higher
- Required PHP extensions:
  - BCMath
  - Ctype
  - cURL
  - DOM
  - Fileinfo
  - JSON
  - Mbstring
  - OpenSSL
  - PCRE
  - PDO
  - Tokenizer
  - XML
  - GD
  - ZIP

### How to Check PHP Version
1. Create a file named `phpinfo.php` in your public_html folder
2. Add this content: `<?php phpinfo(); ?>`
3. Visit `yourdomain.com/phpinfo.php`
4. Check PHP version and extensions
5. Delete the file after checking

## Step 2: Download and Prepare Files

### Option A: Download from GitHub (Recommended)

1. Go to https://github.com/hasanbisri17/POS
2. Click "Code" → "Download ZIP"
3. Extract the ZIP file on your computer

### Option B: Clone via Git (if available)

```bash
git clone https://github.com/hasanbisri17/POS.git
```

## Step 3: Upload Files to Hosting

### Using cPanel File Manager

1. Login to your cPanel
2. Open "File Manager"
3. Navigate to `public_html` (or your domain's folder)
4. Upload the ZIP file or drag & drop all project files
5. If uploaded as ZIP, extract it
6. Move all contents from the extracted folder to `public_html`

### Using FTP Client (FileZilla, WinSCP, etc.)

1. Connect to your hosting via FTP
2. Navigate to `public_html` or your domain folder
3. Upload all project files
4. Ensure proper file permissions (755 for folders, 644 for files)

### File Structure After Upload
```
public_html/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
├── resources/
├── routes/
├── storage/
├── tests/
├── vendor/ (will be created later)
├── .env (will be created)
├── .gitignore
├── artisan
├── composer.json
├── package.json
└── README.md
```

## Step 4: Configure Document Root

### Important: Public Folder Setup

Laravel requires the `public` folder to be your document root. Here are methods for different hosting types:

### Method 1: Subdomain/Addon Domain (Recommended)
1. Create a subdomain or addon domain in cPanel
2. Set the document root to `/public_html/your-pos-folder/public`
3. This is the cleanest and most secure method

### Method 2: Main Domain with .htaccess
If you must use the main domain, create `.htaccess` in `public_html`:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

### Method 3: Move Public Contents (Less Secure)
1. Move contents of `public` folder to `public_html`
2. Update `index.php` paths:
   ```php
   require __DIR__.'/../vendor/autoload.php';
   $app = require_once __DIR__.'/../bootstrap/app.php';
   ```

## Step 5: Install Composer Dependencies

### Option A: Using Hosting's Composer (if available)

Many hosts now provide Composer access:

```bash
# Via SSH (if available)
cd /path/to/your/project
composer install --optimize-autoloader --no-dev

# Via cPanel Terminal (if available)
composer install --optimize-autoloader --no-dev
```

### Option B: Upload Vendor Folder

If Composer isn't available:

1. Install dependencies locally on your computer:
   ```bash
   composer install --optimize-autoloader --no-dev
   ```
2. Upload the generated `vendor` folder to your hosting
3. This folder can be large (50-100MB), so be patient

### Option C: Use Hosting's Auto-Installer

Some hosts offer Laravel auto-installers:
- Softaculous (available on many cPanel hosts)
- Fantastico
- Check your hosting control panel

## Step 6: Create Database

### Using cPanel MySQL Databases

1. Login to cPanel
2. Find "MySQL Databases"
3. Create a new database:
   - Database name: `your_username_pos`
4. Create a database user:
   - Username: `your_username_posuser`
   - Password: `strong_password_here`
5. Add user to database with "All Privileges"
6. Note down the database details

### Using phpMyAdmin

1. Access phpMyAdmin from cPanel
2. Click "New" to create database
3. Name it `pos_database` or similar
4. Set collation to `utf8mb4_unicode_ci`

## Step 7: Configure Environment

### Create .env File

1. Copy `.env.example` to `.env`
2. Edit `.env` file with your hosting details:

```env
APP_NAME="POS System"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_username_pos
DB_USERNAME=your_username_posuser
DB_PASSWORD=your_strong_password

# File Storage
FILESYSTEM_DISK=public

# Session & Cache
SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync

# Mail Configuration (Optional)
MAIL_MAILER=smtp
MAIL_HOST=your-hosting-smtp
MAIL_PORT=587
MAIL_USERNAME=your-email@yourdomain.com
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@yourdomain.com
MAIL_FROM_NAME="POS System"

# Security
SESSION_SECURE_COOKIE=true
SECURE_COOKIES=true
```

### Generate Application Key

#### Option A: Via SSH/Terminal
```bash
php artisan key:generate
```

#### Option B: Manual Generation
1. Visit: https://generate-random.org/laravel-key-generator
2. Copy the generated key
3. Add to `.env`: `APP_KEY=base64:your_generated_key_here`

## Step 8: Set File Permissions

### Using cPanel File Manager
1. Select `storage` folder
2. Right-click → "Change Permissions"
3. Set to 755 (or 775 if needed)
4. Check "Recurse into subdirectories"
5. Repeat for `bootstrap/cache` folder

### Using FTP
Set these permissions:
- Folders: 755
- Files: 644
- `storage/` folder: 775 (recursive)
- `bootstrap/cache/` folder: 775 (recursive)

## Step 9: Run Database Migrations

### Option A: Via SSH/Terminal
```bash
php artisan migrate --seed
```

### Option B: Via Web Interface
Create a temporary file `install.php` in your public folder:

```php
<?php
require_once __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "Running migrations...\n";
$kernel->call('migrate', ['--seed' => true, '--force' => true]);
echo "Migrations completed!\n";

echo "Creating storage link...\n";
$kernel->call('storage:link');
echo "Storage link created!\n";

echo "Installation completed! Please delete this file.";
?>
```

Visit `yourdomain.com/install.php` and then delete the file.

### Option C: Manual Database Import
1. Download the SQL file from the repository
2. Import via phpMyAdmin
3. Update the database connection details

## Step 10: Create Storage Link

### Via SSH/Terminal
```bash
php artisan storage:link
```

### Via File Manager
Create a symbolic link from `public/storage` to `storage/app/public`:
1. Some hosts don't support symlinks
2. You may need to copy files instead of linking
3. Contact hosting support if needed

## Step 11: Build Frontend Assets (Optional)

If your hosting supports Node.js:

```bash
npm install
npm run build
```

Otherwise, the pre-built assets should work fine.

## Step 12: Configure Cron Jobs (Optional)

### Using cPanel Cron Jobs
1. Go to cPanel → "Cron Jobs"
2. Add a new cron job:
   - Minute: `*`
   - Hour: `*`
   - Day: `*`
   - Month: `*`
   - Weekday: `*`
   - Command: `/usr/local/bin/php /home/username/public_html/artisan schedule:run`

### Alternative: Webcron Services
If cron jobs aren't available, use services like:
- EasyCron.com
- cron-job.org
- SetCronJob.com

Set them to call: `https://yourdomain.com/schedule` (you'll need to create this route)

## Step 13: SSL Certificate Setup

### Using Let's Encrypt (if supported)
1. Many hosts offer free SSL via cPanel
2. Go to "SSL/TLS" → "Let's Encrypt"
3. Enable for your domain

### Using Hosting Provider's SSL
1. Check if your host provides free SSL
2. Enable it in your control panel
3. Update APP_URL in .env to use https://

## Step 14: Final Testing

1. Visit your domain
2. You should see the POS login page
3. Default credentials:
   - Email: `admin@example.com`
   - Password: `password`
4. Change the password immediately
5. Test all functionality

## Common Hosting-Specific Instructions

### cPanel Hosting
- Use "File Manager" for file operations
- "MySQL Databases" for database setup
- "Cron Jobs" for scheduled tasks
- "SSL/TLS" for HTTPS setup

### Hostinger
- Use "File Manager" in hPanel
- "MySQL Databases" for database
- May need to use full paths in cron jobs
- Free SSL available

### Namecheap
- Use "File Manager" in cPanel
- "MySQL Database Wizard" for setup
- SSH access available on higher plans
- Free SSL with EasyWP

### GoDaddy
- Use "File Manager" in cPanel
- "MySQL Databases" for database
- Limited SSH access
- SSL available as add-on

## Troubleshooting

### Common Issues

#### 1. 500 Internal Server Error
```bash
# Check error logs in cPanel
# Verify file permissions
# Check .env configuration
# Ensure all required PHP extensions are enabled
```

#### 2. Database Connection Error
- Verify database credentials in `.env`
- Check if database exists
- Ensure database user has proper privileges
- Try using `127.0.0.1` instead of `localhost`

#### 3. File Permission Issues
```bash
# Set proper permissions
chmod 755 storage/ -R
chmod 755 bootstrap/cache/ -R
```

#### 4. Composer Dependencies Missing
- Upload vendor folder manually
- Or use hosting's Composer if available
- Check with hosting support for Composer access

#### 5. Storage Link Issues
- Some hosts don't support symlinks
- Copy files instead of creating links
- Contact hosting support for assistance

#### 6. Asset Loading Issues
- Check if files are uploaded correctly
- Verify public folder configuration
- Clear browser cache

### Performance Optimization for Shared Hosting

#### 1. Enable OPcache (if available)
Add to `.htaccess`:
```apache
php_value opcache.enable 1
php_value opcache.memory_consumption 128
```

#### 2. Optimize Laravel for Shared Hosting
```bash
# Cache configurations (if SSH available)
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### 3. Database Optimization
- Use database indexing
- Optimize queries
- Consider caching frequently accessed data

#### 4. File Optimization
- Compress images
- Minify CSS/JS (already done by Vite)
- Use CDN if possible

### Security Considerations

1. **Hide Laravel Files**
   - Ensure only `public` folder is accessible
   - Use proper document root configuration

2. **Environment Security**
   - Never commit `.env` to version control
   - Use strong database passwords
   - Enable HTTPS

3. **Regular Updates**
   - Keep Laravel and dependencies updated
   - Monitor security advisories
   - Regular backups

### Backup Strategy

#### 1. Database Backup
```bash
# Via SSH
mysqldump -u username -p database_name > backup.sql

# Via phpMyAdmin
# Use Export feature
```

#### 2. File Backup
- Download entire project folder
- Use hosting's backup features
- Consider automated backup services

### Support Resources

1. **Hosting Support**
   - Contact your hosting provider
   - Check their knowledge base
   - Use live chat if available

2. **Laravel Community**
   - Laravel.com documentation
   - Laracasts.com tutorials
   - Stack Overflow

3. **POS System Support**
   - GitHub Issues: https://github.com/hasanbisri17/POS/issues
   - Documentation: Check README.md

## Hosting Provider Specific Notes

### Hostinger
- PHP Selector available
- Free SSL certificates
- Git deployment available
- SSH access on premium plans

### Namecheap
- cPanel with Softaculous
- Free SSL with some plans
- SSH access available
- Good Laravel support

### GoDaddy
- cPanel interface
- Limited SSH access
- May need manual Composer setup
- SSL certificates available

### Bluehost
- cPanel with Softaculous
- SSH access available
- Free SSL certificates
- Good PHP support

### SiteGround
- Custom control panel
- Git deployment
- Free SSL certificates
- Excellent Laravel support

Your POS System should now be successfully installed on your shared hosting!

Remember to:
- Change default passwords
- Set up regular backups
- Monitor your application logs
- Keep the system updated
