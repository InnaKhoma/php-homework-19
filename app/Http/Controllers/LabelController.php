<?php

namespace App\Http\Controllers;

use App\Filter\LabelFilter;
use App\Models\Label;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LabelController extends Controller
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
        $labels = Label::with(['projects', 'user']);
        $users = User::all();
        $projects = Project::all();

        $labels = (new LabelFilter($labels, $request))->apply()->get();

        return response()->view('labels',
            [
                'labels' => $labels,
                'users' => $users,
                'projects' => $projects,
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $route = route('labels.store');
        $value = old('name');
        return response()->view('forms.create', [
            'route' => $route,
            'button' => 'Create',
            'heading' => 'Create New Label',
            'input' => 'Label Name',
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
            ['name' => 'required|min:2']
        );

        if($validator->fails()){
            return back()
                ->withErrors($validator->errors())
                ->withInput(request()->all());
        } else {
            $label = new Label();
            $label->name = $request->name;
            $user = Auth::user();
            $label->user_id = $user->id;
            $label->save();

            return response()->redirectTo(route('users.show', ['user' => Auth::user()->id]))
                ->with('success', "New label successfully added.");
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
        $label = Label::findOrFail($id);
        return response()->view('label', ['label' => $label]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $label = Label::findOrFail($id);
        $route = route('labels.update', ['label' => $label->id]);
        return response()->view('forms.create', [
            'label' => $label->id,
            'route' => $route,
            'button' => 'Update',
            'heading' => 'Edit the Label',
            'input' => 'Label Name',
            'value' => $label->name,
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
            ['name' => 'required|min:2']
        );

        if($validator->fails()){
            return back()
                ->withErrors($validator->errors())
                ->withInput(request()->all());
        } else {
            $label = Label::findOrFail($id);
            $label->name = $request->name;
            $label->save();

            return response()->redirectTo(route('labels.show', ['label' => $label]))
                ->with('success', "Label successfully updated.");
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
        $label = Label::findOrFail($id);
        $label->delete();
        return response()->redirectTo(route('users.show', ['user' => Auth::user()->id]))
            ->with('success', 'Label "' . $label->name .'" successfully deleted.');
    }
}
