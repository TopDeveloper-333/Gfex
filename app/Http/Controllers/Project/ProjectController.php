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


        return response()->json($project_content);
    }

    public function listProjects(Request $request)
    {
        $user_id = $request->user()->id;
        $project_list = Project::where('user_id', '=', $user_id)->pluck('project_name')->toArray();

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
        $project_name = $request->get('project');
        error_log('openProject: '.$project_name);

        $project = Project::where('project_name', '=', $project_name)->firstOrFail();
        $project_content = $project->content;
        return response()->json($project_content);
    }

    public function saveProject(Request $request)
    {
        return response()->json([]);
    }

    public function launchProject(Request $request)
    {
        return response()->json([]);
    }
}
