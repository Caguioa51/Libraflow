@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Borrow a Book</h1>
    @if(auth()->user()->isAdmin())
        <div class="alert alert-info">Admins cannot borrow books. Please use a user account to borrow.</div>
    @else
        <form action="{{ route('borrowings.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="book_id" class="form-label">Book</label>
                <select name="book_id" id="book_id" class="form-control" required>
                    @foreach($books as $book)
                        <option value="{{ $book->id }}">{{ $book->title }}</option>
                    @endforeach
                </select>
            </div>
            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
            <button type="submit" class="btn btn-primary">Borrow</button>
        </form>
    @endif
</div>
@endsection 