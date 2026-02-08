@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">All Borrowings</h1>
    @if(auth()->user()->isAdmin())

    @endif
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Book</th>
                <th>User</th>
                <th>Borrowed At</th>
                <th>Returned At</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($borrowings as $borrowing)
            <tr>
                <td>{{ $borrowing->book->title ?? '-' }}</td>
                <td>{{ $borrowing->user->name ?? '-' }}</td>
                <td>{{ $borrowing->borrowed_at }}</td>
                <td>{{ $borrowing->returned_at ?? '-' }}</td>
                <td>{{ ucfirst($borrowing->status) }}</td>
                <td>
                    @if(auth()->user()->isAdmin())
                        @if($borrowing->status === 'borrowed')
                        <form action="{{ route('borrowings.mark-as-returned', $borrowing) }}" method="POST" style="display:inline-block">
                            @csrf
                            <button class="btn btn-success btn-sm">Mark as Returned</button>
                        </form>
                        @endif
                        <form action="{{ route('borrowings.destroy', $borrowing) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Delete this borrowing record?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div>
        {{ $borrowings->links() }}
    </div>
</div>
@endsection
