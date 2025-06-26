# 💒 Wedding Website

A wedding website built with Laravel for managing events, photo galleries, and guest interactions.

**🌐 Live website:** [https://merceyhermes.com](https://merceyhermes.com)

## 🎯 Project Overview

Wedding website built in Laravel that includes photo galleries, gift registry, event scheduling, and guest management features. Implements custom image processing and automated email workflows.

## ✨ Features

### 🖼️ Gallery System
- **Image Organization** - Categorization based on filename patterns
- **Thumbnail Generation** - Artisan commands for image processing
- **Category Management** - Ceremony, reception, couple, guests, and detail photos
- **Bulk Download** - ZIP generation for photo collections
- **Category Filtering** - Frontend filtering by photo type

### 🎁 Gift Registry
- **Reservation System** - Track gift availability and reservations
- **Email Verification** - Confirmation workflows via email
- **Guest Messages** - Personal messages with gift reservations
- **Cancellation** - Self-service reservation cancellation

### 📅 Event Management
- **Timeline** - Multiple event scheduling (ceremony, reception, etc.)
- **Calendar Export** - ICS file generation
- **Venue Information** - Location details and maps integration
- **Accommodations** - Hotel listing with discount information

### 🛠️ Technical Stack

#### Backend
- **Laravel 11** - PHP framework
- **PHP 8.2+** - Server-side language
- **Eloquent ORM** - Database interactions
- **Artisan Commands** - Custom CLI tools for gallery and data management
- **Queue System** - Background job processing

#### Frontend
- **Vite** - Build tool
- **Bootstrap 5.3** - CSS framework
- **JavaScript** - Frontend interactions
- **FontAwesome** - Icons

#### Database
- **SQLite** (development) / **MySQL** (production)
- **Migrations** - Schema versioning
- **Seeders** - Sample data

## 🏗️ Implementation Details

### Custom Commands
```bash
gallery:generate-thumbnails    # Image processing and thumbnail creation
gallery:migrate-structure      # Legacy gallery migration tools
gallery:clean-orphans         # Clean unused thumbnail files
```

### Image Processing
- **File Detection** - Scans directories for image files
- **Category Classification** - Organizes by filename prefixes
- **Thumbnail Creation** - Generates multiple sizes
- **Storage Management** - Maintains organized file structure

### ImageHelper Utility
- **Directory Scanning** - Recursive image discovery in gallery folders
- **Category Extraction** - Automatic categorization from filename patterns
- **Image Information** - Width, height, filesize extraction
- **Thumbnail Generation** - Multiple size variants for different display contexts
- **File System Operations** - Directory creation and file management

### Database Seeders
Complete data seeding system for testing and initial setup:
- **CeremonySeeder** - Wedding ceremony details and timing
- **ReceptionSeeder** - Reception venue and schedule information  
- **GiftSeeder** - Gift registry items with prices and descriptions
- **PartySeeder** - Evening celebration details
- **AppetizerSeeder** - Cocktail hour information
- **FeastSeeder** - Dinner event scheduling
- **AccommodationsSeeder** - Hotel recommendations and discounts
- **GallerySeeder** - Integrates with ImageHelper for photo database setup

### Database Schema
The application uses multiple specialized tables:
- **ceremonies** - Wedding ceremony details (venue, time, maps)
- **receptions** - Reception event information
- **parties** - Evening celebration details
- **appetizers** - Cocktail hour scheduling
- **feasts** - Dinner event information
- **accommodations** - Hotel listings with discount information
- **galleries** - Photo metadata with categories and thumbnails
- **gifts** - Registry items with reservation tracking
- **users** - Basic authentication setup
- **sessions** - Session management

### Email System
- **SMTP Configuration** - Email delivery setup
- **Blade Templates** - Email template system
- **Queue Processing** - Background email sending
- **Verification Links** - Automated confirmation emails

## 📁 Application Structure

```
├── app/
│   ├── Console/Commands/        # Custom Artisan commands
│   │   ├── GenerateGalleryThumbnails.php
│   │   └── MigrateGalleryStructure.php
│   ├── Http/Controllers/        # MVC Controllers
│   │   ├── GalleryController.php
│   │   ├── GiftController.php
│   │   ├── ScheduleController.php
│   │   └── StoryController.php
│   ├── Models/                  # Eloquent Models
│   │   ├── Gallery.php
│   │   ├── Gift.php
│   │   ├── Ceremony.php
│   │   ├── Reception.php
│   │   ├── Party.php
│   │   └── Accommodation.php
│   └── Helpers/                 # Utility Classes
│       └── ImageHelper.php      # Image processing utilities
├── database/
│   ├── migrations/              # Schema definitions
│   ├── seeders/                 # Data population
│   │   ├── DatabaseSeeder.php   # Main seeder orchestrator
│   │   ├── GallerySeeder.php    # Gallery data with ImageHelper integration
│   │   ├── GiftSeeder.php       # Gift registry items
│   │   └── Event seeders/       # Ceremony, reception, party data
│   └── factories/               # Model factories for testing
├── resources/
│   ├── views/                   # Blade templates
│   ├── css/                     # Stylesheets
│   └── js/                      # Frontend JavaScript
└── public/images/               # Media assets
    ├── gallery/                 # Wedding photos organized by category
    └── story/                   # Relationship timeline images
```

## 🔧 Configuration

### Maintenance Mode
- **Preview Access** - Token-based access during development
- **Environment Toggle** - Enable/disable via environment variables
- **Route Handling** - Redirect to maintenance page when enabled

### Development Setup
- **Concurrent Services** - Multiple services via composer script
- **Asset Building** - Vite for CSS/JS compilation
- **Database Tools** - Migration and seeding commands

### Deployment
- **GitHub Actions** - Automated deployment workflow
- **Environment Variables** - Configuration via .env files
- **Asset Optimization** - Production build process

## 📊 Tech Stack

| Component | Technology | 
|-----------|------------|
| **Backend** | Laravel 11, PHP 8.2 |
| **Frontend** | Vite, Bootstrap 5, JavaScript |
| **Database** | SQLite/MySQL |
| **Email** | SMTP |
| **Deployment** | GitHub Actions |
| **Media** | Custom image processing |

---

<div align="center">

*Wedding website built with Laravel*

</div>
