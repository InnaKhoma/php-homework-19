@extends('layouts.app')
@section('title', 'Profile')
@section('content')
    <div class="container">
        @auth
            <div class="row justify-content-center">
                <div class="col" >
                    @if(session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('danger'))
                        <div class="alert alert-danger">
                            {{ session('danger') }}
                        </div>
                    @endif

                    <div class="card">
                        <div class="card-header d-flex justify-content-between">Profile
                            <div class="d-flex justify-content-between align-items-center">
                                <a class="btn btn-warning"
                                   href="{{ route('users.edit', ['user' => $user->id]) }}"
                                   role="button">Edit</a>
                                <form action="{{ route('users.destroy', ['user' => $user->id]) }}"
                                      method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" class="btn btn-danger" style="margin-left: 10px">Delete
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><b>Name:</b> {{ $user->name }}</li>
                                <li class="list-group-item"><b>Email:</b> {{ $user->email }}</li>
                                <li class="list-group-item"><b>Country:</b> {{ $user->country->country }}</li>
                                <li class="list-group-item"><b>Registered:</b> {{ $user->email_verified_at }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col" style="margin-top: 20px;">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                My Projects
                                @can('create', \App\Models\Project::class)
                                    <a class="btn btn-success" href="{{ route('projects.create') }}" role="button">
                                        New Project</a>
                                @endcan
                            </div>
                        </div>

                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                @forelse(Auth::user()->projects as $project)
                                    <li class="list-group-item">
                                        <a href="{{ route('projects.show', [$project->id]) }}">{{ $project->name }}</a>
                                        @foreach($project->labels as $label)
                                            <a href="{{ route('labels.show', [$label->id]) }}"
                                               class="badge badge-pill badge-primary">{{ $label->name }}</a>
                                        @endforeach
                                    </li>
                                @empty
                                    <p>You have no projects.</p>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col" style="margin-top: 20px;">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                My Labels
                                @can('create', \App\Models\Label::class)
                                    <a class="btn btn-success" href="{{ route('labels.create') }}" role="button">New
                                        Label</a>
                                @endcan
                            </div>
                        </div>
                        <div class="card-body">
                            @forelse(Auth::user()->labels as $label)
                                <a href="{{ route('labels.show', [$label->id]) }}" style="margin-right: 10px;">
                                    #{{ $label->name }}</a>
                            @empty
                                You have no labels yet.
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col" style="margin-top: 50px">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                        <tr>
                            <th scope="col">I'm linked to Projects</th>
                            <th scope="col">Owner</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse(Auth::user()->links as $project)
                            @if($project->user->name !== Auth::user()->name)
                                <tr>
                                    <td>
                                        <p>
                                            <a href="{{ route('projects.show', [$project->id]) }}">{{ $project->name }}</a>
                                            @foreach($project->labels as $label)
                                                <a href="{{ route('labels.show', [$label->id]) }}"
                                                   class="badge badge-pill badge-primary">{{ $label->name }}</a>
                                            @endforeach
                                        </p>
                                    </td>
                                    <td>
                                        <p>{{ $project->user->name }}</p>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <p>You have no projects.</p>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endauth
    </div>
@endsection
