<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use TitasGailius\Terminal\Terminal;

use App\Models\Project;
use App\Models\License;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Crypt\Common\PublicKey;
use phpseclib3\Crypt\Common\PrivateKey;
use phpseclib3\Exception\NoKeyLoadedException;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    static public function defaultProjectContent()
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
        $user_id = $request->user()->id;
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

    public function deleteProject(Request $request)
    {
        $user_id = $request->user()->id;
        $existProject = Project::find($request->get('id'));
        if ($existProject->user_id == $user_id) {
            $existProject->delete();
        }

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

    private function createReservoirMon($workspace_dir, $reservoir)
    {
        $filePath = $workspace_dir . '/RESERVOIR_MON.in';
        Storage::disk('executables')->delete($filePath);
        $backupPath = $workspace_dir . '/RESERVOIR_MON.bak';
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

        // $content = $content . $reservoir['wellTestData'] . PHP_EOL;

        // if (intval($reservoir['wellTestData']) == 1) {
        //     $content = $content . $reservoir['cnModel']['C'] . '  ';
        //     $content = $content . $reservoir['cnModel']['n'] . PHP_EOL;    
        // }
        // else if (intval($reservoir['wellTestData']) == 2) {
        //     $content = $content . $reservoir['verticalModel']['k'] . '  ';
        //     $content = $content . $reservoir['verticalModel']['Porosity'] . '  ';
        //     $content = $content . $reservoir['verticalModel']['NetPay'] . '  ';
        //     $content = $content . $reservoir['verticalModel']['DrainageArea'] . '  ';
        //     $content = $content . $reservoir['verticalModel']['WellboreID'] . '  ';
        //     $content = $content . $reservoir['verticalModel']['Skin'] . PHP_EOL;    
        // }
        // else if (intval($reservoir['wellTestData']) == 3) {
        //     $content = $content . $reservoir['horizontalModel']['k'] . '  ';
        //     $content = $content . $reservoir['horizontalModel']['Porosity'] . '  ';
        //     $content = $content . $reservoir['horizontalModel']['NetPay'] . '  ';
        //     $content = $content . $reservoir['horizontalModel']['DrainageArea'] . '  ';
        //     $content = $content . $reservoir['horizontalModel']['WellboreID'] . '  ';
        //     $content = $content . $reservoir['horizontalModel']['Skin'] . '  ';
        //     $content = $content . $reservoir['horizontalModel']['WellLength'] . '  ';
        //     $content = $content . $reservoir['horizontalModel']['KvKh'] . PHP_EOL;    
        // }

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

    private function createWellHistory($workspace_dir, $wellhistory)
    {
        $filePath = $workspace_dir . '/WELL_HISTORY.in';
        Storage::disk('executables')->delete($filePath);
        $backupPath = $workspace_dir . '/WELL_HISTORY.bak';
        Storage::disk('executables')->delete($backupPath);

        $content = '';

        $content = $content . $wellhistory['historyForecastRun']['FirstYearOfProduction'] . '  ';
        $content = $content . $wellhistory['historyForecastRun']['LifeOfTheField'] . PHP_EOL;

        $content = $content . $wellhistory['operationsData']['SalesPressure'] . '  ';
        $content = $content . $wellhistory['operationsData']['PressureLimit'] . '  ';
        $content = $content . $wellhistory['operationsData']['EconomicsRate'] . '  ';
        $content = $content . $wellhistory['operationsData']['QgtotInitial'] . PHP_EOL;

        // $content = $content . $wellhistory['reservoirParameters']['GIIP'] . '  ';
        // $content = $content . $wellhistory['reservoirParameters']['Pr'] . PHP_EOL;

        // $content = $content . $wellhistory['hasDualPorosity'] . PHP_EOL;

        // if (intval($wellhistory['hasDualPorosity']) == 1) {
        //     $content = $content . $wellhistory['dualPorosity']['km'] . '  ';
        //     $content = $content . $wellhistory['dualPorosity']['hm'] . '  ';
        //     $content = $content . $wellhistory['dualPorosity']['ShapeFactorSigma'] . '  ';
        //     $content = $content . $wellhistory['dualPorosity']['MatrixGIIP'] . PHP_EOL;    
        // }

        // write matching data
        $content = $content . $wellhistory['matching'] . PHP_EOL;
        if (intval($wellhistory['matching']) == 1) {
            $content = $content . $wellhistory['numberOfRates'] . PHP_EOL;
            foreach ($wellhistory['gasFlowRates'] as $value) {
                $content = $content . $value . PHP_EOL;
            }    
        }

        // write wellsnetwork
        $content = $content . $wellhistory['numberOfWells'] . PHP_EOL;
        foreach ($wellhistory['wellsNetwork'] as $value) {
            $content = $content . $value['wellTestData'] . PHP_EOL;

            if ($value['wellTestData'] == 1) {
                $content = $content . $value['cnModel']['C'] . '  ';
                $content = $content . $value['cnModel']['n'] . '  ';
                $content = $content . $value['cnModel']['WellToTiePoint'] . PHP_EOL;    

                $content = $content . $value['cnModel1']['PressureAtShutIn'] . '  ';
                $content = $content . $value['cnModel1']['PressureAtReOpening'] . PHP_EOL;    
            }
            else if ($value['wellTestData'] == 2) {
                $content = $content . $value['verticalModel']['k'] . '  ';
                $content = $content . $value['verticalModel']['Porosity'] . '  ';
                $content = $content . $value['verticalModel']['NetPay'] . '  ';
                $content = $content . $value['verticalModel']['DrainageArea'] . '  ';
                $content = $content . $value['verticalModel']['WellboreID'] . '  ';
                $content = $content . $value['verticalModel']['Skin'] . '  ';
                $content = $content . $value['verticalModel']['WellToTiePoint'] . PHP_EOL;    

                $content = $content . $value['verticalModel1']['PressureAtShutIn'] . '  ';
                $content = $content . $value['verticalModel1']['PressureAtReOpening'] . PHP_EOL;    
            }
            else if ($value['wellTestData'] == 3) {
                $content = $content . $value['horizontalModel']['k'] . '  ';
                $content = $content . $value['horizontalModel']['Porosity'] . '  ';
                $content = $content . $value['horizontalModel']['NetPay'] . '  ';
                $content = $content . $value['horizontalModel']['DrainageArea'] . '  ';
                $content = $content . $value['horizontalModel']['WellboreID'] . '  ';
                $content = $content . $value['horizontalModel']['Skin'] . '  ';
                $content = $content . $value['horizontalModel']['WellLength'] . '  ';
                $content = $content . $value['horizontalModel']['KvKh'] . '  ';
                $content = $content . $value['horizontalModel']['WellToTiePoint'] . PHP_EOL;    

                $content = $content . $value['horizontalModel1']['PressureAtShutIn'] . '  ';
                $content = $content . $value['horizontalModel1']['PressureAtReOpening'] . PHP_EOL;    
            }
        }

        Storage::disk('executables')->put($filePath, $content);
        error_log('Finished to write WELL_HISTORY.in');
    }

    private function parseWellOut($filePath)
    {
        error_log('parseWellOut: ' . $filePath);

        $res = array();
        $content = fopen(storage_path($filePath),'r');
        $i = 0;

        while(!feof($content)){
            try {
                $line = fgets($content);
                $i++;
                if ($i < 6)
                    continue;
                
                $string = preg_replace('/\s+/', ',', $line);
                $pieces = explode(',', $string);
                if ($i == 6)
                    error_log('WELL.out: ' . count($pieces));

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
                
                $string = preg_replace('/\s+/', ',', $line);
                $pieces = explode(',', $string);

                if ($i == 6)
                    error_log('PlotOf.out: ' . count($pieces));

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

    private function parasePressureMatching($filePath)
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
                    error_log('Pressure_matching.out: ' . count($pieces));

                if (count($pieces) == 4) {
                    //if (is_numeric($pieces[1]) && is_numeric($pieces[2]) && is_numeric($pieces[3]) ) 
                    {
                        array_push($res, 
                            array($pieces[1], $pieces[2])
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

    public function runDryGas(Request $request)
    {

        // determine workspace dir
        $workspace_dir = $request->user()->id;
        $id = $request->get('projectId');

        error_log('runDryGas: id = '. $id. ' Dir = ' . $workspace_dir);
        $this->removeOldFiles($workspace_dir);

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
        $this->createDryGas($workspace_dir, $content->drygas);

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

    public function runMonitoring(Request $request)
    {
        // determine workspace dir
        $workspace_dir = $request->user()->id;
        $id = $request->get('projectId');

        error_log('runDryGas: id = '. $id. ' Dir = ' . $workspace_dir);
        $this->removeOldFiles($workspace_dir);

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
        $content->wellhistory = $request->get('wellhistory');
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
        $this->createDryGas($workspace_dir, $content->drygas);

        //
        // create SURFACE.in file inside workspace 
        //
        $this->createSurface($workspace_dir, $content->surface);

        //
        // create RESERVOIR.in file inside workspace
        //
        $this->createReservoirMon($workspace_dir, $content->reservoir);

        //
        // create ECONOMICS.in file inside workspace
        //
        // $this->createEconomics($workspace_dir, $content->economics);

        //
        // create OPERATIONS.in file inside workspace
        //
        // $this->createOperations($workspace_dir, $content->operations);

        //
        // create WELLHISTORY.in file inside workspace 
        //
        $this->createWellHistory($workspace_dir, $content->wellhistory);

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
        // Read Output Files: WELL1.out, WELL2.out .....
        // 
        $res = [];
        $res['NumberOfWells'] = $content->wellhistory['numberOfWells'];
        error_log('NumberOfWells : ' . $content->wellhistory['numberOfWells']);

        for ($index = 0; $index < $content->wellhistory['numberOfWells']; $index++) {
            if (Storage::disk('executables')->exists($workspace_dir . '/WELL' . ($index + 1) .'.OUT')) {
                $wells_content = Storage::disk('executables')->get($workspace_dir . '/WELL' . ($index + 1) .'.OUT');
                $res['WELL'. ($index+1)] = htmlspecialchars($wells_content);
                $res['RES_WELL'. ($index+1)] = $this->parseWellOut($workspace_path . '/WELL' . ($index + 1) .'.OUT');
            }    
        }

        //
        // Read output file : PRESSURE_MATCHING.OUT
        //
        if (Storage::disk('executables')->exists($workspace_dir . '/PRESSURE_MATCHING.OUT')) {
            $pressure_content = Storage::disk('executables')->get($workspace_dir . '/PRESSURE_MATCHING.OUT');
            $res['PRESSURE_MATCHING'] = htmlspecialchars($pressure_content);
            $res['RES_PRESSURE_MATCHING'] = $this->parasePressureMatching($workspace_path.'/PRESSURE_MATCHING.OUT');
        }

        return response()->json($res);
    }

    public function runGasCondensate(Request $request)
    {
        // determine workspace dir
        $workspace_dir = $request->user()->id;
        $id = $request->get('projectId');

        error_log('runGasCondensate: id = '. $id. ' Dir = ' . $workspace_dir);
        $this->removeOldFiles($workspace_dir);

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

    public function requestCvdOut(Request $request)
    {
        // determine workspace dir
        $workspace_dir = $request->user()->id;

        error_log('requestCvdOut: Dir = ' . $workspace_dir);
        Storage::disk('executables')->delete($workspace_dir . '/CVD.OUT');
        Storage::disk('executables')->delete($workspace_dir . '/CVD.NEW');
        Storage::disk('executables')->delete($workspace_dir . '/CVD.DAT');

        //
        // Get content
        //
        $cvdData1 = $request->get('cvdData1');
        $cvdData2 = $request->get('cvdData2');

        //
        // create workspace directory with user_id
        //
        $cmd_create_dir = 'mkdir ' . $workspace_dir;
        $output = Terminal::in(storage_path('executables'))->run($cmd_create_dir);

        //
        // copy BLACKOIL.exe into workspace directory
        //
        $cmd_copy_app = 'copy BLACKOIL.exe ' . $workspace_dir;
        $output = Terminal::in(storage_path('executables'))->run($cmd_copy_app);
        if ($output->successful() == false)  {
            error_log('Error happened to copy BLACKOIL.exe');
            return response()->json([
                []
            ]);    
        }

        //
        // create CVD.in file
        //
        $cmd_cvd_in_file = $workspace_dir . '/CVD.in';
        $content = '';
        $content = $content . $cvdData1['Psc'] . '  ';
        $content = $content . $cvdData1['Tsc'] . '  ';
        $content = $content . $cvdData1['T'] . '  ';
        $content = $content . $cvdData1['Gi'] . '  ';
        $content = $content . $cvdData1['Rvi'] . '  ';
        $content = $content . $cvdData1['SpecificGravityOfOil'] . '  ';
        $content = $content . $cvdData1['SpecificGravityOfGas'] . '  ';
        $content = $content . $cvdData1['ZFactorOfGas'] . PHP_EOL ;

        foreach ($cvdData2 as $value) {
            $content = $content . $value[0] . '  ';
            $content = $content . $value[1] . '  ';
            $content = $content . $value[2] . '  ';
            $content = $content . $value[3] . '  ';
            $content = $content . $value[4] . '  ';
            $content = $content . $value[5] . PHP_EOL;
        }

        Storage::disk('executables')->delete($cmd_cvd_in_file);
        Storage::disk('executables')->put($cmd_cvd_in_file, $content);
        
        //
        // launch BLACKOIL.exe
        //
        $workspace_path = 'executables/' . $workspace_dir;
        $output = Terminal::in(storage_path($workspace_path))->run('BLACKOIL.exe');
        if ($output->successful() == false)  {
            error_log('Error happened to launch BLACKOIL.exe');
            return response()->json([
                []
            ]);    
        }
        error_log('Finished to call CVD_FUNCTION');

        //
        // Get CVD.out file
        //
        $res = array();
        $content = fopen(storage_path($workspace_path.'/CVD.OUT'),'r');
        $i = 0;

        while(!feof($content)){
            try {
                $line = fgets($content);
                $i++;
                if ($i < 2)
                    continue;

                $string = preg_replace('/\s+/', ',', $line);
                $pieces = explode(',', $string);
                if (count($pieces) == 10) {
                    // if (is_numeric($pieces[1]) && is_numeric($pieces[2]) && is_numeric($pieces[3]) ) 
                    {
                        array_push($res, array($pieces[1], $pieces[2], $pieces[3],
                                               $pieces[4], $pieces[5], $pieces[6], 
                                               $pieces[7], $pieces[8]));
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

    public function generateLicense(Request $request)
    {
        $name = $request->get('name');
        $email = $request->get('email');
        $from = $request->get('from');
        $to = $request->get('to');
        $role = $request->get('role');
        $machineKey = $request->get('machineKey');

        try 
        {
            // ---------------------------
            // load private key & decrypt data
            //
        
            $private_key = PublicKeyLoader::load(file_get_contents(base_path() . '/priv.pem'));
            $encrypted_machine_key = base64_decode($machineKey);
            $raw_data = $private_key->decrypt($encrypted_machine_key);

            // -----------------------------------------
            // split data : machine-id, os:type
            //

            $contents = explode(':', $raw_data);
            $machine_id = $contents[0];
            $os_type = $contents[1];
            
            // -----------------------------------------
            // Generate value
            //            
            $base_data = $machine_id . ':' . $os_type . ':' . $from . ':' . $to;

            // -------------------------------------------
            // encrypt data : {machineID}:{osType}:{startDate}:{endDate}
            //

            $encrypted = '';
            $private_key_content = file_get_contents(base_path() . '/priv.pem');
            openssl_private_encrypt($base_data, $encrypted, $private_key_content);
            $encrypted = base64_encode($encrypted);

            // -------------------------------------------
            // sign data : 
            //

            $signature = '';
            openssl_sign($encrypted, $signature, $private_key_content, 'sha256WithRSAEncryption');
            $signature = base64_encode($signature);

            $license_key = $encrypted . '.' . $signature;
            error_log('License Key : ' . $license_key);

            // ----------------------------------------
            // save license to database
            //
            {
                $project = new License;
                $project->name = $name;
                $project->email = $email;
                $project->from = $from;
                $project->to = $to;
                $project->role = $role;
                $project->machine_key = $machine_id;
                $project->license_key = $license_key;
                $project->ostype = $os_type;
                $project->save();

                return response()->json($project);
            }
        } 
        catch(NoKeyLoadedException $e) {
            error_log('Failed to load private key');
        }


        return response()->json([]);
    }

    public function fetchLicenses(Request $request)
    {
        // $page = $request->get('page');
        $current = $request->user();
        if ($current->is_admin != '1') {
            return response()->json([]);
        }

        $licenses = License::getAllLicenses();
        return response()->json($licenses);
    }

    public function deleteLicense(Request $request)
    {
        $currentUser = $request->user();        
        if ($currentUser->is_admin != 1)
            return response()->json(['message' => 'The user has not administrator priviledge']);

        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => json_encode($validator->messages())]);
        }

        $existLicense = License::find($request->get('id'));
        $existLicense->delete();

        return response()->json([]);
            
    }
}
