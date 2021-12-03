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

  public function runMultiPlot(Request $request)
  {
    $user_id = $request->user()->id;

    $selected_plots = $request->get('selectedPlots');
    $axisX = $request->get('axisX');
    $axisY = $request->get('axisY');

    error_log($selected_plots[0][0]);
    error_log(json_encode($axisX));
    error_log(json_encode($axisY));

    try {

      // get Axis X
      $res = array();
      {
        $plot = json_decode(Plot::find($selected_plots[0][0])->plot);

        $x = array();
        $x[0] = $axisX['name'];
        for ($i = 0; $i < count($plot); $i++) {
          $x[$i + 1] = $plot[$i][$axisX['index']];
        }

        array_push($res, $x);
      }

      // get Axis Y with multiple cases
      $resY = array();
      foreach ($selected_plots as $element) {
        $PLOT_DATA = Plot::find($element[0]);
        $plot = json_decode($PLOT_DATA->plot);
        
        $y = array();
        $y[0] = $PLOT_DATA->plot_name;
        for ($i = 0; $i < count($plot); $i++) {
          $y[$i+1] = $plot[$i][$axisY['index']];
        }

        array_push($res, $y);
      }

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
