<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
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
    protected $signature = 'importcsv
                            {url : loopurl}
                            {status=200 : The expected status code}';

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
        $customerCsvUrl = "";
        $productCsvUrl = "";

        //Save file temporary
        $tmpFileName = "/tmp/" . uniqid(). '.csv';
        file_put_contents($tmpFileName, base64_decode($csv['content']));

        $newCustomers = (new FastExcel)->configureCsv($delimiter = ';')->import($customerCsvUrl, function ($line) {
            return Customer::create([
                'id' => $line['id'],
                'job_title' => $line['job_title'],
                'email_address' => $line['email_address'],
                'first_name' => $line['first_name'],
                'last_name' => $line['last_name'],
                'registered_since' => $line['registered_since'],
                'phone' => $line['phone'],
            ]);
        });

        //Delete file after import
        unlink($tmpFileName);

        $newProducts = (new FastExcel)->configureCsv($delimiter = ';')->import($customerCsvUrl, function ($line) {
            return Product::create([
                'id' => $line['id'],
                'phone' => $line['phone'],
                'price' => $line['price'],
            ]);
        });

        return Response()->json(['success' => true], 200);
    }
}
