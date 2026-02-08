<div class="mb-3">
    <label for="name" class="form-label">Name</label>
    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $author->name ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="bio" class="form-label">Bio</label>
    <textarea name="bio" id="bio" class="form-control">{{ old('bio', $author->bio ?? '') }}</textarea>
</div> 