{{-- Shared form fields for create and edit. $student is null when creating. --}}
@if ($errors->any())
    <ul class="errors">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<div class="form-group">
    <label for="first_name">First Name</label>
    <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $student?->user?->first_name) }}">
</div>

<div class="form-group">
    <label for="middle_name">Middle Name (optional)</label>
    <input type="text" id="middle_name" name="middle_name" value="{{ old('middle_name', $student?->user?->middle_name) }}">
</div>

<div class="form-group">
    <label for="last_name">Last Name</label>
    <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $student?->user?->last_name) }}">
</div>

<div class="form-group">
    <label for="email">Email</label>
    <input type="email" id="email" name="email" value="{{ old('email', $student?->user?->email) }}">
</div>

<div class="form-group">
    <label for="student_no">Student Number</label>
    <input type="text" id="student_no" name="student_no" value="{{ old('student_no', $student?->student_no) }}">
</div>

<div class="form-group">
    <label for="sex">Sex</label>
    <select id="sex" name="sex">
        <option value="">Select</option>
        @foreach (['Male', 'Female'] as $sex)
            <option value="{{ $sex }}" {{ old('sex', $student?->sex) === $sex ? 'selected' : '' }}>{{ $sex }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="year_level">Year Level</label>
    <select id="year_level" name="year_level">
        <option value="">Select</option>
        @foreach (range(1, 5) as $year)
            <option value="{{ $year }}" {{ (int) old('year_level', $student?->year_level) === $year ? 'selected' : '' }}>{{ $year }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="institute">Institute</label>
    {{-- This dropdown only filters the program list. It is not saved. --}}
    <select id="institute" name="institute">
        <option value="">All institutes</option>
        @foreach ($institutes as $institute)
            <option value="{{ $institute->id }}"
                {{ old('institute', $student?->program?->institute_id) == $institute->id ? 'selected' : '' }}>
                {{ $institute->code }} - {{ $institute->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="program_id">Program</label>
    <select id="program_id" name="program_id">
        <option value="">Select a program</option>
        @foreach ($programs as $program)
            <option value="{{ $program->id }}" data-institute="{{ $program->institute_id }}"
                {{ old('program_id', $student?->program_id) == $program->id ? 'selected' : '' }}>
                {{ $program->code }} - {{ $program->name }}
            </option>
        @endforeach
    </select>
</div>

@include('officer.students._script')
<script>linkSelects('institute', 'program_id');</script>
