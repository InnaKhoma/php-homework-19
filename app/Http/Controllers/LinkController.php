<?php

namespace App\Http\Controllers;

use App\Models\Label;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LinkController extends Controller
{

    public function detach($project, $label)
    {
        $unlink = Project::findOrFail($project);
        $unlink->labels()->detach($label);
        return back()->with('success', 'Label successfully deleted from project.');
    }

    public function detach_user($project, $user)
    {
        $unlink = User::findOrFail($user);
        $unlink->links()->detach($project);
        return back()->with('success',  $unlink->name . ' was successfully deleted from project.');
    }

    public function labels($project)
    {
        $project_labels = Project::findOrFail($project)->labels;
        $labels = auth()->user()->labels->diff($project_labels);
        $route = route('attach.label', ['project' => $project]);
        return view('forms.attach',
            [
                'project' => $project,
                'items' => $labels,
                'route' => $route,
                'heading' => 'Link labels to project'
            ]);
    }

    public function attach_label($project)
    {
        $labels = request()->get('items');

        if (isset($labels)) {
            foreach ($labels as $label) {
                $link = Project::findOrFail($project);
                $link->labels()->attach($label);
            }

            return response()->redirectTo(route('projects.show', ['project' => $project]))
                ->with('success', 'Label(s) successfully added to project.');
        }

        return response()->redirectTo(route('projects.show', ['project' => $project]));
    }

    public function projects($label)
    {
        $label_projects = Label::findOrFail($label)->projects;
        $projects = auth()->user()->projects->diff($label_projects);
        $route = route('attach.project', ['label' => $label]);

        return view('forms.attach',
            [
                'items' => $projects,
                'label' => $label,
                'route' => $route,
                'heading' => 'Link label to projects'
            ]);
    }

    public function attach_project($label)
    {
        $projects = request()->get('items');

        if (isset($projects)) {
            foreach ($projects as $project) {
                $link = Label::findOrFail($label);
                $link->projects()->attach($project);
            }

            return response()->redirectTo(route('labels.show', ['label' => $label]))
                ->with('success', 'Label successfully added to project(s).');
        }

        return response()->redirectTo(route('labels.show', ['label' => $label]));
    }

    public function users($project)
    {
        $project_users = Project::findOrFail($project)->links->push(auth()->user());
        $users = User::all()->diff($project_users);
        $route = route('attach.user', ['project' => $project]);
        return view('forms.attach',
            [
                'project' => $project,
                'items' => $users,
                'route' => $route,
                'heading' => 'Link users to project'
            ]);
    }

    public function attach_user($project)
    {
        $users = request()->get('items');

        if (isset($users)) {
            foreach ($users as $user) {
                $link = Project::findOrFail($project);
                $link->links()->attach($user);
            }

            return response()->redirectTo(route('projects.show', ['project' => $project]))
                ->with('success', 'User(s) successfully added to project.');
        }

        return response()->redirectTo(route('projects.show', ['project' => $project]));
    }
}
