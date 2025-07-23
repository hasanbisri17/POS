# POS (Point of Sale) System

A modern Point of Sale system built with Laravel 12 and Filament 3, designed for small to medium businesses to manage their sales, inventory, and transactions efficiently.

## Features

### 🛍️ Sales Management
- **Point of Sale Interface**: User-friendly POS interface for quick transactions
- **Transaction Management**: Complete transaction history and management
- **Receipt Printing**: Generate and print receipts for customers
- **Multiple Payment Methods**: Support for cash, card, and other payment methods

### 📦 Inventory Management
- **Product Management**: Add, edit, and manage products with categories
- **Stock Tracking**: Real-time inventory tracking with low stock alerts
- **Product Variants**: Support for product variations (size, color, etc.)
- **Stock Movements**: Track all inventory movements with detailed logs

### 💰 Financial Management
- **Cash Management**: Track cash flow with categories
- **Sales Reports**: Comprehensive sales analytics and reports
- **Dashboard**: Real-time business insights and key metrics
- **Export Data**: Export transactions and reports to Excel

### 👥 User Management
- **Role-based Access**: Different access levels for staff and managers
- **User Authentication**: Secure login system
- **Activity Tracking**: Monitor user activities and transactions

### 🎨 Modern Interface
- **Filament Admin Panel**: Beautiful and intuitive admin interface
- **Responsive Design**: Works perfectly on desktop, tablet, and mobile
- **Dark/Light Mode**: Support for different themes
- **Real-time Updates**: Live updates using Livewire

## Tech Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Admin Panel**: Filament 3.3
- **Frontend**: Livewire/Volt, Tailwind CSS 4.0
- **Database**: MySQL 8.0+
- **Authentication**: Laravel Sanctum
- **File Storage**: Laravel Storage
- **Testing**: Pest PHP

## Requirements

### Server Requirements
- PHP 8.2 or higher
- MySQL 8.0 or higher
- Composer 2.0+
- Node.js 18+ & NPM
- Web server (Apache/Nginx)

### PHP Extensions
- BCMath PHP Extension
- Ctype PHP Extension
- cURL PHP Extension
- DOM PHP Extension
- Fileinfo PHP Extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PCRE PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- GD PHP Extension
- ZIP PHP Extension

## Installation

### Quick Installation (VPS/Local)

1. **Clone the repository**
```bash
git clone https://github.com/hasanbisri17/POS.git
cd POS
```

2. **Install PHP dependencies**
```bash
composer install
```

3. **Install Node.js dependencies**
```bash
npm install
```

4. **Environment setup**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Configure database in .env file**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pos_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

6. **Run migrations and seeders**
```bash
php artisan migrate --seed
```

7. **Create storage link**
```bash
php artisan storage:link
```

8. **Build assets**
```bash
npm run build
```

9. **Start the application**
```bash
php artisan serve
```

Visit `http://localhost:8000` to access the application.

### Default Login Credentials
- **Email**: admin@example.com
- **Password**: password

**⚠️ Important**: Change the default credentials immediately after first login!

## Detailed Installation Guides

- [📋 VPS Installation Guide](docs/VPS_INSTALLATION.md)
- [🌐 Shared Hosting Installation Guide](docs/HOSTING_INSTALLATION.md)
- [🐳 Docker Installation Guide](docs/DOCKER_INSTALLATION.md)

## Configuration

### Environment Variables
Key environment variables you need to configure:

```env
# Application
APP_NAME="POS System"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pos_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Mail (Optional)
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls

# File Storage
FILESYSTEM_DISK=public
```

### File Permissions
Set proper permissions for Laravel:
```bash
sudo chown -R www-data:www-data /path/to/your/project
sudo chmod -R 755 /path/to/your/project
sudo chmod -R 775 /path/to/your/project/storage
sudo chmod -R 775 /path/to/your/project/bootstrap/cache
```

## Usage

### Creating Your First Product
1. Login to the admin panel
2. Navigate to **Products** → **Categories**
3. Create a product category
4. Navigate to **Products** → **Products**
5. Add your first product with details

### Making a Sale
1. Go to **POS** from the main navigation
2. Select products to add to cart
3. Choose payment method
4. Complete the transaction
5. Print receipt if needed

### Managing Inventory
1. Navigate to **Inventory** → **Stock Movements**
2. View all stock changes
3. Use **Products** section to adjust stock levels
4. Monitor low stock alerts on dashboard

## API Documentation

The system includes API endpoints for integration:

- `GET /api/products` - Get all products
- `POST /api/transactions` - Create new transaction
- `GET /api/reports/sales` - Get sales reports

For detailed API documentation, visit `/api/documentation` after installation.

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## Testing

Run the test suite:
```bash
php artisan test
```

Run specific tests:
```bash
php artisan test --filter=ProductTest
```

## Troubleshooting

### Common Issues

**1. Permission Denied Errors**
```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

**2. Database Connection Issues**
- Verify database credentials in `.env`
- Ensure MySQL service is running
- Check firewall settings

**3. Asset Loading Issues**
```bash
npm run build
php artisan config:clear
php artisan cache:clear
```

**4. Storage Link Issues**
```bash
php artisan storage:link
```

For more troubleshooting tips, see [TROUBLESHOOTING.md](docs/TROUBLESHOOTING.md)

## Security

- Always use HTTPS in production
- Regularly update dependencies
- Use strong database passwords
- Enable Laravel's security features
- Regular backups of database and files

## License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## Support

- 📧 Email: support@yourcompany.com
- 📖 Documentation: [Wiki](https://github.com/hasanbisri17/POS/wiki)
- 🐛 Bug Reports: [Issues](https://github.com/hasanbisri17/POS/issues)
- 💬 Discussions: [Discussions](https://github.com/hasanbisri17/POS/discussions)

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for a list of changes and updates.

---

**Made with ❤️ for small businesses**
