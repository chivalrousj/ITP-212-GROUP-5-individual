{{-- Shared form fields for create and edit. $program is null when creating. --}}
@if ($errors->any())
    <ul class="errors">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<div class="form-group">
    <label for="institute_id">Institute</label>
    <select id="institute_id" name="institute_id">
        <option value="">Select an institute</option>
        @foreach ($institutes as $institute)
            <option value="{{ $institute->id }}"
                {{ old('institute_id', $program->institute_id ?? '') == $institute->id ? 'selected' : '' }}>
                {{ $institute->code }} - {{ $institute->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="code">Code</label>
    <input type="text" id="code" name="code" value="{{ old('code', $program->code ?? '') }}">
</div>

<div class="form-group">
    <label for="name">Name</label>
    <input type="text" id="name" name="name" value="{{ old('name', $program->name ?? '') }}">
</div>
