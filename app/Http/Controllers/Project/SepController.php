<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use TitasGailius\Terminal\Terminal;

class SepController extends Controller
{
    /**
     * Get authenticated user.
     */
    public function requestOPT(Request $request)
    {
        $workspace_dir = $request->user()->id;

        //
        // create workspace directory with user_id
        //
        $cmd_create_dir = 'mkdir ' . $workspace_dir;
        $output = Terminal::in(storage_path('executables'))->run($cmd_create_dir);

        //
        // copy SEPOPT.exe into workspace directory
        //
        $cmd_copy_sepopt = 'copy SEPOPT.exe ' . $workspace_dir;
        $output = Terminal::in(storage_path('executables'))->run($cmd_copy_sepopt);
        if ($output->successful() == false)  {
            error_log('Error happened to copy SEPOPT.exe');
            return response()->json([
                []
            ]);    
        }

        //
        // create SEP.in file inside workspace 
        //
        $originalStreamComposition1 = $request->get('originalStreamComposition1');
        $originalStreamComposition2 = $request->get('originalStreamComposition2');
        $saturatedReservoirConditions = $request->get('saturatedReservoirConditions');
        $separatorConditions = $request->get('separatorConditions');

        $cmd_sepopt_in_file = $workspace_dir . '/SEP.in';
        $content = '';
        $content = $content . $originalStreamComposition1["N2"] . '  ';
        $content = $content . $originalStreamComposition1["CO2"] . '  ';
        $content = $content . $originalStreamComposition1["H2S"] . '  ';
        $content = $content . $originalStreamComposition1["C1"] . '  ';
        $content = $content . $originalStreamComposition1["C2"] . '  ';
        $content = $content . $originalStreamComposition1["C3"] . '  ';
        $content = $content . $originalStreamComposition1["iC4"] . '  ';
        $content = $content . $originalStreamComposition1["nC4"] . '  ';
        $content = $content . $originalStreamComposition1["iC5"] . '  ';
        $content = $content . $originalStreamComposition1["nC5"] . '  ';
        $content = $content . $originalStreamComposition1["C6"] . '  ';
        $content = $content . $originalStreamComposition1["C7"] . PHP_EOL;

        $content = $content . $originalStreamComposition2["MolecularWeight"] . '  ';
        $content = $content . $originalStreamComposition2["SpecificGravity"] . PHP_EOL;

        $content = $content . $saturatedReservoirConditions["PSAT"] . '  ';
        $content = $content . $saturatedReservoirConditions["TRES"] . PHP_EOL;

        // decide the rows
        $rows = 0;
        $rows = count($separatorConditions["setNumber1"]);
        if ($rows == 3 && $separatorConditions["setNumber1"][2][0] == '') {
            $rows = 2;
        }

        // create set numbers
        if ($separatorConditions["setNumber1"][0][0] != '') 
        {
            $content = $content . $separatorConditions["setNumber1"][0][0] . '  ';
            $content = $content . $separatorConditions["setNumber1"][0][1] . PHP_EOL;
            $content = $content . $separatorConditions["setNumber1"][1][0] . '  ';
            $content = $content . $separatorConditions["setNumber1"][1][1] . PHP_EOL;
            if ($rows == 3) {
                $content = $content . $separatorConditions["setNumber1"][2][0] . '  ';
                $content = $content . $separatorConditions["setNumber1"][2][1] . PHP_EOL;    
            }    
        }

        if ($separatorConditions["setNumber2"][0][0] != '') 
        {
            $content = $content . $separatorConditions["setNumber2"][0][0] . '  ';
            $content = $content . $separatorConditions["setNumber2"][0][1] . PHP_EOL;
            $content = $content . $separatorConditions["setNumber2"][1][0] . '  ';
            $content = $content . $separatorConditions["setNumber2"][1][1] . PHP_EOL;
            if ($rows == 3) {
                $content = $content . $separatorConditions["setNumber2"][2][0] . '  ';
                $content = $content . $separatorConditions["setNumber2"][2][1] . PHP_EOL;    
            }    
        }

        if ($separatorConditions["setNumber3"][0][0] != '') 
        {
            $content = $content . $separatorConditions["setNumber3"][0][0] . '  ';
            $content = $content . $separatorConditions["setNumber3"][0][1] . PHP_EOL;
            $content = $content . $separatorConditions["setNumber3"][1][0] . '  ';
            $content = $content . $separatorConditions["setNumber3"][1][1] . PHP_EOL;
            if ($rows == 3) {
                $content = $content . $separatorConditions["setNumber3"][2][0] . '  ';
                $content = $content . $separatorConditions["setNumber3"][2][1] . PHP_EOL;    
            }    
        }

        if ($separatorConditions["setNumber4"][0][0] != '') 
        {
            $content = $content . $separatorConditions["setNumber4"][0][0] . '  ';
            $content = $content . $separatorConditions["setNumber4"][0][1] . PHP_EOL;
            $content = $content . $separatorConditions["setNumber4"][1][0] . '  ';
            $content = $content . $separatorConditions["setNumber4"][1][1] . PHP_EOL;
            if ($rows == 3) {
                $content = $content . $separatorConditions["setNumber4"][2][0] . '  ';
                $content = $content . $separatorConditions["setNumber4"][2][1] . PHP_EOL;    
            }    
        }

        if ($separatorConditions["setNumber5"][0][0] != '') 
        {
            $content = $content . $separatorConditions["setNumber5"][0][0] . '  ';
            $content = $content . $separatorConditions["setNumber5"][0][1] . PHP_EOL;
            $content = $content . $separatorConditions["setNumber5"][1][0] . '  ';
            $content = $content . $separatorConditions["setNumber5"][1][1] . PHP_EOL;
            if ($rows == 3) {
                $content = $content . $separatorConditions["setNumber5"][2][0] . '  ';
                $content = $content . $separatorConditions["setNumber5"][2][1] . PHP_EOL;
            }    
        }

        if ($separatorConditions["setNumber6"][0][0] != '') 
        {
            $content = $content . $separatorConditions["setNumber6"][0][0] . '  ';
            $content = $content . $separatorConditions["setNumber6"][0][1] . PHP_EOL;
            $content = $content . $separatorConditions["setNumber6"][1][0] . '  ';
            $content = $content . $separatorConditions["setNumber6"][1][1] . PHP_EOL;
            if ($rows == 3) {
                $content = $content . $separatorConditions["setNumber6"][2][0] . '  ';
                $content = $content . $separatorConditions["setNumber6"][2][1] . PHP_EOL;    
            }    
        }

        if ($separatorConditions["setNumber7"][0][0] != '') 
        {
            $content = $content . $separatorConditions["setNumber7"][0][0] . '  ';
            $content = $content . $separatorConditions["setNumber7"][0][1] . PHP_EOL;
            $content = $content . $separatorConditions["setNumber7"][1][0] . '  ';
            $content = $content . $separatorConditions["setNumber7"][1][1] . PHP_EOL;
            if ($rows == 3) {
                $content = $content . $separatorConditions["setNumber7"][2][0] . '  ';
                $content = $content . $separatorConditions["setNumber7"][2][1] . PHP_EOL;    
            }    
        }

        if ($separatorConditions["setNumber8"][0][0] != '') 
        {
            $content = $content . $separatorConditions["setNumber8"][0][0] . '  ';
            $content = $content . $separatorConditions["setNumber8"][0][1] . PHP_EOL;
            $content = $content . $separatorConditions["setNumber8"][1][0] . '  ';
            $content = $content . $separatorConditions["setNumber8"][1][1] . PHP_EOL;
            if ($rows == 3) {
                $content = $content . $separatorConditions["setNumber8"][2][0] . '  ';
                $content = $content . $separatorConditions["setNumber8"][2][1] . PHP_EOL;    
            }    
        }

        if ($separatorConditions["setNumber9"][0][0] != '') 
        {
            $content = $content . $separatorConditions["setNumber9"][0][0] . '  ';
            $content = $content . $separatorConditions["setNumber9"][0][1] . PHP_EOL;
            $content = $content . $separatorConditions["setNumber9"][1][0] . '  ';
            $content = $content . $separatorConditions["setNumber9"][1][1] . PHP_EOL;
            if ($rows == 3) {
                $content = $content . $separatorConditions["setNumber9"][2][0] . '  ';
                $content = $content . $separatorConditions["setNumber9"][2][1] . PHP_EOL;    
            }    
        }

        Storage::disk('executables')->put($cmd_sepopt_in_file, $content);
        error_log('Finished to create SEP.in');

        //
        // launch SEPOPT.exe
        //
        $workspace_path = 'executables/' . $workspace_dir;
        $output = Terminal::in(storage_path($workspace_path))->run('SEPOPT.exe');
        if ($output->successful() == false)  {
            error_log('Error happened to launch SEPOPT.exe');
            return response()->json([
                []
            ]);    
        }

        //
        // Get KGKO_COREY.out file
        //
        $res = array();
        $content = fopen(storage_path($workspace_path.'/OPT.OUT'),'r');
        while(!feof($content)){
            try {
                $line = fgets($content);
                if ($line[0] == '#') 
                    continue;   

                $string = preg_replace('/\s+/', ',', $line);
                $pieces = explode(',', $string);
                if (count($pieces) == 6) {
                    error_log('pushed one item');
                    array_push($res, array($pieces[1], $pieces[2], $pieces[3], $pieces[4]));
                }
            } 
            catch (Exception $e) {
                continue;
            }
        }
        fclose($content);

        return response()->json($res);
    }
}
