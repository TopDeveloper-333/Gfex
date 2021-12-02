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

class PlotController extends Controller
{
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
