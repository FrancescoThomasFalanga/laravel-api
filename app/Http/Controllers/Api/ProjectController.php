<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Type;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request) {

        $requestData = $request->all();

        $types = Type::all();

        if($request->has('type_id') && $requestData['type_id']) {

            $projects = Project::where('type_id', $requestData['type_id'])
                ->with('type', 'technologies')
                ->paginate(4);
        
            if(count($projects) == 0) {
                return response()->json([
                    'success' => false,
                    'error' => 'No projects found with this type'
                ]);
            }
        } else {

            $projects = Project::with('type', 'technologies')->paginate(4);
            

        }

        return response()->json([
            'success' => true,
            'results' => $projects,
            'allTypes' => $types,
        ]);

    }

    public function show($slug)
    {
         $project = Project::where('slug',$slug)->with('type', 'technologies')->first();

         if($project){
             return response()->json([
             'success'=> true,
             'results'=> $project
         ]);
         }else{
             return response()->json([
             'success'=> false,
             'error'=> 'Project not fund'
        ]);}    
    }
}
