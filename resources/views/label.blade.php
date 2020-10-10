@extends('layouts.app')
@section('title', 'Label ' . $label->name)
@section('content')
    <div class="container">
        <div class="row">
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
                <div class="d-flex justify-content-between align-items-center">
                    <h1>Label: <span class="badge badge-pill badge-primary">{{ $label->name }}</span></h1>
                    <div class="d-flex justify-content-between align-items-center">
                        @can('update', $label)
                            <a class="btn btn-warning" href="{{ route('labels.edit', ['label' => $label->id]) }}"
                               role="button">Edit</a>
                        @endcan
                        @can('delete', $label)
                            <form action="{{ route('labels.destroy', ['label' => $label->id]) }}" method="POST">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="btn btn-danger" style="margin-left: 10px">Delete</button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 50px">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h5>Projects with this label</h5>
                    </div>
                    <ul class="list-group list-group-flush">
                        @forelse($label->projects as $project)
                            @can('view', $project)
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="{{ route('projects.show', [$project->id]) }}">{{ $project->name }}</a>
                                        @can('update', $label)
                                            <form
                                                action="{{ route('detach', ['project' => $project->id, 'label'=>$label->id]) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-light"><i
                                                        class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </li>
                            @endcan
                        @empty
                            <p>There is no projects with this label.</p>
                        @endforelse
                    </ul>
                </div>
                @can('update', $label)
                    <a href="{{ route('projects.list', ['label' => $label->id]) }}"
                       class="btn btn-success btn-block" role="button" aria-pressed="true">Add project</a>
                @endcan
            </div>
        </div>
    </div>
@endsection
