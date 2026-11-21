{{-- Shared form fields for create and edit. $institute is null when creating. --}}
@if ($errors->any())
    <ul class="errors">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<div class="form-group">
    <label for="code">Code</label>
    <input type="text" id="code" name="code" value="{{ old('code', $institute->code ?? '') }}">
</div>

<div class="form-group">
    <label for="name">Name</label>
    <input type="text" id="name" name="name" value="{{ old('name', $institute->name ?? '') }}">
</div>
