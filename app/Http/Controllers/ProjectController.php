<?php

namespace App\Http\Controllers;

use App\Models\Project;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::actif()->paginate(9);
        return view('projects.index', compact('projects'));
    }

    public function show(Project $project)
    {
        abort_if(!$project->actif, 404);
        return view('projects.show', compact('project'));
    }
}
