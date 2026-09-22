## Setup & Installation

Execute these commands to set up the project locally:

composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve

## Default login credentials for Api

Email - admin@erp.com
Password - password123

## Running Tests

php artisan test

## Database Indexing & Optimization Decisions

 - Faster Reports: Added a combined index on supplier_id and status in the purchase_orders table. This allows the system to quickly calculate total spend for RECEIVED orders without scanning through every row in the database.

 - Preventing Inventory Errors : Used lockForUpdate() inside a database transaction when marking orders as RECEIVED. This locks the product rows temporarily so two simultaneous requests can't update stock at the same time and cause wrong inventory counts.

 - Efficient Data Loading (Eager Loading): Used with() when querying database models to fetch related items and suppliers in a single query instead of making separate queries for every item (avoiding the N+1 query problem).

 ## Api collection shared externally
