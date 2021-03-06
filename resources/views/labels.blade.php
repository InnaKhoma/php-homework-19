@extends('layouts.app')
@section('title', 'All labels')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-3">
                <aside>
                    <form action="{{ route('labels.index') }}">
                        <div class="form-group">
                            <label>Project</label>
                            <select multiple class="form-control" id="project" name="project[]">
                                @foreach($projects as $project)
                                    <option value={{ $project->id }}>{{ $project->name }}</option>
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
                        <button type="submit" class="btn btn-primary">Apply Filter</button>
                        <a href="{{ route('labels.index') }}" class="btn btn-secondary" role="button"
                           aria-pressed="true">Reset Filter</a>
                    </form>
                </aside>
            </div>
            <div class="col-9">
                <table class="table table-bordered">
                    <thead class="thead-light">
                    <tr>
                        <th scope="col">Label</th>
                        <th scope="col">Projects</th>
                        <th scope="col">User email</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($labels as $label)
                        <tr>
                            <td>{{ $label->name }}</td>
                            <td>
                                @forelse($label->projects as $project)
                                    <p>{{ $project->name }}</p>
                                @empty
                                    -
                                @endforelse
                            </td>
                            <td>{{ $label->user->email }}</td>
                        </tr>
                    @empty
                        There are no labels.
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
