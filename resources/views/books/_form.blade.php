<div class="mb-3">
    <label for="title" class="form-label">Title</label>
    <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $book->title ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="category_id" class="form-label">Category</label>
    <select name="category_id" id="category_id" class="form-control" required>
        <option value="">Select Category</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}" @if(old('category_id', $book->category_id ?? '') == $category->id) selected @endif>{{ $category->name }}</option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label for="author_id" class="form-label">Author</label>
    <select name="author_id" id="author_id" class="form-control" required>
        <option value="">Select Author</option>
        @foreach($authors as $author)
            <option value="{{ $author->id }}" @if(old('author_id', $book->author_id ?? '') == $author->id) selected @endif>{{ $author->name }}</option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label for="genre" class="form-label">Genre</label>
    <input type="text" name="genre" id="genre" class="form-control" value="{{ old('genre', $book->genre ?? '') }}">
</div>
<div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea name="description" id="description" class="form-control">{{ old('description', $book->description ?? '') }}</textarea>
</div>
<div class="mb-3">
    <label for="location" class="form-label">Location</label>
    <input type="text" name="location" id="location" class="form-control" value="{{ old('location', $book->location ?? '') }}">
</div>
<div class="mb-3">
    <label for="quantity" class="form-label">Quantity</label>
    <input type="number" name="quantity" id="quantity" min="1" class="form-control" value="{{ old('quantity', $book->quantity ?? 1) }}" required>
</div>

