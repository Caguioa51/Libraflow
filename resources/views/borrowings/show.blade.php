@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Borrowing Details</h1>
    <div class="card">
        <div class="card-body">
            <h3 class="card-title">Book: {{ $borrowing->book->title ?? '-' }}</h3>
            <p><strong>User:</strong> {{ $borrowing->user->name ?? '-' }}</p>
            <p><strong>Borrowed At:</strong> {{ $borrowing->borrowed_at }}</p>
            <p><strong>Returned At:</strong> {{ $borrowing->returned_at ?? '-' }}</p>
            <p><strong>Status:</strong> {{ ucfirst($borrowing->status) }}</p>
            <a href="{{ route('borrowings.index') }}" class="btn btn-secondary">Back to List</a>
            @if(auth()->user()->isAdmin())
                @if($borrowing->status === 'borrowed')
                <form action="{{ route('borrowings.return', $borrowing) }}" method="POST" style="display:inline-block">
                    @csrf
                    @method('PATCH')
                    <button class="btn btn-success btn-sm">Mark as Returned</button>
                </form>
                @endif
                <form action="{{ route('borrowings.destroy', $borrowing) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Delete this borrowing record?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm">Delete</button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection 