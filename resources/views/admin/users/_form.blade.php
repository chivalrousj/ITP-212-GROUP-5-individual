{{-- Shared form for create and edit. $user is null when creating. --}}
@if ($errors->any())
    <ul class="errors">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<div class="form-group">
    <label for="first_name">First Name</label>
    <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name ?? '') }}">
</div>

<div class="form-group">
    <label for="middle_name">Middle Name (optional)</label>
    <input type="text" id="middle_name" name="middle_name" value="{{ old('middle_name', $user->middle_name ?? '') }}">
</div>

<div class="form-group">
    <label for="last_name">Last Name</label>
    <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name ?? '') }}">
</div>

<div class="form-group">
    <label for="email">Email</label>
    <input type="email" id="email" name="email" value="{{ old('email', $user->email ?? '') }}">
</div>

<div class="form-group">
    <label for="role_id">Role</label>
    <select id="role_id" name="role_id">
        <option value="">Select a role</option>
        @foreach ($roles as $role)
            <option value="{{ $role->id }}" {{ old('role_id', $user->role_id ?? '') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="password">Password</label>
    @if ($user)
        <p class="hint">Leave blank to keep the current password.</p>
    @endif
    <input type="password" id="password" name="password">
</div>

<div class="form-group">
    <label for="password_confirmation">Confirm Password</label>
    <input type="password" id="password_confirmation" name="password_confirmation">
</div>

<div class="form-group">
    {{-- The hidden 0 makes an unticked box send a value too --}}
    <input type="hidden" name="is_active" value="0">
    <label class="check">
        <input type="checkbox" name="is_active" value="1"
            {{ old('is_active', $user->is_active ?? true) ? 'checked' : '' }}>
        Account is active
    </label>
</div>
