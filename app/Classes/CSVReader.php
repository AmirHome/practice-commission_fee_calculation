<?php

namespace App\Classes;

use Exception;
use Illuminate\Support\Facades\Cache;
use App\Classes\Commission;


class CSVReader
{

    public function read(String $fileName){
        try {
            if (! file_exists(public_path($fileName))) {
                throw new Exception("$fileName was not found");
            }
            // Read a CSV file
            $handle = fopen(public_path($fileName), "r");

            // Init result
            $data = [];

            // Optionally, you can keep the number of the line where
            // the loop its currently iterating over
            $lineNumber = 1;

            // Iterate over every line of the file
            while (($raw_string = fgets($handle)) !== false) {
                // Parse the raw csv string: "1, a, b, c"
                $row = str_getcsv($raw_string);

                /*
                 * TODO: Optimize, to Process here
                */
                // And do what you need to do with every line
                // Calculate Commission for this Row
                $commission = new commission($row);
                $data[] = $commission->fee();

                // Increase the current line
                $lineNumber++;
            }
            fclose($handle);
            return ['error'=>0, 'data'=>$data];
        }catch(Exception $e) {
            return ['error'=>1, 'msg'=>$e->getMessage(), 'data'=>[]];
        }

    }

}
