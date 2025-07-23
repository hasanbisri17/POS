#!/bin/bash

# POS System Deployment Script for VPS
# This script automates the deployment process on Ubuntu/Debian systems
# Run with: bash deploy.sh

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
PROJECT_NAME="pos-system"
PROJECT_DIR="/var/www/$PROJECT_NAME"
DOMAIN=""
DB_NAME="pos_database"
DB_USER="pos_user"
DB_PASS=""
ADMIN_EMAIL="admin@example.com"

# Functions
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

check_root() {
    if [[ $EUID -eq 0 ]]; then
        print_error "This script should not be run as root"
        exit 1
    fi
}

check_os() {
    if [[ ! -f /etc/os-release ]]; then
        print_error "Cannot determine OS version"
        exit 1
    fi
    
    . /etc/os-release
    if [[ $ID != "ubuntu" && $ID != "debian" ]]; then
        print_warning "This script is designed for Ubuntu/Debian. Proceed with caution."
    fi
}

get_user_input() {
    echo -e "${BLUE}=== POS System Deployment Configuration ===${NC}"
    
    read -p "Enter your domain name (e.g., pos.example.com): " DOMAIN
    if [[ -z "$DOMAIN" ]]; then
        print_error "Domain name is required"
        exit 1
    fi
    
    read -p "Enter database password for $DB_USER: " -s DB_PASS
    echo
    if [[ -z "$DB_PASS" ]]; then
        print_error "Database password is required"
        exit 1
    fi
    
    read -p "Enter admin email [$ADMIN_EMAIL]: " input_email
    if [[ ! -z "$input_email" ]]; then
        ADMIN_EMAIL="$input_email"
    fi
    
    echo -e "\n${YELLOW}Configuration Summary:${NC}"
    echo "Domain: $DOMAIN"
    echo "Project Directory: $PROJECT_DIR"
    echo "Database: $DB_NAME"
    echo "Database User: $DB_USER"
    echo "Admin Email: $ADMIN_EMAIL"
    
    read -p "Continue with deployment? (y/N): " confirm
    if [[ $confirm != [yY] ]]; then
        print_error "Deployment cancelled"
        exit 1
    fi
}

update_system() {
    print_status "Updating system packages..."
    sudo apt update && sudo apt upgrade -y
    sudo apt install -y curl wget git unzip software-properties-common apt-transport-https ca-certificates gnupg lsb-release
    print_success "System updated"
}

install_php() {
    print_status "Installing PHP 8.2..."
    
    # Add PHP repository
    sudo add-apt-repository ppa:ondrej/php -y
    sudo apt update
    
    # Install PHP and extensions
    sudo apt install -y php8.2 php8.2-fpm php8.2-mysql php8.2-xml php8.2-gd php8.2-curl \
        php8.2-mbstring php8.2-zip php8.2-bcmath php8.2-json php8.2-tokenizer \
        php8.2-fileinfo php8.2-dom php8.2-pcre php8.2-ctype php8.2-openssl
    
    print_success "PHP 8.2 installed"
    php -v
}

install_composer() {
    print_status "Installing Composer..."
    
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
    sudo chmod +x /usr/local/bin/composer
    
    print_success "Composer installed"
    composer --version
}

install_nodejs() {
    print_status "Installing Node.js 18..."
    
    curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
    sudo apt install -y nodejs
    
    print_success "Node.js installed"
    node --version
    npm --version
}

install_mysql() {
    print_status "Installing MySQL..."
    
    sudo apt install -y mysql-server
    sudo systemctl start mysql
    sudo systemctl enable mysql
    
    print_success "MySQL installed"
}

setup_database() {
    print_status "Setting up database..."
    
    # Create database and user
    sudo mysql -e "CREATE DATABASE IF NOT EXISTS $DB_NAME;"
    sudo mysql -e "CREATE USER IF NOT EXISTS '$DB_USER'@'localhost' IDENTIFIED BY '$DB_PASS';"
    sudo mysql -e "GRANT ALL PRIVILEGES ON $DB_NAME.* TO '$DB_USER'@'localhost';"
    sudo mysql -e "FLUSH PRIVILEGES;"
    
    print_success "Database setup completed"
}

install_nginx() {
    print_status "Installing and configuring Nginx..."
    
    sudo apt install -y nginx
    sudo systemctl start nginx
    sudo systemctl enable nginx
    
    # Create Nginx configuration
    sudo tee /etc/nginx/sites-available/$PROJECT_NAME > /dev/null <<EOF
server {
    listen 80;
    server_name $DOMAIN www.$DOMAIN;
    root $PROJECT_DIR/public;
    index index.php index.html;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
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
EOF
    
    # Enable site
    sudo ln -sf /etc/nginx/sites-available/$PROJECT_NAME /etc/nginx/sites-enabled/
    sudo nginx -t
    sudo systemctl reload nginx
    
    print_success "Nginx configured"
}

deploy_application() {
    print_status "Deploying application..."
    
    # Create project directory
    sudo mkdir -p $PROJECT_DIR
    cd /var/www
    
    # Clone repository
    sudo git clone https://github.com/hasanbisri17/POS.git $PROJECT_NAME
    cd $PROJECT_NAME
    
    # Set ownership
    sudo chown -R www-data:www-data $PROJECT_DIR
    sudo chmod -R 755 $PROJECT_DIR
    sudo chmod -R 775 $PROJECT_DIR/storage
    sudo chmod -R 775 $PROJECT_DIR/bootstrap/cache
    
    # Install dependencies
    sudo -u www-data composer install --optimize-autoloader --no-dev
    sudo -u www-data npm install
    
    # Setup environment
    sudo -u www-data cp .env.example .env
    sudo -u www-data php artisan key:generate
    
    # Configure environment
    sudo -u www-data sed -i "s|APP_URL=.*|APP_URL=https://$DOMAIN|" .env
    sudo -u www-data sed -i "s|DB_DATABASE=.*|DB_DATABASE=$DB_NAME|" .env
    sudo -u www-data sed -i "s|DB_USERNAME=.*|DB_USERNAME=$DB_USER|" .env
    sudo -u www-data sed -i "s|DB_PASSWORD=.*|DB_PASSWORD=$DB_PASS|" .env
    sudo -u www-data sed -i "s|APP_ENV=.*|APP_ENV=production|" .env
    sudo -u www-data sed -i "s|APP_DEBUG=.*|APP_DEBUG=false|" .env
    
    # Run migrations
    sudo -u www-data php artisan migrate --seed --force
    
    # Create storage link
    sudo -u www-data php artisan storage:link
    
    # Build assets
    sudo -u www-data npm run build
    
    # Cache configurations
    sudo -u www-data php artisan config:cache
    sudo -u www-data php artisan route:cache
    sudo -u www-data php artisan view:cache
    
    # Final permissions
    sudo chown -R www-data:www-data $PROJECT_DIR
    sudo chmod -R 755 $PROJECT_DIR
    sudo chmod -R 775 $PROJECT_DIR/storage
    sudo chmod -R 775 $PROJECT_DIR/bootstrap/cache
    
    print_success "Application deployed"
}

setup_ssl() {
    print_status "Setting up SSL certificate..."
    
    # Install Certbot
    sudo apt install -y certbot python3-certbot-nginx
    
    # Obtain SSL certificate
    sudo certbot --nginx -d $DOMAIN -d www.$DOMAIN --non-interactive --agree-tos --email $ADMIN_EMAIL
    
    # Test renewal
    sudo certbot renew --dry-run
    
    print_success "SSL certificate configured"
}

setup_firewall() {
    print_status "Configuring firewall..."
    
    sudo ufw allow OpenSSH
    sudo ufw allow 'Nginx Full'
    sudo ufw --force enable
    
    print_success "Firewall configured"
}

setup_cron() {
    print_status "Setting up cron jobs..."
    
    # Add Laravel scheduler
    (sudo crontab -u www-data -l 2>/dev/null; echo "* * * * * cd $PROJECT_DIR && php artisan schedule:run >> /dev/null 2>&1") | sudo crontab -u www-data -
    
    print_success "Cron jobs configured"
}

create_systemd_service() {
    print_status "Creating systemd service for queue worker..."
    
    sudo tee /etc/systemd/system/pos-queue.service > /dev/null <<EOF
[Unit]
Description=POS Queue Worker
After=network.target

[Service]
User=www-data
Group=www-data
Restart=always
ExecStart=/usr/bin/php $PROJECT_DIR/artisan queue:work --sleep=3 --tries=3 --max-time=3600
StandardOutput=journal
StandardError=journal

[Install]
WantedBy=multi-user.target
EOF
    
    sudo systemctl daemon-reload
    sudo systemctl enable pos-queue.service
    sudo systemctl start pos-queue.service
    
    print_success "Queue worker service created"
}

final_checks() {
    print_status "Running final checks..."
    
    # Check services
    if sudo systemctl is-active --quiet nginx; then
        print_success "Nginx is running"
    else
        print_error "Nginx is not running"
    fi
    
    if sudo systemctl is-active --quiet mysql; then
        print_success "MySQL is running"
    else
        print_error "MySQL is not running"
    fi
    
    if sudo systemctl is-active --quiet php8.2-fpm; then
        print_success "PHP-FPM is running"
    else
        print_error "PHP-FPM is not running"
    fi
    
    # Check application
    if curl -s -o /dev/null -w "%{http_code}" http://$DOMAIN | grep -q "200\|301\|302"; then
        print_success "Application is accessible"
    else
        print_warning "Application might not be accessible"
    fi
}

cleanup() {
    print_status "Cleaning up..."
    
    sudo apt autoremove -y
    sudo apt autoclean
    
    print_success "Cleanup completed"
}

show_completion_info() {
    echo -e "\n${GREEN}=== Deployment Completed Successfully! ===${NC}"
    echo -e "${BLUE}Application URL:${NC} https://$DOMAIN"
    echo -e "${BLUE}Admin Panel:${NC} https://$DOMAIN/admin"
    echo -e "${BLUE}Default Login:${NC}"
    echo -e "  Email: admin@example.com"
    echo -e "  Password: password"
    echo -e "\n${YELLOW}Important Next Steps:${NC}"
    echo -e "1. Change the default admin password immediately"
    echo -e "2. Configure your company details in the admin panel"
    echo -e "3. Add your products and categories"
    echo -e "4. Test the POS functionality"
    echo -e "5. Set up regular backups"
    echo -e "\n${BLUE}Useful Commands:${NC}"
    echo -e "  View logs: sudo tail -f $PROJECT_DIR/storage/logs/laravel.log"
    echo -e "  Restart services: sudo systemctl restart nginx php8.2-fpm"
    echo -e "  Update application: cd $PROJECT_DIR && sudo -u www-data git pull"
    echo -e "\n${BLUE}Support:${NC}"
    echo -e "  Documentation: https://github.com/hasanbisri17/POS"
    echo -e "  Issues: https://github.com/hasanbisri17/POS/issues"
}

# Main execution
main() {
    echo -e "${GREEN}POS System VPS Deployment Script${NC}"
    echo -e "${BLUE}================================${NC}\n"
    
    check_root
    check_os
    get_user_input
    
    print_status "Starting deployment process..."
    
    update_system
    install_php
    install_composer
    install_nodejs
    install_mysql
    setup_database
    install_nginx
    deploy_application
    setup_ssl
    setup_firewall
    setup_cron
    create_systemd_service
    final_checks
    cleanup
    
    show_completion_info
}

# Handle script interruption
trap 'print_error "Deployment interrupted"; exit 1' INT TERM

# Run main function
main "$@"
