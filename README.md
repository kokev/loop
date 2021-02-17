________________
# **SET UP PROJECT**

1. Install packages:
```
    -composer install
```

2. Create .env file from .env.example

3. Open .env file and set up database

4. Create database tables:
```
    -php artisan migrate 
```

5. Seed database with data (payment_providers table)
```
    -php artisan db:seed
```

6. Import CSVs with custom command 
```
    -php artisan importcsv
```

7. Serving laravel
```
    -php artisan serve
```
___________
# **ENDPOINTS**

## List of orders

    - GET api/orders

## Selected order detailed information

    - GET api/orders/{id}

## Create a new order

    - POST api/orders
    - JSON:
    ```
    {
        "customer_id": 10
    }
    ```

## Update a selected order

    - PUT api/orders/{id}
    - Request JSON:
    ```
    {
        "customer_id": 2,
        "payed": true
    }
    ```

## Delete a selected order

    - DELETE api/orders/{id}

## Add a new product to an order

    - POST api/orders/{id}/add
    - Request JSON:
    ```
    {
        "product_id": 1 
    }
    ```

## Pay an order, with a selected payment provider

    - POST api/orders/{id}/pay
    - Request JSON:
    ```
    {
        "payment_provider_id": 1
    }
    ```

## List of payment providers (prepared for more providers in the future)

    - GET api/payment-providers

__________________
# **MY TIME SCHEDULE**

