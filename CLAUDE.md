# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

TEFA Canning Transaction & Monitoring System - A web-based transaction system for Teaching Factory (TEFA) Canning at Politeknik Negeri Jember. The system addresses SNI certification constraints through a Pre-Order (PO) Batch model integrated with campus events.

**Core Business Model:** Pre-Order Berbasis Batch - Customers can only place orders during specific batch periods that are tied to campus events. This model ensures production volume matches actual demand and complies with SNI certification requirements.

**Primary Goals:**
- Digitalize order management and customer data
- Monitor sales volume through visual dashboards
- Automate financial reporting (Revenue, Profit, Capital)
- Provide role-based access control for operational efficiency

## Tech Stack

**Backend:**
- PHP 8.2+ (Current: 8.3.29)
- Laravel 10+ (Current: 10.50.0)
- MariaDB with Eloquent Soft Deletes

**Frontend:**
- Tailwind CSS
- DaisyUI component library
- ApexCharts for data visualization

**Admin Panel:**
- FilamentPHP (Admin interface builder)
- Filament Shield (Role-based access control)

**Key Libraries & APIs:**
- **Fonnte API** - WhatsApp automatic notifications
- **Spatie Laravel Permission** - User role management
- **Spatie Activity Log** - Audit trail for admin actions
- **Laravel Excel (Maatwebsite)** - Export reports to Excel
- **DomPDF** - Generate PDF reports
- **ApexCharts.php** - Chart visualization integration

## UI Identity & Colors

```css
Primary:  #DC2626 (Red-600)
Accent:   #EF4444 (Red-500)
Dark:     #991B1B (Red-800)
```

Use these red colors consistently across the application for brand identity.
Admin panel uses FilamentPHP v3 with custom red palette, Inter font, and SPA mode.

## Design Decisions

- **Admin Panel (Filament):** Red theme, collapsible sidebar, NavigationGroup objects, database notifications, red Polije logo for dark mode
- **Customer Panel (Filament):** Separate Filament panel at `/customer` with customer guard, registration with phone/address/organization fields, pre-order page, order history with edit/delete (pending only), dashboard with 4 widgets (welcome, order summary, latest batch, available products), collapsible sidebar, profile editing with active order lock
- **Public Pages:** Product catalog at `/` using Blade components (`<x-landing.*>`), dynamic batch news from database, Google Maps in footer 4-column grid
- **Navigation Badges:** Must return `(string) $count`, not raw `int`, to avoid Filament TypeError
- **Livewire:** Included automatically by Filament v3, no separate installation needed
- **PDF Reports:** DomPDF for order reports with Polije logo in header, product subtotal breakdown, 3_logo_in_1.png in footer, @page margins 25mm/20mm, downloadable from admin and customer panel
- **Dual Authentication:** `web` guard for admin users, `customer` guard for customer users (separate auth system)
- **Core Product Protection:** 3 base products (TEFA-SST-001, TEFA-ASN-001, TEFA-SSC-001) cannot be deleted by anyone, enforced at model level via `booted()` and UI level via hidden delete actions
- **User Management:** UserResource in admin panel, visible only to super_admin, super_admin accounts cannot be deleted, users cannot delete themselves
- **Order Edit by Customer:** Pending orders can be edited (products/quantities) but batch cannot be changed. Non-pending orders are locked.
- **Price Integrity:** Pre-order submission uses server-side product price lookup, never trusts form-submitted unit_price
- **Profile Edit Lock:** Customer cannot edit profile info when orders are in `processing` or `ready` status. Password change is always available.
- **Order Helpers:** Use `Order::generateOrderNumber()` and `Order::generatePickupCode()` instead of inline `Str::random()`. Use `Order::getStatusColor()`, `Order::getStatusIcon()`, `Order::getStatusLabel()` for consistent status display.
- **Query Optimization:** Use single consolidated queries (selectRaw) instead of multiple count/sum queries. Cache `hasActiveOrders()` result in properties to avoid repeated DB calls.
- **Fonnte Device:** config/services.php includes `device` parameter from `FONNTE_DEVICE` env var. FonnteService sends device param when configured.

## User Roles & Permissions

The system implements strict two-tier access control:

### Superadmin (Full Access)
**Capabilities:**
- Full user management: Create, edit, delete admin accounts
- **Exclusive access** to edit product prices
- Full financial reports access: Revenue (Omzet), Profit, and Capital (Modal)
- View audit logs (Activity Log) to track all admin actions
- Restore soft-deleted data
- Modify all system settings

**Technical Implementation:**
- Role: `superadmin` in Spatie Laravel Permission
- Laravel Policy check: Only Superadmin can update `price` field on Products
- Can access all Filament resources including financial reports

### Teknisi / Admin Biasa (Operational Access)
**Capabilities:**
- Update batch status: Pending → Processing → Ready for Pickup
- View order volume (unit counts) for production planning
- Validate customer pickup via QR code scan or manual verification
- **Cannot view** financial data (total revenue, profit)
- **Cannot edit** product prices
- **Cannot permanently delete** data (soft delete only)

**Technical Implementation:**
- Role: `teknisi` or `admin` in Spatie Laravel Permission
- Filament Shield policies restrict access to financial resources
- Blocked from `App\Policies\ProductPolicy::updatePrice()`
- Dashboard shows only production metrics, not financial summaries

## Core Business Logic

### Batch-Based Pre-Order System

**Batch Lifecycle:**
1. **Open** - Customers can browse and place orders
2. **Processing** - Admin closes batch, production starts (orders locked)
3. **Ready for Pickup** - Production complete, items ready for collection
4. **Closed** - All items picked up, batch archived

**Key Rules:**
- Users can only shop when batch status is `Open`
- Batch changes trigger notifications (see Notification Triggers below)
- Each batch is linked to a campus event for scheduling

### Master Data Pelanggan (Customer Database)

**Features:**
- Persistent customer database (not deleted after orders)
- Admin sees dropdown of existing customers when creating orders
- Customer profile shows complete transaction history
- Supports recurring customers tracking

**Implementation:**
- `Customer` model with soft deletes
- `Order` belongs to `Customer`
- Filament resource: CustomerResource with order relationship table

### Pickup Code System

Each order generates a unique `pickup_code` for:
- QR code generation (future integration)
- Manual pickup validation by Teknisi
- Preventing unauthorized item collection

**Database Schema:**
```php
$table->string('pickup_code')->unique();
$table->timestamp('picked_up_at')->nullable();
```

## Notification Triggers (Fonnte API)

### Trigger 1: Order Confirmation
**When:** Admin creates a new order
**Action:** Send WhatsApp message to customer with:
- Order details (items, quantities)
- Total amount
- Pickup code (for future QR scanning)
- Estimated ready date (from batch)

### Trigger 2: Ready for Pickup
**When:** Teknisi changes batch status to "Ready for Pickup"
**Action:** Send WhatsApp message to all customers in batch:
- Notification that items are ready
- Pickup location and hours
- Reminder to bring pickup code

### Trigger 3: New Customer Order → Superadmin
**When:** Customer submits pre-order via customer panel
**Action:** Send WhatsApp message to all super_admin users (with phone number) containing:
- Order number and customer name
- Product list and total amount
- Prompt to check admin panel

**Implementation Notes:**
- Fonnte API endpoint: `https://api.fonnte.com/send`
- Store Fonnte token in `.env`: `FONNTE_TOKEN=your_token_here`
- Queue notifications to avoid blocking admin actions
- `FonnteService::sendNewOrderToSuperAdmin()` queries `User::role('super_admin')->whereNotNull('phone')`

## Development Rules & Requirements

### 1. Laravel Policies for Security

**Product Price Editing:**
```php
// app/Policies/ProductPolicy.php
public function updatePrice(User $user, Product $product)
{
    return $user->hasRole('superadmin');
}
```

**Data Deletion:**
```php
// Soft delete enforcement
public function forceDelete(User $user, Model $model)
{
    return $user->hasRole('superadmin');
}
```

### 2. SNI Disclaimer Display

**Requirement:** Display mandatory SNI disclaimer on product catalog page before checkout.

**Implementation:**
- Add disclaimer banner component at top of `resources/views/products/catalog.blade.php`
- Include checkbox: "I acknowledge this product is produced under TEFA learning conditions"
- Store acknowledgment in order metadata

**Sample Disclaimer Text:**
> ⚠️ **Disclaimer SNI:** Produk TEFA Canning diproduksi dalam lingkungan pembelajaran Teaching Factory. Produk telah melalui proses quality control standar namun mungkin memiliki variasi minor yang tidak memengaruhi kualitas dan keamanan pangan.

### 3. Audit Logging with Spatie

All sensitive actions must be logged:
- Price changes (who changed what and when)
- Order creation/modification/deletion
- User management changes
- Batch status transitions

**Implementation:**
```php
use Spatie\Activitylog\Traits\LogsActivity;

class Product extends Model
{
    use LogsActivity;

    protected static $logAttributes = ['name', 'price', 'description'];
    protected static $logOnlyDirty = true;
    protected static $logName = 'product';
}
```

View audit logs in Filament: `ActivityLogResource` (Superadmin only)

### 4. Soft Delete Implementation

All major models must use soft deletes:
- Customers
- Products
- Orders
- Batches

```php
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
}
```

## Database Architecture

### Key Models & Relationships

```
Customer
  - hasMany(Order)
  - softDeletes()

Batch
  - hasMany(Order)
  - enum status: open, processing, ready, closed
  - event_name (campus event)
  - event_date

Product
  - belongsToMany(Order) - pivot: order_product
  - price (protected: Superadmin only)

Order
  - belongsTo(Customer)
  - belongsTo(Batch)
  - hasMany(OrderItem)
  - string pickup_code (unique)
  - timestamp picked_up_at (nullable)

OrderItem (pivot: order_product)
  - quantity
  - unit_price (snapshot at time of order)
```

### Migrations Priority Order

1. `customers_table` - Master data
2. `batches_table` - Order grouping
3. `products_table` - Catalog
4. `orders_table` - Transaction header
5. `order_product_table` - Order line items
6. `activity_log_table` - Spatie (run php artisan vendor:publish)

## Dashboard Requirements

### For Superadmin
**Financial Metrics:**
- Total Revenue (Omzet) - ApexCharts line chart
- Total Profit - ApexCharts area chart
- Capital (Modal) breakdown

**Operational Metrics:**
- Active batch status
- Total orders per batch
- Customer acquisition trends

### For Teknisi
**Production Metrics Only:**
- Current batch order volume (unit count)
- Products to produce summary
- Pending pickup count
- **NO financial data shown**

**Implementation:**
- Create separate dashboard widgets in Filament
- Use conditional rendering based on user role
- ApexCharts widgets for visual trends

## Common Development Commands

```bash
# Development server
php artisan serve

# Run migrations
php artisan migrate

# Fresh database (WARNING: deletes data)
php artisan migrate:fresh --seed

# Install Filament
php artisan filament:install --panels
php artisan make:filament-resource Product
php artisan make:filament-resource Customer

# Create Policies
php artisan make:policy ProductPolicy --model=Product

# Install dependencies
composer require filament/filament:"^3.0"
composer require filament/spatie-laravel-permission-plugin
composer require spatie/laravel-permission
composer require spatie/laravel-activitylog
composer require maatwebsite/excel
composer require barryvdh/laravel-dompdf

# Publish Spatie configs
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider"

# Create admin user
php artisan make:filament-user
```

## Testing Strategy

1. **Unit Tests:** Policies (Superadmin vs Teknisi access)
2. **Feature Tests:** Batch status transitions, notification triggers
3. **Browser Tests:** Filament admin workflows with Dusk

## Security Considerations

- All routes with Filament middleware are auto-protected
- Custom policies required for price editing and deletion
- API tokens (Fonnte) stored in environment variables only
- Pickup codes must be cryptographically secure (use `Str::random(16)`)
- Validate batch is "Open" before allowing order creation

## Known Limitations & Future Enhancements

- QR code scanning for pickup (future mobile app integration)
- Payment gateway integration (currently manual recording)
- Multi-warehouse support
- SMS notifications fallback

## Prompt Rules & Development Guidelines

### When Working on This Project
1. **Language:** Use Bahasa Indonesia for all UI labels, notifications, form fields, and user-facing text. Use English for code comments and variable names.
2. **No Confirmation Needed:** Just implement features directly. Ask only for crucial/destructive operations (e.g., database wipe, deleting files).
3. **Red Theme:** Always use `#DC2626` as primary color. Never introduce other brand colors.
4. **Filament Conventions:** Follow Filament v3 patterns — Pages for custom views, Resources for CRUD, Widgets for dashboard cards.
5. **Security First:** Never expose API tokens or credentials. Always use `.env` variables via `config()`.
6. **Server-Side Price:** Never trust client-submitted prices. Always look up from database.
7. **Soft Deletes:** All major models must use `SoftDeletes` trait.
8. **Activity Log:** All models should use `LogsActivity` trait for audit trail.
9. **Guard Awareness:** Customer panel uses `customer` guard. Admin panel uses `web` guard. Always specify the guard explicitly.
10. **Status Badge Types:** Return `(string)` from `getNavigationBadge()`, never raw `int`.
11. **Edit Tool:** When modifying existing files, use `replace_string_in_file` — never `create_file` on existing files.
12. **Order Helpers:** Use `Order::generateOrderNumber()`, `Order::generatePickupCode()`, `Order::getStatusColor()`, `Order::getStatusLabel()`, `Order::getStatusIcon()` for consistency.
13. **Query Optimization:** Consolidate multiple COUNT/SUM queries into single selectRaw. Cache repeated boolean checks in properties.

### Prompt Suggestions for Common Tasks
- "Tambahkan fitur [X] di panel customer" → Creates new Page in `app/Filament/Customer/Pages/`
- "Buat widget baru di dashboard" → Creates Widget + Blade view, registers in Dashboard
- "Kirim notifikasi WhatsApp saat [event]" → Uses `FonnteService::sendMessage()`
- "Tambah kolom di tabel [model]" → Creates migration, updates model fillable/casts, updates Resource form/table
- "Proteksi [resource] dari [action]" → Model booted() guard + UI hidden action
- "Export data ke PDF" → DomPDF view + controller route

## Architecture: Multi-Panel Filament System

### Admin Panel (`/admin`)
- **Guard:** `web` (User model)
- **Roles:** `super_admin`, `teknisi`
- **Resources:** BatchResource, CustomerResource, ProductResource, OrderResource, UserResource (super_admin only)
- **Widgets:** DashboardStatsWidget (financial charts), RecentOrdersWidget (table)
- **Features:** NavigationGroups, database notifications, brand logo (red Polije for dark mode), SPA mode
- **Product Protection:** 3 core products (TEFA-SST/ASN/SSC-001) cannot be deleted
- **User Management:** Super admin can CRUD users, cannot delete other super_admins or self

### Customer Panel (`/customer`)
- **Guard:** `customer` (Customer model)
- **Provider:** `CustomerPanelProvider`
- **Pages:** Dashboard (4 widgets), PreOrder (form-based ordering), OrderHistory (table with edit/delete), EditOrder (modify pending orders), EditProfile (profile & password with active order lock)
- **Widgets:** WelcomeWidget (profile card), OrderSummaryWidget (4 stats, single query), LatestBatchWidget (batch info + CTA), AvailableProductsWidget (product list + prices)
- **Registration:** Custom form with name, email, phone, organization, address, password
- **Auth:** Customer model extends Authenticatable, implements FilamentUser
- **Order Rules:** Edit/delete only when status is `pending`, batch cannot be changed on edit

### Public Landing Page (`/`)
- **Components:** `<x-landing.layout>`, `<x-landing.navbar>`, `<x-landing.footer>`, `<x-landing.product-card>`
- **Sections:** Hero, Product catalog, Batch news (dynamic), SNI disclaimer, About
- **Footer:** 4-column grid — Brand, Location, Navigation, Google Maps widget (embedded in footer right column)

## File Structure (Key Custom Files)

```
app/
├── Filament/
│   ├── Customer/
│   │   ├── Pages/                   # Customer panel pages
│   │   │   ├── Auth/Register.php    # Custom registration form
│   │   │   ├── Dashboard.php        # Dashboard with 4 widgets
│   │   │   ├── EditOrder.php        # Edit pending order (batch locked)
│   │   │   ├── EditProfile.php      # Profile editing with active order lock
│   │   │   ├── OrderHistory.php     # Table with edit/delete/PDF actions
│   │   │   └── PreOrder.php         # Form-based ordering
│   │   └── Widgets/                 # Customer dashboard widgets
│   │       ├── WelcomeWidget.php
│   │       ├── OrderSummaryWidget.php
│   │       ├── LatestBatchWidget.php
│   │       └── AvailableProductsWidget.php
│   ├── Resources/               # Admin panel resources
│   │   ├── ActivityLogResource.php  # Audit trail viewer (super_admin only)
│   │   ├── BatchResource.php
│   │   ├── CustomerResource.php
│   │   ├── OrderResource.php
│   │   ├── ProductResource.php
│   │   └── UserResource.php     # Super admin only
│   └── Widgets/
│       ├── DashboardStatsWidget.php
│       ├── BatchOrderSummaryWidget.php
│       ├── ProductionSummaryWidget.php
│       └── RecentOrdersWidget.php
├── Http/
│   ├── Controllers/OrderPdfController.php
│   └── Middleware/CustomerPanelMiddleware.php
├── Models/
│   ├── Batch.php, Customer.php, Order.php, Product.php, User.php
├── Policies/
│   ├── BatchPolicy.php, CustomerPolicy.php, OrderPolicy.php, ProductPolicy.php
├── Providers/Filament/
│   ├── AdminPanelProvider.php
│   └── CustomerPanelProvider.php
└── Services/FonnteService.php

resources/views/
├── components/landing/          # Blade components
│   ├── layout.blade.php
│   ├── navbar.blade.php
│   ├── footer.blade.php         # 4-column with Google Maps widget
│   └── product-card.blade.php
├── filament/
│   ├── brand-logo.blade.php
│   ├── brand-logo-dark.blade.php  # Red Polije logo for dark mode
│   └── customer/
│       ├── pages/
│       │   ├── pre-order.blade.php
│       │   ├── order-history.blade.php
│       │   ├── edit-order.blade.php
│       │   └── edit-profile.blade.php
│       └── widgets/
│           ├── welcome-widget.blade.php
│           ├── latest-batch-widget.blade.php
│           └── available-products-widget.blade.php
├── pdf/order-report.blade.php
└── welcome.blade.php
```

## Naming & Coding Conventions (Future Codebase)

> These conventions are for **new code going forward**. Do not rename existing files/variables.

### File Naming

| Type | Convention | Example |
|------|-----------|---------|
| Model | Singular PascalCase | `Order.php`, `BatchItem.php` |
| Migration | Laravel snake_case timestamp | `2026_02_16_000001_add_phone_to_users_table.php` |
| Filament Resource | `{Model}Resource.php` | `OrderResource.php`, `ActivityLogResource.php` |
| Resource Page | Verb + PascalCase | `ListOrders.php`, `CreateOrder.php`, `EditOrder.php`, `ViewOrder.php` |
| Filament Widget | Descriptive PascalCase + `Widget` | `DashboardStatsWidget.php`, `WelcomeWidget.php` |
| Blade View | kebab-case | `order-report.blade.php`, `welcome-widget.blade.php` |
| Blade Component | kebab-case inside `components/` | `components/landing/product-card.blade.php` |
| Seeder | `{Model}Seeder.php` | `CustomerSeeder.php`, `UserSeeder.php` |
| Factory | `{Model}Factory.php` | `CustomerFactory.php`, `UserFactory.php` |
| Service | `{Purpose}Service.php` | `FonnteService.php`, `ReportService.php` |
| Controller | `{Purpose}Controller.php` | `OrderPdfController.php` |
| Middleware | PascalCase descriptive | `CustomerPanelMiddleware.php` |
| Policy | `{Model}Policy.php` | `OrderPolicy.php`, `ProductPolicy.php` |
| Customer Page | Context-specific PascalCase | `PreOrder.php`, `EditProfile.php`, `OrderHistory.php` |

### Variable Naming

| Type | Convention | Example |
|------|-----------|---------|
| Model instance | `$camelCase` singular | `$order`, `$customer`, `$activeBatch` |
| Collection | `$camelCase` plural | `$orders`, `$openBatches`, `$activeProducts` |
| Boolean | `$is/has/can` prefix | `$isSuperAdmin`, `$hasActiveOrders`, `$canEdit` |
| Count | `$xxxCount` suffix | `$orderCount`, `$pendingPickup` |
| Amount/Money | `$xxxAmount` or `$xxxTotal` | `$totalAmount`, `$subtotal`, `$totalRevenue` |
| Query builder | `$query` inside closures | `fn(Builder $query) => $query->where(...)` |
| Form state | `$data` | `$data = $this->form->getState()` |
| Config value | descriptive camelCase | `$fonnteToken`, `$apiUrl` |

### Folder Organization

| Concern | Path | Rule |
|---------|------|------|
| Admin Resources | `app/Filament/Resources/` | One Resource per model |
| Admin Widgets | `app/Filament/Widgets/` | Dashboard-level widgets |
| Customer Pages | `app/Filament/Customer/Pages/` | Feature-specific pages |
| Customer Widgets | `app/Filament/Customer/Widgets/` | Customer dashboard widgets |
| Customer Auth | `app/Filament/Customer/Pages/Auth/` | Registration, login customization |
| Resource Pages | `app/Filament/Resources/{Resource}/Pages/` | List, Create, Edit, View pages |
| Blade Views | `resources/views/filament/customer/` | Match PHP namespace structure |
| Landing Components | `resources/views/components/landing/` | Reusable landing page parts |
| Services | `app/Services/` | Business logic / API integrations |

### Code Style Rules

1. **Labels in Bahasa Indonesia**: All Filament `->label()`, `->description()`, `->helperText()`, notification titles/bodies, empty state text — always Bahasa Indonesia.
2. **English code**: Variable names, method names, comments, class names — always English.
3. **Type hints**: Always type-hint method parameters and return types.
4. **Filament `->native(false)`**: Always set `native(false)` on Select components for consistent styling.
5. **Guard explicit**: Always specify guard — `auth('customer')->user()`, `auth()->user()` (web guard).
6. **`firstOrCreate` in seeders**: Use `firstOrCreate` to make seeders idempotent (safe to re-run).
7. **Price from DB**: Never trust form-submitted prices. Always look up `Product::find($id)->price`.
8. **Navigation groups**: Resources must specify `$navigationGroup` matching one of: Transaksi, Master Data, Manajemen Produksi, Audit & Log, Pengaturan.
9. **Soft deletes**: Every model that represents business data must use `SoftDeletes`.
10. **Activity logging**: Every model must use `LogsActivity` trait with `logFillable()`.

