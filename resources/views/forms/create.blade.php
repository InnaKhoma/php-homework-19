@extends('layouts.app')
@section('title', $heading)
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ $heading }}</div>
                    <div class="card-body">
                        <form method="post" action="{{ $route }}">
                            @method($method)
                            @csrf
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ $input }}</label>
                                <div class="col-md-8">
                                    <input type="text" name="name" class="form-control" id="name"
                                           aria-describedby="name"
                                           value="{{ $value }}">
                                    @error('name')
                                    <div class="alert alert-danger" role="alert">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">{{ $button }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
