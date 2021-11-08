<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use TitasGailius\Terminal\Terminal;

use App\Models\Project;


class ProjectController extends Controller
{
    private function defaultProjectContent()
    {
        return [
            'fastplan' => [
                'isFDP' => '1',
                'isCondensate' => '1',
                'isEconomics' => true,
                'isSeparatorOptimizer' => false,
            ],
            'sep' => [

            ],
            'drygas' => [

            ],
            'surface' => [

            ],
            'reservoir' => [

            ],
            'wellhistory' => [

            ],
            'economics' => [

            ],
            'operations' => [

            ],
            'gascondensate' => [

            ],
            'relPerm' => [

            ],
            'resKGKO' => [

            ],
            'resOPT' => [

            ]
        ];
    }

    public function createProject(Request $request)
    {
        $user_id = $request->user()->id;
        $project_name = $request->get('project');
        $project_content = $this->defaultProjectContent();
        $project_result = '{}';

        // try {
        //     $project = Project::where('project_name', '=', $project_name)->firstOrFail();
        //     $project_content = $project->content;
        //     error_log('Found same name : ' . $project_name);
        // }
        // catch (Exception $e)
        {
            $project = new Project;
            $project->user_id = $user_id;
            $project->project_name = $project_name;
            $project->content = json_encode($project_content);
            $project->result = $project_result;
            $project->save();
        }


        return response()->json($project);
    }

    public function listProjects(Request $request)
    {
        $user_id = $request->user()->id;
        $project_list = Project::where('user_id', '=', $user_id)->pluck('project_name','id')->toArray();

        error_log('listProjects: user_id = '. $user_id);

        $res = array();
        foreach ($project_list as $key => $value) {
            $item = ['id' => $key, 'name' => $value];
            $res[] = $item;
        }

        error_log(json_encode($res));
        return response()->json($res);
    }

    public function openProject(Request $request)
    {
        $id = $request->get('id');
        $project_name = $request->get('project');
        error_log('openProject: id = '. $id . " name: ".$project_name);

        $project = Project::where('id', '=', $id)->firstOrFail();
        return response()->json($project);
    }

    public function saveProject(Request $request)
    {
        $id = $request->get('projectId');
        $isSaveAs = $request->get('isSaveAs');
        $user_id = $request->user()->id;

        error_log('saveProject: id = '. $id. ' saveAs = ' . $isSaveAs);        

        if ($isSaveAs == true) {
            $project = new Project();
            $project->user_id = $user_id;
            $project->project_name = $request->get('projectName');
            $project->result = '{}';

            $content = json_decode(json_encode($this->defaultProjectContent()));
        }
        else {
            $project = Project::where('id', '=', $id)->firstOrFail();
            $content = json_decode($project->content);
        }

        // update content 
        $content->fastplan->isFDP = $request->get('isFDP');
        $content->fastplan->isCondensate = $request->get('isCondensate');
        $content->fastplan->isEconomics = $request->get('isEconomics');
        $content->fastplan->isSeparatorOptimizer = $request->get('isSeparatorOptimizer');
        $content->sep = $request->get('sep');
        $content->drygas = $request->get('drygas');
        $content->surface = $request->get('surface');
        $content->reservoir = $request->get('reservoir');
        $content->wellhistory = $request->get('wellhistory');
        $content->economics = $request->get('economics');
        $content->operations = $request->get('operations');
        $content->relPerm = $request->get('relPerm');
        $content->resKGKO = $request->get('resKGKO');
        $content->gascondensate = $request->get('gascondensate');

        $project->content = json_encode($content);
        $project->save();

        // error_log(json_encode($content));

        return response()->json([]);
    }

    private function createFastPlan($workspace_dir, $fastplan)
    {
        $filePath = $workspace_dir . '/FASTPLAN.in';
        Storage::disk('executables')->delete($filePath);

        $backupPath = $workspace_dir . '/FASTPLAN.bak';
        Storage::disk('executables')->delete($backupPath);

        $content = '';

        if (intval($fastplan->isFDP) == 1)
            $content = $content . '0' . PHP_EOL;
        else 
            $content = $content . '1' . PHP_EOL;
            
        $content = $content . $fastplan->isCondensate . PHP_EOL;

        if ($fastplan->isEconomics == true)
            $content = $content . '1' . PHP_EOL;
        else 
            $content = $content . '0' . PHP_EOL;

        if ($fastplan->isSeparatorOptimizer == true)
            $content = $content . '1' . PHP_EOL;
        else 
            $content = $content . '0' . PHP_EOL;

        $content = $content . '1' . PHP_EOL;

        Storage::disk('executables')->put($filePath, $content);
        error_log('Finished to write FASTPLAN.in');
    }

    private function createDryGas($workspace_dir, $drygas) 
    {
        $filePath = $workspace_dir . '/GAS_PVT.in';
        Storage::disk('executables')->delete($filePath);
        $backupPath = $workspace_dir . '/GAS_PVT.bak';
        Storage::disk('executables')->delete($backupPath);

        $content = '';
        $content = $content . $drygas['standardConditions']['Psc'] . '  ';
        $content = $content . $drygas['standardConditions']['Tsc'] . PHP_EOL;

        $content = $content . $drygas['gasProperties']['gasCompressibility'] . '  ';
        $content = $content . $drygas['gasProperties']['gasViscosity'] . '  ';
        $content = $content . $drygas['gasProperties']['specificGravity'] . '  ';
        $content = $content . $drygas['gasProperties']['resTemp'] . '  ';
        $content = $content . $drygas['gasProperties']['N2'] . '  ';
        $content = $content . $drygas['gasProperties']['CO2'] . '  ';
        $content = $content . $drygas['gasProperties']['H2S'] . PHP_EOL;
        // $content = $content . '0' . PHP_EOL;

        $content = $content . $drygas['rockProperties']['conateWaterSaturation'] . '  ';
        $content = $content . $drygas['rockProperties']['waterCompressibility'] . '  ';
        $content = $content . $drygas['rockProperties']['rockCompressibility'] . PHP_EOL;

        Storage::disk('executables')->put($filePath, $content);
        error_log('Finished to write GAS_PVT.in');
    }

    private function createGasCondensate($workspace_dir, $gascondensate) 
    {
        $filePath = $workspace_dir . '/PINE.in';
        Storage::disk('executables')->delete($filePath);
        $backupPath = $workspace_dir . '/PINE.bak';
        Storage::disk('executables')->delete($backupPath);

        $content = '';
        $content = $content . $gascondensate['gasCondensate1']['Psat'] . '  ';
        $content = $content . $gascondensate['gasCondensate1']['Swi'] . PHP_EOL;

        foreach ($gascondensate['gasCondensate2'] as $value) {
            $content = $content . $value[0] . '  ';
            $content = $content . $value[1] . '  ';
            $content = $content . $value[2] . '  ';
            $content = $content . $value[3] . '  ';
            $content = $content . $value[4] . '  ';
            $content = $content . $value[5] . '  ';
            $content = $content . $value[6] . '  ';
            $content = $content . $value[7] . PHP_EOL;
        }

        Storage::disk('executables')->put($filePath, $content);
        error_log('Finished to write PINE.in');
    }

    private function createKRSG($workspace_dir, $krsg) 
    {
        $filePath = $workspace_dir . '/KRSG.in';
        Storage::disk('executables')->delete($filePath);
        $backupPath = $workspace_dir . '/KRSG.bak';
        Storage::disk('executables')->delete($backupPath);

        $content = '';

        foreach ($krsg as $value) {
            $content = $content . $value[0] . '  ';
            $content = $content . $value[1] . '  ';
            $content = $content . $value[2] . PHP_EOL;
        }

        Storage::disk('executables')->put($filePath, $content);
        error_log('Finished to write KRSG.in');
    }

    private function createSurface($workspace_dir, $surface)
    {
        $filePath = $workspace_dir . '/SURFACE.in';
        Storage::disk('executables')->delete($filePath);
        $backupPath = $workspace_dir . '/SURFACE.bak';
        Storage::disk('executables')->delete($backupPath);

        $content = '';

        $content = $content . $surface['tubingProperties']['Length'] . '  ';
        $content = $content . $surface['tubingProperties']['Size'] . '  ';
        $content = $content . $surface['tubingProperties']['Perfs'] . '  ';
        $content = $content . $surface['tubingProperties']['SSSV_ID'] . '  ';
        $content = $content . $surface['tubingProperties']['SSSV_Depth'] . '  ';
        $content = $content . $surface['tubingProperties']['Temperature'] . '  ';
        $content = $content . $surface['tubingProperties']['GasZFactor'] . PHP_EOL;

        $content = $content . $surface['wellaheadToManifold']['Length'] . '  ';
        $content = $content . $surface['wellaheadToManifold']['Size'] . '  ';
        $content = $content . $surface['wellaheadToManifold']['Temperature'] . '  ';
        $content = $content . $surface['wellaheadToManifold']['GasZFactor'] . PHP_EOL;

        $content = $content . $surface['manifoldToCompression']['Length'] . '  ';
        $content = $content . $surface['manifoldToCompression']['Size'] . '  ';
        $content = $content . $surface['manifoldToCompression']['Temperature'] . '  ';
        $content = $content . $surface['manifoldToCompression']['GasZFactor'] . PHP_EOL;

        $content = $content . $surface['compressionToSales']['Length'] . '  ';
        $content = $content . $surface['compressionToSales']['Size'] . '  ';
        $content = $content . $surface['compressionToSales']['Temperature'] . '  ';
        $content = $content . $surface['compressionToSales']['GasZFactor'] . PHP_EOL;

        $content = $content . $surface['compressionToStart']['CompressionDischargeRatio'] . '  ';
        $content = $content . $surface['compressionToStart']['DELTA_P_MIN'] . PHP_EOL;

        Storage::disk('executables')->put($filePath, $content);
        error_log('Finished to write SURFACE.in');
    }

    private function createReservoir($workspace_dir, $reservoir)
    {
        $filePath = $workspace_dir . '/RESERVOIR.in';
        Storage::disk('executables')->delete($filePath);
        $backupPath = $workspace_dir . '/RESERVOIR.bak';
        Storage::disk('executables')->delete($backupPath);

        $content = '';

        $content = $content . $reservoir['reservoirPVT']['Viscosity'] . '  ';
        $content = $content . $reservoir['reservoirPVT']['GasZFactor'] . '  ';
        $content = $content . $reservoir['reservoirPVT']['SpecificGravity'] . '  ';
        $content = $content . $reservoir['reservoirPVT']['ReservoirTemperature'] . PHP_EOL;

        $content = $content . $reservoir['reservoirParameters']['GIIP'] . '  ';
        $content = $content . $reservoir['reservoirParameters']['ReservoirPressure'] . PHP_EOL;

        $content = $content . $reservoir['hasDualPorosity'] . PHP_EOL;

        if (intval($reservoir['hasDualPorosity']) == 1) {
            $content = $content . $reservoir['dualPorosity']['km'] . '  ';
            $content = $content . $reservoir['dualPorosity']['hm'] . '  ';
            $content = $content . $reservoir['dualPorosity']['ShapeFactorSigma'] . '  ';
            $content = $content . $reservoir['dualPorosity']['MatrixGIIP'] . PHP_EOL;    
        }

        $content = $content . $reservoir['wellTestData'] . PHP_EOL;

        if (intval($reservoir['wellTestData']) == 1) {
            $content = $content . $reservoir['cnModel']['C'] . '  ';
            $content = $content . $reservoir['cnModel']['n'] . PHP_EOL;    
        }
        else if (intval($reservoir['wellTestData']) == 2) {
            $content = $content . $reservoir['verticalModel']['k'] . '  ';
            $content = $content . $reservoir['verticalModel']['Porosity'] . '  ';
            $content = $content . $reservoir['verticalModel']['NetPay'] . '  ';
            $content = $content . $reservoir['verticalModel']['DrainageArea'] . '  ';
            $content = $content . $reservoir['verticalModel']['WellboreID'] . '  ';
            $content = $content . $reservoir['verticalModel']['Skin'] . PHP_EOL;    
        }
        else if (intval($reservoir['wellTestData']) == 3) {
            $content = $content . $reservoir['horizontalModel']['k'] . '  ';
            $content = $content . $reservoir['horizontalModel']['Porosity'] . '  ';
            $content = $content . $reservoir['horizontalModel']['NetPay'] . '  ';
            $content = $content . $reservoir['horizontalModel']['DrainageArea'] . '  ';
            $content = $content . $reservoir['horizontalModel']['WellboreID'] . '  ';
            $content = $content . $reservoir['horizontalModel']['Skin'] . '  ';
            $content = $content . $reservoir['horizontalModel']['WellLength'] . '  ';
            $content = $content . $reservoir['horizontalModel']['KvKh'] . PHP_EOL;    
        }

        Storage::disk('executables')->put($filePath, $content);
        error_log('Finished to write RESERVOIR.in');
    }

    private function createEconomics($workspace_dir, $economics)
    {
        $filePath = $workspace_dir . '/ECONOMICS.in';
        Storage::disk('executables')->delete($filePath);
        $backupPath = $workspace_dir . '/ECONOMICS.bak';
        Storage::disk('executables')->delete($backupPath);

        $content = '';

        $content = $content . $economics['economics1']['PriceOfGas'] . '  ';
        $content = $content . $economics['economics1']['PriceOfCondensate'] . '  ';
        $content = $content . $economics['economics1']['PriceOfCompression'] . '  ';
        $content = $content . $economics['economics1']['Life'] . '  ';
        $content = $content . $economics['economics1']['SalvageRateOfCAPEX'] . PHP_EOL;

        $content = $content . $economics['economics2']['Investment'] . '  ';
        $content = $content . $economics['economics2']['ROR'] . '  ';
        $content = $content . $economics['economics2']['Royalty'] . '  ';
        $content = $content . $economics['economics2']['Tax'] . PHP_EOL;

        $content = $content . $economics['economics3']['FirstYearOfProject'] . '  ';
        $content = $content . $economics['economics3']['FirstYearOfProduction'] . PHP_EOL;

        foreach ($economics['economics'] as $value) {
            $content = $content . $value[0] . '  ';
            $content = $content . $value[1] . '  ';
            $content = $content . $value[2] . PHP_EOL;
        }

        Storage::disk('executables')->put($filePath, $content);
        error_log('Finished to write ECONOMICS.in');
    }

    private function createOperations($workspace_dir, $operations)
    {
        $filePath = $workspace_dir . '/OPERATIONS.in';
        Storage::disk('executables')->delete($filePath);
        $backupPath = $workspace_dir . '/OPERATIONS.bak';
        Storage::disk('executables')->delete($backupPath);

        $content = '';

        $content = $content . $operations['operationalConstraints']['StartOfOperations'] . '  ';
        $content = $content . $operations['operationalConstraints']['EndOfContract'] . '  ';
        $content = $content . $operations['operationalConstraints']['MaximumNumberOfWells'] . '  ';
        $content = $content . $operations['operationalConstraints']['RigSchedule'] . PHP_EOL;

        $content = $content . $operations['gasDeliveryRequirements']['SalesPressuire'] . '  ';
        $content = $content . $operations['gasDeliveryRequirements']['TargetRate'] . '  ';
        $content = $content . $operations['gasDeliveryRequirements']['PressureLimit'] . '  ';
        $content = $content . $operations['gasDeliveryRequirements']['EconomicsRate'] . '  ';
        $content = $content . $operations['gasDeliveryRequirements']['MaxFieldRecovery'] . PHP_EOL;

        Storage::disk('executables')->put($filePath, $content);
        error_log('Finished to write OPERATIONS.in');
    }

    private function parsePlotOf($filePath)
    {
        $res = array();
        $content = fopen(storage_path($filePath),'r');
        $i = 0;

        while(!feof($content)){
            try {
                $line = fgets($content);
                $i++;
                if ($i < 4)
                    continue;
                
                if ($i == 6)
                    error_log('PlotOf.out: ' . count($pieces));

                $string = preg_replace('/\s+/', ',', $line);
                $pieces = explode(',', $string);
                if (count($pieces) == 32) {
                    //if (is_numeric($pieces[1]) && is_numeric($pieces[2]) && is_numeric($pieces[3]) ) 
                    {
                        array_push($res, 
                            array($pieces[1], $pieces[2], $pieces[3], $pieces[4],
                                  $pieces[5], $pieces[6], $pieces[7], $pieces[8],
                                  $pieces[9], $pieces[10], $pieces[11], $pieces[12],
                                  $pieces[13], $pieces[14], $pieces[15], $pieces[16],
                                  $pieces[17], $pieces[18], $pieces[19], $pieces[20],
                                  $pieces[21], $pieces[22], $pieces[23], $pieces[24],
                                  $pieces[25], $pieces[26], $pieces[27], $pieces[28],
                                  $pieces[29], $pieces[30], 
                            )
                        );
                    }
                }
            } 
            catch (Exception $e) {
                continue;
            }
        }
        fclose($content);
        return $res;
    }

    private function parseEconomics($filePath)
    {
        $res = array();
        $content = fopen(storage_path($filePath),'r');
        $i = 0;

        while(!feof($content)){
            try {
                $line = fgets($content);
                $i++;
                if ($i < 3)
                    continue;

                $string = preg_replace('/\s+/', ',', $line);
                $pieces = explode(',', $string);
                
                if ($i == 4)
                    error_log('PlotOf.out: ' . count($pieces));

                if (count($pieces) == 17) {
                    //if (is_numeric($pieces[1]) && is_numeric($pieces[2]) && is_numeric($pieces[3]) ) 
                    {
                        array_push($res, 
                            array($pieces[1], $pieces[2], $pieces[3], $pieces[4],
                                  $pieces[5], $pieces[6], $pieces[7], $pieces[8],
                                  $pieces[9], $pieces[10], $pieces[11], $pieces[12],
                                  $pieces[13], $pieces[14], $pieces[15]
                            )
                        );
                    }
                }
            } 
            catch (Exception $e) {
                continue;
            }
        }
        fclose($content);
        return $res;
    }

    public function runDryGas(Request $request)
    {

        // determine workspace dir
        $workspace_dir = $request->user()->id;
        $id = $request->get('projectId');

        error_log('runDryGas: id = '. $id. ' Dir = ' . $workspace_dir);
        Storage::disk('executables')->delete($workspace_dir . '/PLOT_OF.OUT');
        Storage::disk('executables')->delete($workspace_dir . '/ECONOMICS.OUT');
        Storage::disk('executables')->delete($workspace_dir . '/RESULTS_OF.OUT');

        //
        // Get content
        //
        $content = json_decode(json_encode($this->defaultProjectContent()));
        $content->fastplan->isFDP = $request->get('isFDP');
        $content->fastplan->isCondensate = $request->get('isCondensate');
        $content->fastplan->isEconomics = $request->get('isEconomics');
        $content->fastplan->isSeparatorOptimizer = $request->get('isSeparatorOptimizer');
        // $content->sep = $request->get('sep');
        $content->drygas = $request->get('drygas');
        $content->surface = $request->get('surface');
        $content->reservoir = $request->get('reservoir');
        // $content->wellhistory = $request->get('wellhistory');
        $content->economics = $request->get('economics');
        $content->operations = $request->get('operations');
        // $content->relPerm = $request->get('relPerm');
        // $content->gascondensate = $request->get('gascondensate');
        // $content->resKGKO = $request->get('resKGKO');

        //
        // create workspace directory with user_id
        //
        $cmd_create_dir = 'mkdir ' . $workspace_dir;
        $output = Terminal::in(storage_path('executables'))->run($cmd_create_dir);

        //
        // copy ConsoleApplicationFDPHIST.exe into workspace directory
        //
        $cmd_copy_app = 'copy ConsoleApplicationFDPHIST.exe ' . $workspace_dir;
        $output = Terminal::in(storage_path('executables'))->run($cmd_copy_app);
        if ($output->successful() == false)  {
            error_log('Error happened to copy ConsoleApplicationFDPHIST.exe');
            return response()->json([
                []
            ]);    
        }
        
        //
        // create FASTPLAN.in file inside workspace 
        //        
        $this->createFastPlan($workspace_dir, $content->fastplan);

        //
        // create GAS_PVT.in file inside workspace 
        //
        $this->createDryGas($gaspvt_file_path, $content->drygas);

        //
        // create SURFACE.in file inside workspace 
        //
        $this->createSurface($workspace_dir, $content->surface);

        //
        // create RESERVOIR.in file inside workspace 
        //
        $this->createReservoir($workspace_dir, $content->reservoir);

        //
        // create ECONOMICS.in file inside workspace
        //
        if ($content->fastplan->isEconomics == true) {
            $this->createEconomics($workspace_dir, $content->economics);
        }

        //
        // create OPERATIONS.in file inside workspace 
        //
        $this->createOperations($workspace_dir, $content->operations);

        //
        // launch ConsoleApplicationFDPHIST.exe
        //
        $workspace_path = 'executables/' . $workspace_dir;
        $output = Terminal::in(storage_path($workspace_path))->run('ConsoleApplicationFDPHIST.exe');
        if ($output->successful() == false)  {
            error_log('Error happened to launch ConsoleApplicationFDPHIST.exe');
            return response()->json([
                []
            ]);    
        }

        //
        // Read Output Files: PLOT_OF.OUT, RESULTS_OF.OUT, ECONOMICS.OUT 
        // 
        $res = [];

        if (Storage::disk('executables')->exists($workspace_dir . '/PLOT_OF.OUT')) {
            $plotof_content = Storage::disk('executables')->get($workspace_dir . '/PLOT_OF.OUT');
            $res['PLOT_OF'] = htmlspecialchars($plotof_content);
            $res['RES_PLOT_OF'] = $this->parsePlotOf($workspace_path.'/PLOT_OF.OUT');
        }

        if (Storage::disk('executables')->exists($workspace_dir . '/ECONOMICS.OUT')) {
            $economics_content = Storage::disk('executables')->get($workspace_dir . '/ECONOMICS.OUT');            
            $res['ECONOMICS'] = htmlspecialchars($economics_content);
            $res['RES_ECONOMICS'] = $this->parseEconomics($workspace_path.'/ECONOMICS.OUT');
        }

        if (Storage::disk('executables')->exists($workspace_dir . '/RESULTS_OF.OUT')) {
            $resultof_content = Storage::disk('executables')->get($workspace_dir . '/RESULTS_OF.OUT');            
            $res['RESULT_OF'] = htmlspecialchars($resultof_content);
        }

        return response()->json($res);
    }

    public function runGasCondensate(Request $request)
    {
        // determine workspace dir
        $workspace_dir = $request->user()->id;
        $id = $request->get('projectId');

        error_log('runGasCondensate: id = '. $id. ' Dir = ' . $workspace_dir);
        Storage::disk('executables')->delete($workspace_dir . '/PLOT_OF.OUT');
        Storage::disk('executables')->delete($workspace_dir . '/ECONOMICS.OUT');
        Storage::disk('executables')->delete($workspace_dir . '/RESULTS_OF.OUT');

        //
        // Get content
        //
        $content = json_decode(json_encode($this->defaultProjectContent()));
        $content->fastplan->isFDP = $request->get('isFDP');
        $content->fastplan->isCondensate = $request->get('isCondensate');
        $content->fastplan->isEconomics = $request->get('isEconomics');
        $content->fastplan->isSeparatorOptimizer = $request->get('isSeparatorOptimizer');
        // $content->sep = $request->get('sep');
        $content->drygas = $request->get('drygas');
        $content->surface = $request->get('surface');
        $content->reservoir = $request->get('reservoir');
        // $content->wellhistory = $request->get('wellhistory');
        $content->economics = $request->get('economics');
        $content->operations = $request->get('operations');
        // $content->relPerm = $request->get('relPerm');
        $content->gascondensate = $request->get('gascondensate');
        $content->resKGKO = $request->get('resKGKO');

        //
        // create workspace directory with user_id
        //
        $cmd_create_dir = 'mkdir ' . $workspace_dir;
        $output = Terminal::in(storage_path('executables'))->run($cmd_create_dir);

        //
        // copy ConsoleApplicationFDPHIST.exe into workspace directory
        //
        $cmd_copy_app = 'copy ConsoleApplicationFDPHIST.exe ' . $workspace_dir;
        $output = Terminal::in(storage_path('executables'))->run($cmd_copy_app);
        if ($output->successful() == false)  {
            error_log('Error happened to copy ConsoleApplicationFDPHIST.exe');
            return response()->json([
                []
            ]);    
        }
        
        //
        // create FASTPLAN.in file inside workspace 
        //
        $this->createFastPlan($workspace_dir, $content->fastplan);

        //
        // create DRY_GAS.in file inside workspace
        //
        $this->createDryGas($workspace_dir, $content->drygas);

        //
        // create PINE.in file inside workspace 
        //
        $this->createGasCondensate($workspace_dir, $content->gascondensate);

        //
        // create KRSG.in file inside workspace 
        //
        $this->createKRSG($workspace_dir, $content->resKGKO);

        //
        // create SURFACE.in file inside workspace 
        //
        $this->createSurface($workspace_dir, $content->surface);

        //
        // create RESERVOIR.in file inside workspace 
        //
        $this->createReservoir($workspace_dir, $content->reservoir);

        //
        // create ECONOMICS.in file inside workspace
        //
        if ($content->fastplan->isEconomics == true) {
            $this->createEconomics($workspace_dir, $content->economics);
        }

        //
        // create OPERATIONS.in file inside workspace 
        //        
        $this->createOperations($workspace_dir, $content->operations);

        //
        // launch ConsoleApplicationFDPHIST.exe
        //
        $workspace_path = 'executables/' . $workspace_dir;
        $output = Terminal::in(storage_path($workspace_path))->run('ConsoleApplicationFDPHIST.exe');
        if ($output->successful() == false)  {
            error_log('Error happened to launch ConsoleApplicationFDPHIST.exe');
            return response()->json([
                []
            ]);    
        }

        //
        // Read Output Files: PLOT_OF.OUT, RESULTS_OF.OUT, ECONOMICS.OUT 
        // 
        $res = [];

        if (Storage::disk('executables')->exists($workspace_dir . '/PLOT_OF.OUT')) {
            $plotof_content = Storage::disk('executables')->get($workspace_dir . '/PLOT_OF.OUT');
            $res['PLOT_OF'] = htmlspecialchars($plotof_content);
            $res['RES_PLOT_OF'] = $this->parsePlotOf($workspace_path.'/PLOT_OF.OUT');
        }

        if (Storage::disk('executables')->exists($workspace_dir . '/ECONOMICS.OUT')) {
            $economics_content = Storage::disk('executables')->get($workspace_dir . '/ECONOMICS.OUT');            
            $res['ECONOMICS'] = htmlspecialchars($economics_content);
            $res['RES_ECONOMICS'] = $this->parseEconomics($workspace_path.'/ECONOMICS.OUT');
        }

        if (Storage::disk('executables')->exists($workspace_dir . '/RESULTS_OF.OUT')) {
            $resultof_content = Storage::disk('executables')->get($workspace_dir . '/RESULTS_OF.OUT');            
            $res['RESULT_OF'] = htmlspecialchars($resultof_content);
        }

        return response()->json($res);
    }


}
