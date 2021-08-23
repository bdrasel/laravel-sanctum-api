<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    //CREATE PROJECT API
    public function cratepProject(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'duration' => 'required',
        ]);

        $student_id = auth()->user()->id;
        $project = new Project();
        $project->student_id = $student_id;
        $project->name = $request->name;
        $project->description = $request->description;
        $project->duration = $request->duration;
        $project->save();

        return response()->json(['status' => 1, 'message' => "Project Inserted Successfully"]);


    }

    //LIST PROJECT API
    public function listProject()
    {
        $student_id = auth()->user()->id;
        $project = Project::where('student_id',$student_id)->get();
        return response()->json(['status' => 1, 'message' => "All Project Information",'data' => $project]);
    }

    //SINGLE PROJECT API
    public function singleProject($id)
    {
        $student_id = auth()->user()->id;
        if(Project::where(['id' => $id, 'student_id' => $student_id])->exists()){

            $detils = Project::where('id',$id)->where('student_id',$student_id)->first();
            return response()->json(['status' => 1, 'message' => "Project Details",'data' => $detils]);

        }else{

            return response()->json(['status' => 0, 'message' => "Project Not Found"]);
        }

    }

    //DELETE PROJECT API
    public function deleteProject($id)
    {
        $student_id = auth()->user()->id;
        if(Project::where(['id' => $id, 'student_id' => $student_id])->exists()){

            $project = Project::where('id',$id)->where('student_id',$student_id)->first();
            $project->delete();
            return response()->json(['status' => 1, 'message' => "Project has been deleted successfully"]);

        }else{

            return response()->json(['status' => 0, 'message' => "Project Not Found"]);
        }
    }
}
