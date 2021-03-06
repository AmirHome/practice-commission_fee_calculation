<?php

namespace App\Console\Commands;

use App\Classes\CSVReader;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class Script extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate {file_name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Input CSV file to calculate commission fee';

    private CSVReader $csvReader;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(CSVReader $csvReader)
    {
        $this->csvReader = $csvReader;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $fileName = $this->argument('file_name');

        $input = $this->csvReader->read($fileName);

        if (!empty($input->error)) {
            echo $input['msg'];
            return Command::FAILURE;
        } else {

            $this->print($input['data']);
            return Command::SUCCESS;
        }

    }

    public function print($fees)
    {
        foreach ($fees as $fee) {
            echo number_format( $fee,2) . "\n\r";
        }

    }
}
