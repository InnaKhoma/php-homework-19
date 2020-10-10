<?php

namespace App\Http\Controllers;

use App\Filter\ProjectFilter;
use App\Models\Continent;
use App\Models\Label;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin')->only('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $continents = Continent::all()->unique('continent');
        $labels = Label::all()->pluck('name');
        $users = User::all();

        $projects = Project::with(['labels', 'user']);
        $projects = (new ProjectFilter($projects, $request))->apply()->get();

        return response()->view('projects',
            [
                'projects' => $projects,
                'users' => $users,
                'continents' => $continents,
                'labels' => $labels,
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        $users = User::where('name', '<>', $user->name)->get();
        $route = route('projects.store');
        $value = old('name');
        return response()->view('forms.create', [
            'users' => $users,
            'route' => $route,
            'button' => 'Create',
            'heading' => 'Create New Project',
            'input' => 'Project Name',
            'value' => $value,
            'method' => 'POST'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            request()->all(),
            ['name' => 'required|min:5']
        );

        if($validator->fails()){
            return back()
                ->withErrors($validator->errors())
                ->withInput(request()->all());
        } else {
            $project = new Project();
            $project->name = $request->name;
            $user = Auth::user();
            $project->user_id = $user->id;
            $project->save();

            if(!is_null($labels = $request->labels)){
                foreach($labels as $value){
                    $label = Label::find($value);
                    $project->labels()->save($label);
                }
            }

            if(!is_null($users = $request->users)){
                foreach($users as $value){
                    $user = User::find($value);
                    $project->links()->save($user);
                }
            }

            return response()->redirectTo(route('users.show', ['user' => Auth::user()->id]))
                ->with('success', "New project successfully added.");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = Project::findOrFail($id);
        return response()->view('project', ['project' => $project]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $project = Project::findOrFail($id);
        $user = Auth::user();
        $users = User::where('name', '<>', $user->name)->get();
        $route = route('projects.update', ['project' => $project->id]);
        return response()->view('forms.create', [
            'project' => $project->id,
            'users' => $users,
            'route' => $route,
            'button' => 'Update',
            'heading' => 'Edit the Project',
            'input' => 'Project Name',
            'value' => $project->name,
            'method' => 'PUT'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            request()->all(),
            ['name' => 'required|min:5']
        );

        if($validator->fails()){
            return back()
                ->withErrors($validator->errors())
                ->withInput(request()->all());
        } else {
            $project = Project::find($id);
            $project->name = $request->name;
            $project->save();

            return response()->redirectTo(route('users.show', ['user' => Auth::user()->id]))
                ->with('success', $project->name . " successfully updated.");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();
        return response()->redirectTo(route('users.show', ['user' => Auth::user()->id]))
            ->with('success', 'Project successfully deleted.');
    }
}
