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
        $content->gascondensate = $request->get('gascondensate');

        $project->content = json_encode($content);
        $project->save();

        error_log(json_encode($content));

        return response()->json([]);
    }

    public function launchProject(Request $request)
    {
        return response()->json([]);
    }
}
