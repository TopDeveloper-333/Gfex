<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use TitasGailius\Terminal\Terminal;

use App\Models\Plot;
use App\Models\License;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Project\ProjectController;

use DB;

class PlotController extends Controller
{

  public function listPlots(Request $request)
  {
    $user_id = $request->user()->id;
    $project_list = DB::table('plots')
                    ->select('id','created as group', 'plot_name as name')
                    ->where('user_id', '=', $user_id)
                    ->get();

    error_log('listPlots: user_id = '. $user_id);
    error_log(json_encode($project_list));
    return response()->json($project_list);

  }

  public function deletePlot(Request $request)
  {
      $user_id = $request->user()->id;
      $existProject = Plot::find($request->get('id'));
      if ($existProject->user_id == $user_id) {
          $existProject->delete();
      }

      return response()->json([]);
  }

  public function runMultiPlot(Request $request)
  {
    $user_id = $request->user()->id;

    $selected_plots = $request->get('selectedPlots');
    $axisX = $request->get('axisX');
    $axisY = $request->get('axisY');
    $axisY2 = $request->get('axisY2');

    error_log($selected_plots[0][0]);
    error_log(json_encode($axisX));
    error_log(json_encode($axisY));
    error_log(json_encode($axisY2));

    try {

      $res = array();

      // get Axis X
      {
        $x = array();

        foreach ($selected_plots as $element) {
          $PLOT_DATA = Plot::find($element[0]);
          $plot = json_decode($PLOT_DATA->plot);
  
          for ($i = 0; $i < count($plot); $i++) {
            // $x[$i + 1] = $plot[$i][$axisX['index']];
            $founded = false;
            for ($j = 0; $j < count($x); $j++) {
              if ($x[$j] == $plot[$i][$axisX['index']])
                $founded = true; break;
            }

            if ($founded == false)
              $x[count($x)] = (int)$plot[$i][$axisX['index']];
          }
        }

        sort($x);
        array_unshift($x, $axisX['name']);
        array_push($res, $x);
      }

      // get Axis Y with multiple cases
      {       
        foreach ($selected_plots as $element) {
          $y = array();
          $y2 = array();

          $PLOT_DATA = Plot::find($element[0]);
          $plot = json_decode($PLOT_DATA->plot);

          // initialize y, y2 axis
          for ($i = 0; $i < count($x); $i++) {
            $y[$i] = null; $y2[$i] = null;
          }

          $y[0] = $PLOT_DATA->plot_name . ':' . $axisY['name'];
          $y2[0] = $PLOT_DATA->plot_name . ':' . $axisY2['name'];

          // update y, y2 axis
          for ($i = 0; $i < count($plot); $i++) {
            $index = -1;

            $year = $plot[$i][$axisX['index']];
            
            for ($j = 0; $j < count($x); $j++) {
              if ($x[$j] == $year) {
                $index = $j; break;
              }
            }

            $y[$index] = $plot[$i][$axisY['index']];
            $y2[$index] = $plot[$i][$axisY2['index']];
          }
          
          array_push($res, $y);
          array_push($res, $y2);
        }
      }

      error_log('RES:' . json_encode($res));
      return response()->json($res);

    }
    catch (Exception $e) {
      error_log('Error occurred on run Multiple plots');
    }

    return response()->json([]);
  }

  public function runSavePlot(Request $request)
  {
    $user_id = $request->user()->id;
    $plot_name = $request->get('newPlotName');
    
    try {
      $plot = new Plot();
      $plot->user_id = $user_id;
      $plot->plot_name = $plot_name;
      
      // update project    
      $content = json_decode(json_encode(ProjectController::defaultProjectContent()));
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

      $plot->project = json_encode($content);
      
      // update plot
      $plot->plot = json_encode($request->get('plot'));
      $plot->created = date('Y-m-d h:i:s');
      $plot->save();

      return response()->json(['id' => $plot->id]);
    }
    catch(Exception $e) {
      error_log('Error happened to save plot');
    }
    return response()->json([]);
  }

}
