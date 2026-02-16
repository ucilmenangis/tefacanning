# TEFA Canning Transaction & Monitoring System

Sistem transaksi dan monitoring berbasis web untuk Teaching Factory (TEFA) Canning di Politeknik Negeri Jember. Mengelola pre-order sarden kaleng berbasis batch, dengan panel admin dan customer terpisah.

## Tech Stack

- **Backend:** PHP 8.3 & Laravel 10.50
- **Frontend:** Tailwind CSS (CDN for public, Filament built-in for admin)
- **Admin Panel:** FilamentPHP v3 with Filament Shield
- **Customer Panel:** FilamentPHP v3 (separate panel with customer auth)
- **Database:** MariaDB (Eloquent Soft Deletes)
- **PDF:** DomPDF (order reports)
- **API:** Fonnte API (WhatsApp notifications)
- **Font:** Inter (Google Fonts via bunny.net CDN)

## Features

### 🏪 Landing Page (`/`)
- Product catalog (3 varian sarden: Saus Tomat, Asin, Saus Cabai)
- Dynamic batch news (open batches from database)
- Google Maps widget in footer (4-column layout)
- SNI disclaimer section
- Blade component architecture (`<x-landing.*>`)
- Responsive, minimalistic red-themed design

### 👤 Customer Panel (`/customer`)
- Customer registration (name, email, phone, organization, address)
- Customer login with separate auth guard
- **Dashboard widgets:** Welcome card, order summary stats, latest batch info, available products list
- **Pre-Order page:** Select batch, add products (min 100, max 3000 kaleng), auto-calculate subtotals
- **Order History:** Table with status badges, pickup codes, PDF download, edit & delete actions (pending only)
- **Edit Order:** Modify products/quantities on pending orders (batch locked)
- Red-themed Filament panel with collapsible sidebar

### 🔧 Admin Panel (`/admin`)
- **Batch Management:** Create/manage production batches tied to campus events
- **Order Management:** Full CRUD, pickup validation, PDF download, status workflow
- **Product Management:** SKU, pricing, stock tracking, image upload (3 core products protected from deletion)
- **Customer Database:** Persistent records with transaction history
- **User Management:** CRUD for admin users (Super Admin only), cannot delete super_admin accounts
- **Dashboard:** Financial stats (revenue, profit, capital) with charts, recent orders table
- **Role-Based Access:** Superadmin vs Teknisi permissions
- **Audit Log:** Spatie Activity Log on all models
- Dark mode with red Polije logo

### 📄 PDF Reports
- Order report with customer info, batch details, product table, totals
- Pickup code section
- Professional A4 layout with TEFA branding
- Download/stream from admin and customer panel

## User Roles

### Superadmin (Full Access)
- Full user management
- Edit product prices
- Financial reports access (Omzet, Profit, Modal)
- Audit log review
- Restore soft-deleted data

### Teknisi (Operational Access)
- Update batch status
- Monitor order volume
- Validate pickup
- No financial data or permanent deletion access

### Customer (Self-Service)
- Register/login via `/customer`
- Place pre-orders during open batches
- View order history and pickup codes
- Download order PDF reports

## Installation

### Prerequisites

- PHP 8.2 or higher
- Composer
- MariaDB or MySQL
- Node.js & NPM (for frontend build)

### Step 1: Install Dependencies

```bash
composer install
npm install
```

### Step 2: Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
```

Configure your `.env` file:

```env
APP_NAME="TEFA Canning System"
APP_ENV=local
APP_KEY=base64:your_generated_key
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tefa_canning_db
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

# Fonnte API Configuration
FONNTE_TOKEN=your_fonnte_api_token
```

### Step 3: Database Setup

```bash
php artisan migrate
php artisan db:seed
```

### Step 4: Install Filament & Dependencies

```bash
# Install Filament Panel
php artisan filament:install --panels

# Install Filament Shield for role management
composer require filament/spatie-laravel-permission-plugin
php artisan filament:install --shield

# Install other required packages
composer require spatie/laravel-permission
composer require spatie/laravel-activitylog
composer require maatwebsite/excel
composer require barryvdh/laravel-dompdf

# Publish configs
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider"
```

### Step 5: Create Admin User

```bash
php artisan make:filament-user
```

Follow the prompts to create a Superadmin account.

## Development Commands

```bash
# Start development server
php artisan serve

# Build frontend assets
npm run dev

# Run tests
php artisan test

# Code formatting
./vendor/bin/pint

# Clear cache
php artisan optimize:clear
```

## Access Points

- **Landing Page:** `http://localhost:8000`
- **Admin Panel:** `http://localhost:8000/admin`
- **Customer Panel:** `http://localhost:8000/customer`
- **Order PDF:** `http://localhost:8000/order/{id}/pdf`

## Project Structure

```
app/
├── Filament/
│   ├── Customer/
│   │   ├── Pages/              # Customer panel (PreOrder, OrderHistory, EditOrder, Dashboard)
│   │   │   └── Auth/Register.php   # Custom registration form
│   │   └── Widgets/            # Dashboard widgets (Welcome, OrderSummary, LatestBatch, AvailableProducts)
│   ├── Resources/              # Admin panel resources (Batch, Customer, Order, Product, User)
│   └── Widgets/                # Admin dashboard widgets (Stats, RecentOrders)
├── Http/
│   ├── Controllers/            # OrderPdfController
│   └── Middleware/             # CustomerPanelMiddleware
├── Models/                     # Eloquent models with SoftDeletes & LogsActivity
├── Policies/                   # Authorization policies
├── Providers/Filament/         # AdminPanelProvider, CustomerPanelProvider
└── Services/                   # FonnteService

resources/views/
├── components/landing/         # Blade components (layout, navbar, footer, product-card)
├── filament/                   # Brand logos, customer panel views
├── pdf/                        # Order report PDF template
└── welcome.blade.php           # Landing page
```

## UI Colors

- **Primary:** #DC2626 (Red-600)
- **Accent:** #EF4444 (Red-500)
- **Dark:** #991B1B (Red-800)

## Security Notes

- All admin routes are protected by Filament authentication
- Price editing restricted to Superadmin via Laravel Policies
- Soft deletes enabled on all major models
- All sensitive actions logged via Spatie Activity Log
- Pickup codes generated with cryptographically secure random strings

## API Integrations

### Fonnte API (WhatsApp Notifications)

**Trigger 1 - Order Confirmation:**
Sent when admin creates a new order with order details and pickup code.

**Trigger 2 - Ready for Pickup:**
Sent when batch status changes to "Ready for Pickup" to notify all customers.

## License

This project is developed for TEFA Canning Politeknik Negeri Jember.

## Support

For detailed development guidance, see [CLAUDE.md](./CLAUDE.md).
