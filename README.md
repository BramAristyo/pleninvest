# Pleninvest

## Overview

Pleninvest is a comprehensive financial management and investment tracking system designed to provide users with a centralized platform for monitoring their financial health. The application facilitates the tracking of net worth, investment portfolios, insurance policies, and reimbursements through a streamlined, data-driven interface.

## Architecture

The project follows a modern monolithic architecture with a clear separation between the backend service layer and the interactive frontend presentation.

### Backend

Built on the **Laravel Framework**, the backend serves as the core engine for:
- **Authentication**: A secure PIN-based authentication system.
- **Data Persistence**: Leveraging Eloquent ORM for managing complex financial data models.
- **RESTful API**: Exposing a consistent interface for the frontend to consume.
- **Reporting**: Integration with Excel for generating comprehensive financial exports.

### Frontend

The frontend is designed for high performance and responsiveness:
- **Template Engine**: Utilizes Laravel Blade for structural components and initial page rendering.
- **Interactive Logic**: Driven by Vanilla JavaScript to provide a Single Page Application (SPA) experience without the complexity of external frameworks.
- **Styling**: Modular CSS architecture for a professional and consistent user interface.

## Key Features

- **Net Worth Tracking**: Real-time aggregation of assets and liabilities to monitor overall financial growth.
- **Investment Management**: Comprehensive tools for tracking diverse investment portfolios and market valuations.
- **Transaction Processing**: Detailed logging, categorization, and batch processing of financial transactions.
- **Insurance and Benefits**: Centralized repository for managing policy details and employer-provided benefits.
- **Reimbursement System**: Workflow for tracking and managing the status of financial reimbursements.
- **Financial Exports**: Exporting of detailed financial data to Excel formats for offline analysis and record-keeping.

## Tech Stack

- **PHP 8.3+**
- **Laravel 13.x**
- **Vanilla JavaScript**
- **SQLite / MySQL**
- **Vite Asset Bundler**
- **Maatwebsite/Laravel-Excel**

## Getting Started

### Prerequisites

- PHP 8.3 or higher
- Composer
- Node.js and NPM
- SQLite (default) or MySQL

### Installation

1.  **Clone the repository:**
    ```bash
    git clone <repository-url>
    cd pleninvest
    ```

2.  **Run the setup script:**
    The project includes a comprehensive setup command defined in `composer.json`.
    ```bash
    composer run setup
    ```
    This script will:
    - Install PHP dependencies.
    - Create the `.env` file from `.env.example`.
    - Generate the application key.
    - Run database migrations.
    - Install NPM packages and build assets.

3.  **Start the development environment:**
    ```bash
    npm run dev
    ```
    This command concurrently runs the Laravel development server, Vite, and the queue worker.

## Project Structure

- `app/Http/Controllers`: Backend logic and API endpoint handlers.
- `app/Models`: Database schemas and Eloquent relationships.
- `database/migrations`: Version control for the database schema.
- `resources/views`: Blade templates for the application shell.
- `public/js/pleninvest.js`: Core frontend application logic.
- `public/css/pleninvest.css`: Application-specific styling.

## License

This project is licensed under the MIT License.
