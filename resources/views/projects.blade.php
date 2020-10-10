@extends('layouts.app')
@section('title', 'All projects')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-3">
                <aside>
                    <form action="{{ route('projects.index') }}">
                        <div class="form-group">
                            <label>Label</label>
                            <select multiple class="form-control" id="label" name="label[]">
                                @foreach($labels as $label)
                                    <option value={{ $label }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="country">Email</label>
                            <select multiple class="form-control" id="email" name="email[]">
                                @foreach($users as $user)
                                    <option value={{ $user->id }}>{{ $user->email }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="continent">Continent</label>
                            <select multiple class="form-control" id="continent" name="continent[]">
                                @foreach($continents as $continent)
                                    <option>{{ $continent->continent }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Apply Filter</button>
                        <a href="{{ route('projects.index') }}" class="btn btn-secondary" role="button"
                           aria-pressed="true">Reset Filter</a>
                    </form>
                </aside>
            </div>
            <div class="col-9">
                <table class="table table-bordered">
                    <thead class="thead-light">
                    <tr>
                        <th scope="col">Project</th>
                        <th scope="col">Labels</th>
                        <th scope="col">User email</th>
                        <th scope="col">Continent</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($projects as $project)
                        <tr>
                            <td>{{ $project->name }}</td>
                            <td>
                                @forelse($project->labels as $label)
                                    <div class="badge badge-pill badge-primary">{{ $label->name }}</div>
                                @empty
                                    -
                                @endforelse
                            </td>
                            <td>{{ $project->user->email }}</td>
                            <td>{{ $project->user->country->continent->continent }}</td>
                        </tr>
                    @empty
                        There are no projects.
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
