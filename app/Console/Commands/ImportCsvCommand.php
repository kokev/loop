<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ImportCsvCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'importcsv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import CSVs from urls';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $customerCsvUrl = "https://loop:backend_dev@backend-developer.view.agentur-loop.com/customers.csv";
        $productCsvUrl = "https://loop:backend_dev@backend-developer.view.agentur-loop.com/products.csv";

        //Temporary save the csv
        $file = file_get_contents($customerCsvUrl);
        Storage::disk('local')->put('customers.csv', $file);

        $newCustomers = (new FastExcel)->configureCsv($delimiter = ',')->import( __DIR__ . '/../../../storage/app/customers.csv', function ($line) {
            
            //Parse date to make it ready for save to the database
            $date = date_parse($line['registered_since']);

            //Explode the names for two part
            $name = explode(" ",$line['FirstName LastName']);

            //Check if the date is right do the saving
            if(empty($date['warnings'])) {
                Customer::create([
                    'id' => $line['ID'],
                    'job_title' => $line['Job Title'],
                    'email_address' => $line['Email Address'],
                    'first_name' => $name[0],
                    'last_name' => $name[1],
                    'registered_since' => $date['year'].'-'.$date['month'].'-'.$date['day'],
                    'phone' => $line['phone'],
                ]);
            }
        });

        //Temporary save the csv
        $file = file_get_contents($productCsvUrl);
        Storage::disk('local')->put('products.csv', $file);

        $newProducts = (new FastExcel)->configureCsv($delimiter = ',')->import( __DIR__ . '/../../../storage/app/products.csv', function ($line) {
            Product::create([
                'id' => $line['ID'],
                'product_name' => $line['productname'],
                'price' => $line['price'],
            ]);
        });

        //Delete the saved files
        unlink(__DIR__ . '/../../../storage/app/customers.csv');
        unlink(__DIR__ . '/../../../storage/app/products.csv');
        
        return;
    }
}
