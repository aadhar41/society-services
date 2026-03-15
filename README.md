# Society Accounting Management System

![Laravel](https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/mysql-%2300f.svg?style=for-the-badge&logo=mysql&logoColor=white)
![AdminLTE](https://img.shields.io/badge/AdminLTE-%234285F4.svg?style=for-the-badge&logo=adminlte&logoColor=white)

## 🚀 Overview

**Society Accounting Management System** is a comprehensive Laravel-based web application designed to streamline the management of residential societies, residential complexes, and apartment buildings. This powerful tool helps administrators efficiently handle accounting, maintenance, resident information, and more, all in one centralized platform.

Whether you're managing a small residential complex or a large apartment building, this system provides the tools you need to maintain order, track finances, and communicate effectively with residents.

## ✨ Key Features

✅ **Comprehensive Society Management**
- Create, edit, and manage societies with complete details
- Track all society-related information in one place

✅ **Block and Flat Organization**
- Manage multiple blocks within a society
- Organize flats within blocks with detailed information
- Track property types (self-occupied, rented, locked)

✅ **Maintenance Tracking**
- Record monthly maintenance charges
- Track lift maintenance and other contributions
- Manage donations and other special payments
- Attach receipts and documents for verification

✅ **Resident Information**
- Maintain detailed resident profiles
- Track contact information and property details
- Manage tenant information for rented properties

✅ **Financial Management**
- Record and track all financial transactions
- Generate invoices and receipts
- Manage payment statuses (pending, done, extra)
- Comprehensive reporting and analytics

✅ **Complaint System**
- Residents can raise complaints
- Administrators can track and resolve complaints
- Maintain a history of all complaints

✅ **Notice Board**
- Post announcements and important notices
- Share events and society updates
- Send notifications to residents

✅ **Role-Based Access Control**
- Different user roles (admin, residents, staff)
- Customizable permissions for each role
- Secure access to sensitive information

✅ **Modern UI with AdminLTE**
- Clean, intuitive interface
- Responsive design for all devices
- DataTables integration for powerful data management

✅ **API Support**
- RESTful API endpoints for mobile applications
- JSON-based responses for seamless integration
- Token-based authentication

## 🛠️ Tech Stack

**Core Technologies:**
- PHP 7.3+ | 8.0+
- Laravel 8.x Framework
- MySQL Database
- AdminLTE 3.x Admin Template

**Frontend:**
- Bootstrap 4.x
- jQuery
- Select2 for enhanced dropdowns
- DataTables for advanced data management
- Laravel Mix for asset compilation

**Backend:**
- Eloquent ORM for database interactions
- Laravel Sanctum for API authentication
- Laravel CORS for cross-origin resource sharing
- Laravel UI AdminLTE for admin panel integration

**Development Tools:**
- Composer for dependency management
- Node.js for frontend build tools
- PHPUnit for testing
- Laravel Sail for Docker-based development

## 📦 Installation

### Prerequisites

Before you begin, ensure you have the following installed on your system:
- [Composer](https://getcomposer.org/) (for PHP dependencies)
- [Node.js](https://nodejs.org/) (for frontend dependencies)
- [MySQL](https://dev.mysql.com/downloads/) (or any MySQL-compatible database)
- [PHP](https://www.php.net/downloads.php) (7.3 or higher)
- [Git](https://git-scm.com/downloads) (for version control)

### Quick Start

1. **Clone the repository:**
   ```bash
   git clone https://github.com/your-username/society-accounting.git
   cd society-accounting
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Install JavaScript dependencies:**
   ```bash
   npm install
   ```

4. **Copy the environment configuration:**
   ```bash
   cp .env.example .env
   ```

5. **Generate application key:**
   ```bash
   php artisan key:generate
   ```

6. **Configure your database:**
   Edit the `.env` file with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_user
   DB_PASSWORD=your_database_password
   ```

7. **Set up the database:**
   ```bash
   php artisan migrate --seed
   ```

8. **Compile assets:**
   ```bash
   npm run dev
   ```

9. **Start the development server:**
   ```bash
   php artisan serve
   ```

10. **Access the application:**
    Open your browser and navigate to `http://localhost:8000`

### Alternative Installation Methods

#### Using Docker (Recommended for Development)

1. **Install Docker and Docker Compose:**
   Follow the instructions for your operating system at [Docker's official site](https://docs.docker.com/get-docker/)

2. **Start the containers:**
   ```bash
   docker-compose up -d
   ```

3. **Set up the database:**
   ```bash
   docker-compose exec app php artisan migrate --seed
   ```

4. **Compile assets:**
   ```bash
   docker-compose exec app npm run dev
   ```

5. **Access the application:**
   Open your browser and navigate to `http://localhost`

#### Using Laravel Sail

1. **Install Laravel Sail:**
   ```bash
   composer require laravel/sail --dev
   ```

2. **Create the Sail configuration file:**
   ```bash
   ./vendor/bin/sail up
   ```

3. **Set up the database:**
   ```bash
   ./vendor/bin/sail artisan migrate --seed
   ```

4. **Compile assets:**
   ```bash
   ./vendor/bin/sail npm run dev
   ```

5. **Access the application:**
   ```bash
   ./vendor/bin/sail artisan serve
   ```

## 🎯 Usage

### Basic Usage

#### Creating a Society

1. Navigate to the "Societies" section in the admin panel
2. Click "Add Record" to create a new society
3. Fill in the required details:
   - Name
   - Address
   - Contact information
   - Description
   - Location details (country, state, city)

```php
// Example of creating a society via API
$response = Http::post('/api/societies', [
    'name' => 'Green Acres Society',
    'address' => '123 Main Street, City',
    'contact' => '1234567890',
    'country' => 'Country ID',
    'state' => 'State ID',
    'city' => 'City ID',
    'description' => 'A beautiful residential complex'
]);
```

#### Managing Blocks

1. Select a society from the dropdown
2. Click "Add Record" to create a new block
3. Enter block details:
   - Name
   - Total flats
   - Description
   - Status

```php
// Example of creating a block via API
$response = Http::post('/api/societies/{society_id}/blocks', [
    'name' => 'Block A',
    'total_flats' => 50,
    'description' => 'Main residential block'
]);
```

#### Recording Maintenance Payments

1. Navigate to the "Maintenance" section
2. Select the appropriate flat
3. Enter maintenance details:
   - Type (monthly, lift, donation, etc.)
   - Date
   - Amount
   - Description
   - Attachments (if any)

```php
// Example of creating a maintenance record via API
$response = Http::post('/api/societies/{society_id}/blocks/{block_id}/flats/{flat_id}/maintenance', [
    'type' => 'monthly',
    'date' => '2023-05-15',
    'year' => 2023,
    'month' => 5,
    'amount' => 5000,
    'description' => 'Monthly maintenance charges',
    'attachments' => 'path/to/receipt.pdf'
]);
```

### Advanced Usage

#### Customizing the UI

1. Edit the SCSS files in `resources/sass/app.scss`
2. Modify the layout in `resources/views/layouts/app.blade.php`
3. Update the JavaScript logic in `resources/js/app.js`

#### Extending Functionality

1. Create new controllers and models following the existing pattern
2. Add new routes in `routes/web.php` and `routes/api.php`
3. Create new views in the `resources/views` directory
4. Add new migrations using `php artisan make:migration`

```php
// Example of creating a new controller
php artisan make:controller ComplaintController --api
```

#### API Integration

The system provides a comprehensive API for mobile applications and other integrations:

```php
// Example of API authentication
$response = Http::withHeaders([
    'Authorization' => 'Bearer ' . $token
])->get('/api/societies');
```

## 📁 Project Structure

```
society-accounting/
├── app/                  # Application source code
│   ├── Http/             # Controllers, Middleware, etc.
│   ├── Models/           # Eloquent models
│   ├── Providers/        # Service providers
│   ├── Repositories/     # Repository interfaces and implementations
│   └── ...
├── config/              # Configuration files
├── database/            # Database migrations and seeders
├── public/              # Publicly accessible files
├── resources/           # Views, languages, assets
│   ├── js/               # JavaScript files
│   ├── sass/            # SCSS stylesheets
│   └── views/           # Blade templates
├── routes/              # Route definitions
├── tests/               # Test cases
├── vendor/              # Composer dependencies
├── .env                 # Environment configuration
├── .gitignore           # Git ignore rules
├── artisan              # Laravel artisan CLI
├── composer.json        # PHP dependencies
├── package.json         # JavaScript dependencies
└── README.md            # This file
```

## 🔧 Configuration

### Environment Variables

Copy `.env.example` to `.env` and configure your environment:

```env
# Application settings
APP_NAME=Society Accounting
APP_ENV=local
APP_KEY=your-app-key
APP_DEBUG=true
APP_URL=http://localhost

# Database settings
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Mail settings
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025

# Caching
CACHE_DRIVER=file

# Session
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Authentication
SANCTUM_STATEFUL_DOMAINS=localhost
```

### Customization Options

1. **Change the Admin Panel Theme:**
   Edit the `resources/sass/app.scss` file to customize colors and styles

2. **Modify User Roles and Permissions:**
   Update the `app/Policies` directory and adjust the middleware in `app/Http/Kernel.php`

3. **Add New Features:**
   Follow the existing pattern to add new models, controllers, and views

4. **Configure Payment Gateways:**
   Edit the payment-related configurations in the `config/services.php` file

## 🤝 Contributing

We welcome contributions from the community! Here's how you can contribute to the Society Accounting Management System:

### How to Contribute

1. **Fork the repository** on GitHub
2. **Clone your fork** to your local machine
3. **Create a new branch** for your feature or bugfix:
   ```bash
   git checkout -b feature/your-feature-name
   ```
4. **Make your changes** and test thoroughly
5. **Commit your changes** with a descriptive message:
   ```bash
   git commit -m "Add: New feature description"
   ```
6. **Push to your fork**:
   ```bash
   git push origin feature/your-feature-name
   ```
7. **Open a Pull Request** against the main repository

### Development Setup

1. **Clone the repository**:
   ```bash
   git clone https://github.com/your-username/society-accounting.git
   cd society-accounting
   ```

2. **Install dependencies**:
   ```bash
   composer install
   npm install
   ```

3. **Set up the environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure your database** in the `.env` file

5. **Run migrations**:
   ```bash
   php artisan migrate --seed
   ```

6. **Compile assets**:
   ```bash
   npm run dev
   ```

7. **Start the development server**:
   ```bash
   php artisan serve
   ```

### Code Style Guidelines

1. **Follow PSR-12** coding standards
2. **Use consistent indentation** (4 spaces)
3. **Write clear, concise comments** where necessary
4. **Follow the existing code structure** and patterns
5. **Use Laravel conventions** for naming and organization
6. **Write tests** for new features and bug fixes

### Pull Request Process

1. **Ensure your code follows the style guidelines**
2. **Write clear, descriptive commit messages**
3. **Include tests** for new functionality
4. **Document any new features** in the README
5. **Update the CHANGELOG** if applicable
6. **Submit a clear, concise pull request description**

## 📝 License

This project is open-sourced under the **MIT License**.

See the [LICENSE](LICENSE) file for more information.

## 👥 Authors & Contributors

**Maintainers:**
- [Your Name](https://github.com/your-username) - Initial work

**Contributors:**
- [Contributor Name](https://github.com/contributor-username) - Feature X
- [Contributor Name](https://github.com/contributor-username) - Bugfix Y

## 🐛 Issues & Support

### Reporting Issues

If you encounter any problems or have feature requests, please:

1. **Check existing issues** to avoid duplicates
2. **Create a new issue** with:
   - Clear description of the problem
   - Steps to reproduce
   - Expected behavior
   - Your environment details
   - Any relevant error messages

### Getting Help

- **Community Support**: Join our [Discussion Forum](link-to-forum)
- **Official Documentation**: [https://your-docs-url.com](https://your-docs-url.com)
- **Email Support**: support@societyaccounting.com

### FAQ

**Q: How do I reset my password?**
A: Visit the `/forgot-password` route and follow the instructions sent to your email.

**Q: Can I use this system for commercial purposes?**
A: Yes, this system is licensed under the MIT License which allows for both personal and commercial use.

**Q: Does this system support multi-tenancy?**
A: Yes, each society can be considered a separate tenant with its own data.

**Q: Can I customize the UI?**
A: Absolutely! The system uses AdminLTE and SCSS, making it easy to customize the appearance.

## 🗺️ Roadmap

### Planned Features

1. **Enhanced Reporting System**
   - Custom report generation
   - Export to PDF/Excel
   - Scheduled reports

2. **Mobile Application**
   - iOS and Android apps
   - Resident portal
   - Admin dashboard

3. **Advanced Analytics**
   - Financial trend analysis
   - Maintenance cost tracking
   - Occupancy statistics

4. **Integration with Payment Gateways**
   - Razorpay
   - PayPal
   - Stripe

5. **Enhanced Security Features**
   - Two-factor authentication
   - Audit logging
   - Role-based access control fine-tuning

### Known Issues

1. **Issue #1**: [Description of the issue]
   - Status: Open
   - Priority: Medium

2. **Issue #2**: [Description of the issue]
   - Status: In Progress
   - Priority: Low

### Future Improvements

1. **Improved Performance**
   - Database optimization
   - Caching strategies
   - Asynchronous task processing

2. **Enhanced User Experience**
   - Mobile-responsive design
   - Dark mode support
   - Customizable dashboards

3. **Additional Features**
   - Event management system
   - Document storage and sharing
   - Vendor management

## 🚀 Getting Started

Ready to get started with the Society Accounting Management System? Follow these steps:

1. **Install the system** as described above
2. **Set up your database** with the provided migrations
3. **Customize the system** to fit your specific needs
4. **Start managing your society** efficiently!

Join our community of users and developers to share experiences, ask questions, and contribute to the project's growth. Together, we can make society management easier and more efficient for everyone!

---

Thank you for choosing the Society Accounting Management System. We hope this tool helps you streamline your society management tasks and improve the overall experience for both administrators and residents.
