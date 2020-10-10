@extends('layouts.app')
@section('title', 'Project')
@section('content')
    @can('view', $project)
    <div class="container">
        <div class="row justify-content-center">
            <div class="col">
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
                    <div class="card-header d-flex justify-content-between">
                        {{ $project->name }}
                        <div class="d-flex justify-content-between align-items-center">
                            @can('update', $project)
                                <a class="btn btn-warning"
                                   href="{{ route('projects.edit', ['project' => $project->id]) }}"
                                   role="button">Edit</a>
                            @endcan
                            @can('delete', $project)
                                <form action="{{ route('projects.destroy', ['project' => $project->id]) }}"
                                      method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" class="btn btn-danger" style="margin-left: 10px">Delete
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row justify-content-between">
                            <div class="col-6">
                                <div class="card">
                                    <div class="card-header">Project labels</div>
                                    <ul class="list-group list-group-flush">
                                        @forelse($project->labels as $label)
                                            <li class="list-group-item">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <a href="{{ route('labels.show', [$label->id]) }}">
                                                        {{ $label->name }}</a>
                                                    @can('update', $project)
                                                        <form
                                                            action="{{ route('detach', ['project' => $project->id, 'label'=>$label->id]) }}"
                                                            method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn btn-light"><i
                                                                    class="fas fa-trash-alt"></i></button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </li>
                                        @empty
                                            <p>This project has no labels.</p>
                                        @endforelse
                                    </ul>
                                </div>
                                @can('update', $project)
                                <a href="{{ route('labels.list', ['project' => $project->id]) }}"
                                   class="btn btn-success btn-block" role="button" aria-pressed="true">Add label</a>
                                @endcan
                            </div>

                            <div class="col-6">
                                <div class="card">
                                    <div class="card-header">
                                        Linked users
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        @forelse($project->links as $user)
                                            <li class="list-group-item">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    {{ $user->name }}
                                            @can('update', $project)
                                                <form
                                                    action="{{ route('detach.user', ['project' => $project->id, 'user'=>$user->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-light"><i
                                                            class="fas fa-trash-alt"></i></button>
                                                </form>
                                            @endcan
                                                </div>
                                            </li>
                                        @empty
                                            <p>No one linked to this project.</p>
                                        @endforelse
                                    </ul>
                                </div>
                                @can('update', $project)
                                <a href="{{ route('users.list', ['project' => $project->id]) }}"
                                   class="btn btn-success btn-block" role="button" aria-pressed="true">Link user</a>
                                @endcan
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    @endcan
@endsection
