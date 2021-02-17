<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\Customer;
use App\Models\Product;

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

        echo "\n- Customers import: \e[0;32mSTARTED\e[0m";
        echo "\n- Processing...";

        $countRows = 0;
        $newCustomers = (new FastExcel)->configureCsv($delimiter = ',')->import( __DIR__ . '/../../../storage/app/customers.csv', function ($line) {

            //Counting lines
            global $countRows;
            $countRows += 1;
            
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
            } else {
                //Log failed rows
                echo "\n- Failed row: \e[0;31m".$line['ID']." | ".
                                                $line['Job Title']." | ".
                                                $line['Email Address']." | ".
                                                $line['FirstName LastName']." | ".
                                                $line['registered_since']."\e[0m";

                Log::info("Failed row: ".$line['ID']." | ".
                                            $line['Job Title']." | ".
                                            $line['Email Address']." | ".
                                            $line['FirstName LastName']." | ".
                                            $line['registered_since']);
            }
        });

        global $countRows;
        $allRow = Customer::count();

        echo "\n- Customers import: \e[0;32mFINISHED\e[0m";
        echo "\n- All/Success: \e[0;32m".$countRows."/".$allRow."\e[0m";
        Log::info("CUSTOMERS_CSV_IMPORT_RESULT (all\success): ".$countRows."/".$allRow);

        //Temporary save the csv
        $file = file_get_contents($productCsvUrl);
        Storage::disk('local')->put('products.csv', $file);

        echo "\n- Products import: \e[0;32mSTARTED\e[0m";
        echo "\n- Processing...";

        $countRows = 0;
        $newProducts = (new FastExcel)->configureCsv($delimiter = ',')->import( __DIR__ . '/../../../storage/app/products.csv', function ($line) {

           //Counting lines
           global $countRows;
           $countRows += 1;

            Product::create([
                'id' => $line['ID'],
                'product_name' => $line['productname'],
                'price' => $line['price'],
            ]);
        });

        global $countRows;
        $allRow = Product::count();

        echo "\n- Products import: \e[0;32mFINISHED\e[0m";
        echo "\n- All/Success: \e[0;32m".$countRows."/".$allRow."\e[0m";
        Log::info("PRODUCTS_CSV_IMPORT_RESULT (all\success): ".$countRows."/".$allRow);

        //Delete the saved files
        unlink(__DIR__ . '/../../../storage/app/customers.csv');
        unlink(__DIR__ . '/../../../storage/app/products.csv');
        
        echo "\n- Temporary csv files deleted: \e[0;32mOK\e[0m";

        return;
    }
}
