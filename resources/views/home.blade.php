@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <p class="mb-3">{{ __('Welcome to My Tutorial. You are logged in!') }}</p>

                    <a href="{{ route('todo.index') }}" class="btn btn-outline-primary">
                        Click here to view your Todos
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
