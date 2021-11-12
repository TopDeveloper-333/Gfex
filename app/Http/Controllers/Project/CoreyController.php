<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use TitasGailius\Terminal\Terminal;

class CoreyController extends Controller
{
    private function removeOldFiles($workspace_dir)
    {
        Storage::disk('executables')->delete($workspace_dir . '/ECONOMICS.BAK');
        Storage::disk('executables')->delete($workspace_dir . '/FASTPLAN.BAK');
        Storage::disk('executables')->delete($workspace_dir . '/GAS_PVT.BAK');
        Storage::disk('executables')->delete($workspace_dir . '/KRSG.BAK');
        Storage::disk('executables')->delete($workspace_dir . '/OPERATIONS.BAK');
        Storage::disk('executables')->delete($workspace_dir . '/PCGR.BAK');
        Storage::disk('executables')->delete($workspace_dir . '/PGOR.BAK');
        Storage::disk('executables')->delete($workspace_dir . '/PINE.BAK');
        Storage::disk('executables')->delete($workspace_dir . '/PKRG.BAK');
        Storage::disk('executables')->delete($workspace_dir . '/PKRO.BAK');
        Storage::disk('executables')->delete($workspace_dir . '/PMAT.BAK');
        Storage::disk('executables')->delete($workspace_dir . '/PZ.BAK');
        Storage::disk('executables')->delete($workspace_dir . '/PZED.BAK');
        Storage::disk('executables')->delete($workspace_dir . '/RESERVOIR.BAK');
        Storage::disk('executables')->delete($workspace_dir . '/RESERVOIR_MON.BAK');
        Storage::disk('executables')->delete($workspace_dir . '/SURFACE.BAK');
        Storage::disk('executables')->delete($workspace_dir . '/WELL_HISTORY.BAK');
        Storage::disk('executables')->delete($workspace_dir . '/CVD.DAT');
        Storage::disk('executables')->delete($workspace_dir . '/COREY_DATA.in');
        Storage::disk('executables')->delete($workspace_dir . '/CVD.in');
        Storage::disk('executables')->delete($workspace_dir . '/ECONOMICS.in');
        Storage::disk('executables')->delete($workspace_dir . '/FASTPLAN.in');
        Storage::disk('executables')->delete($workspace_dir . '/GAS_PVT.in');
        Storage::disk('executables')->delete($workspace_dir . '/KRSG.in');
        Storage::disk('executables')->delete($workspace_dir . '/OPERATIONS.in');
        Storage::disk('executables')->delete($workspace_dir . '/PINE.in');
        Storage::disk('executables')->delete($workspace_dir . '/PZED.in');
        Storage::disk('executables')->delete($workspace_dir . '/RESERVOIR.in');
        Storage::disk('executables')->delete($workspace_dir . '/RESERVOIR_MON.in');
        Storage::disk('executables')->delete($workspace_dir . '/SURFACE.in');
        Storage::disk('executables')->delete($workspace_dir . '/WELL_HISTORY.in');
        Storage::disk('executables')->delete($workspace_dir . '/CVD.NEW');
        Storage::disk('executables')->delete($workspace_dir . '/CVD.OUT');
        Storage::disk('executables')->delete($workspace_dir . '/EARNING.OUT');
        Storage::disk('executables')->delete($workspace_dir . '/ECONOMICS.OUT');
        Storage::disk('executables')->delete($workspace_dir . '/KGKO_COREY.OUT');
        Storage::disk('executables')->delete($workspace_dir . '/MATPLOT.OUT');
        Storage::disk('executables')->delete($workspace_dir . '/PINE.OUT');
        Storage::disk('executables')->delete($workspace_dir . '/PLOT_OF.OUT');
        Storage::disk('executables')->delete($workspace_dir . '/PLOT_SI.OUT');
        Storage::disk('executables')->delete($workspace_dir . '/PRESSURE_MATCHING.OUT');
        Storage::disk('executables')->delete($workspace_dir . '/RESULTS_OF.OUT');
        Storage::disk('executables')->delete($workspace_dir . '/RESULTS_SI.OUT');
    }

    /**
     * Get authenticated user.
     */
    public function requestKGKO(Request $request)
    {
        $workspace_dir = $request->user()->id;
        $this->removeOldFiles($workspace_dir);

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
