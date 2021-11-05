<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use TitasGailius\Terminal\Terminal;

class CoreyController extends Controller
{
    /**
     * Get authenticated user.
     */
    public function requestKGKO(Request $request)
    {
        $workspace_dir = $request->user()->id;

        //
        // create workspace directory with user_id
        //
        $cmd_create_dir = 'mkdir ' . $workspace_dir;
        $output = Terminal::in(storage_path('executables'))->run($cmd_create_dir);

        //
        // copy corey_function.exe into workspace directory
        //
        $cmd_copy_corey = 'copy COREY_FUNCTION.exe ' . $workspace_dir;
        $output = Terminal::in(storage_path('executables'))->run($cmd_copy_corey);
        if ($output->successful() == false)  {
            error_log('Error happened to copy COREY_FUNCTION.exe');
            return response()->json([
                []
            ]);    
        }

        //
        // create COREY_DATA.in file inside workspace 
        //
        $cmd_corey_in_file = $workspace_dir . '/COREY_DATA.in';
        $content = '';
        $content = $content . $request->get('sgi') . '  ';
        $content = $content . $request->get('krgl') . '  ';
        $content = $content . $request->get('kroi') . '  ';
        $content = $content . $request->get('sor') . '  ';
        $content = $content . $request->get('lambda') . PHP_EOL ;

        Storage::disk('executables')->delete($cmd_corey_in_file);
        Storage::disk('executables')->put($cmd_corey_in_file, $content);
        error_log('Finished to call COREY_FUNCTION');

        //
        // launch COREY_FUNCTION.exe
        //
        $workspace_path = 'executables/' . $workspace_dir;
        $output = Terminal::in(storage_path($workspace_path))->run('COREY_FUNCTION.exe');
        if ($output->successful() == false)  {
            error_log('Error happened to launch COREY_FUNCTION.exe');
            return response()->json([
                []
            ]);    
        }

        //
        // Get KGKO_COREY.out file
        //
        $res = array();
        $content = fopen(storage_path($workspace_path.'/KGKO_COREY.OUT'),'r');
        while(!feof($content)){
            try {
                $line = fgets($content);
                $string = preg_replace('/\s+/', ',', $line);
                $pieces = explode(',', $string);
                if (count($pieces) == 5) {
                    if (is_numeric($pieces[1]) && is_numeric($pieces[2]) && is_numeric($pieces[3]) ) {
                        array_push($res, array($pieces[1], $pieces[2], $pieces[3]));
                    }
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
