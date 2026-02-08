@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Logout Test</div>

                <div class="card-body">
                    <h3>Test Logout Functionality</h3>

                    <div class="mt-4">
                        <h5>Test 1: Direct Logout Form</h5>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-danger">Logout (Direct)</button>
                        </form>
                    </div>

                    <div class="mt-4">
                        <h5>Test 2: Test Route Logout</h5>
                        <form method="POST" action="{{ route('test.logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-warning">Logout (Test Route)</button>
                        </form>
                    </div>

                    <div class="mt-4">
                        <h5>Current User</h5>
                        <p>Name: {{ auth()->user()->name }}</p>
                        <p>Email: {{ auth()->user()->email }}</p>
                        <p>ID: {{ auth()->user()->id }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
