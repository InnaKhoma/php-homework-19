@extends('layouts.app')
@section('title', 'All users')
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
                    <div class="d-flex justify-content-end align-items-start">
                        <form action="{{ route('users.store') }}" method="POST">
                            @csrf
                            <div class="input-group mb-3">
                                <input type="number" name="number" class="form-control" placeholder="Number of users" aria-describedby="button-addon2">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit" id="button-addon2">Create Users</button>
                                </div>
                            </div>
                        </form>
                        <a class="btn btn-danger" href="{{ route('delete.all') }}" role="button" style="margin-left: 20px">
                            Delete all users</a>
                    </div>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <aside>
                    <form action="{{ route('users.index') }}">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control" id="name">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            @forelse($emails as $email)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="email[]"
                                           value="{{ $email }}" id="email">
                                    <label class="form-check-label" for="defaultCheck1">
                                        {{ $email }}
                                    </label>
                                </div>
                            @empty
                                No emails
                            @endforelse
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="verified" class="custom-control-input" id="verified"
                                {{ request()->verified === 'on' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="verified">Not Verified</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="country">Country</label>
                            <select multiple class="form-control" id="country" name="country[]">
                                @foreach($countries as $country)
                                    <option value={{ $country->id }}>{{ $country->country }}</option>
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
                        <a href="{{ route('users.index') }}" class="btn btn-secondary" role="button"
                           aria-pressed="true">Reset Filter</a>
                    </form>
                </aside>
            </div>
            <div class="col-9">
                <table class="table table-bordered">
                    <thead class="thead-light">
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Verified</th>
                        <th scope="col">Country</th>
                        <th scope="col">Continent</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if(isset($user->email_verified_at))
                                    <i class="fas fa-check"></i>
                                @endif
                            </td>
                            <td>{{ $user->country->country }}</td>
                            <td>{{ $user->country->continent->continent }}</td>
                        </tr>
                    @empty
                        There are no users.
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
