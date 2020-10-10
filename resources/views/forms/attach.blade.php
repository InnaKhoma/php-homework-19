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
                            @csrf

                            <div class="form-group row">
                                <div class="col">
                                    @forelse($items as $item)
                                        <div class="form-check">
                                            <input class="form-check-input" name="items[]" type="checkbox"
                                                   value="{{ $item->id }}" id="defaultCheck1">
                                            <label class="form-check-label" for="defaultCheck1">
                                                {{ $item->name }}
                                            </label>
                                        </div>
                                    @empty
                                        There is nothing to add.
                                    @endforelse
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary">Add</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
@endsection
