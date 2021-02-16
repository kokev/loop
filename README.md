#SET UP PROJECT#

1. Install packages:
    -composer install

2. Create .env file from .env.example

3. Open .env file and set up database

4. Create database tables:
    -php artisan migrate 

5. Seed database with data (payment_providers table)
    -php artisan db:seed

6. Import CSVs with custom command 
    -php artisan importcsv


#ENDPOINTS

List of orders
    -GET api/orders

Selected order detailed information
    -GET api/orders/{id}

Create a new order
    -POST api/orders
    -JSON:

Update a selected order
    -PUT api/orders/{id}
    -JSON:

Delete a selected order
    -DELETE api/orders/{id}

Add a new product to an order
    -POST api/orders/{id}/add
    -JSON:

Pay an order, with a selected payment provider
    -POST api/orders/{id}/add
    -JSON:

List of payment providers (prepared for more providers in the future)
    -GET api/payment-providers

TODO:
    -customer és product lista végpont (csak hogy lehessen az importált adatokból )
    -logs