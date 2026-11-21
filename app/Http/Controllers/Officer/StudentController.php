<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\Institute;
use App\Models\Program;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    // List students with search, institute and program filters, and 15 per page
    public function index(Request $request)
    {
        // with() loads the related rows at once, so the page does not run one query per student
        $query = Student::with(['user', 'program.institute']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('student_no', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) like ?", ["%{$search}%"]);
                  });
            });
        }

        if ($instituteId = $request->input('institute')) {
            $query->whereHas('program', fn ($p) => $p->where('institute_id', $instituteId));
        }

        if ($programId = $request->input('program')) {
            $query->where('program_id', $programId);
        }

        $students = $query->orderBy('student_no')->paginate(15)->withQueryString();

        $institutes = Institute::orderBy('code')->get();
        $programs = Program::orderBy('code')->get();

        return view('officer.students.index', compact('students', 'institutes', 'programs'));
    }

    public function create()
    {
        $institutes = Institute::orderBy('code')->get();
        $programs = Program::orderBy('code')->get();

        return view('officer.students.create', compact('institutes', 'programs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email:rfc|max:255',
            'student_no' => 'required|string|max:50|unique:students,student_no',
            'sex' => 'required|in:Male,Female',
            'year_level' => 'required|integer|between:1,5',
            'program_id' => 'required|exists:programs,id',
        ]);

        // If this email already has an account, it must be a Student account without a student profile
        $existing = User::where('email', $validated['email'])->first();

        if ($existing && !$existing->hasRole('Student')) {
            return back()->withInput()->withErrors(['email' => 'This email belongs to a non-student account.']);
        }

        if ($existing && Student::where('user_id', $existing->id)->exists()) {
            return back()->withInput()->withErrors(['email' => 'This account already has a student profile.']);
        }

        $studentRole = Role::where('name', 'Student')->firstOrFail();

        // A transaction saves both rows, or neither one
        DB::transaction(function () use ($validated, $studentRole) {
            // firstOrCreate reuses a self-registered account, or makes a new one.
            // The temporary password is the hashed student number.
            $user = User::firstOrCreate(
                ['email' => $validated['email']],
                [
                    'first_name' => $validated['first_name'],
                    'middle_name' => $validated['middle_name'] ?? null,
                    'last_name' => $validated['last_name'],
                    'password' => Hash::make($validated['student_no']),
                    'role_id' => $studentRole->id,
                    'is_active' => true,
                    'is_admin' => false,
                ]
            );

            Student::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'program_id' => $validated['program_id'],
                    'student_no' => $validated['student_no'],
                    'sex' => $validated['sex'],
                    'year_level' => $validated['year_level'],
                ]
            );
        });

        return redirect()->route('officer.students.index')->with('success', 'Student registered.');
    }

    public function edit(Student $student)
    {
        $student->load(['user', 'program']);

        $institutes = Institute::orderBy('code')->get();
        $programs = Program::orderBy('code')->get();

        return view('officer.students.edit', compact('student', 'institutes', 'programs'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            // the email must be unique, but this student's own account may keep it
            'email' => ['required', 'string', 'email:rfc', 'max:255', Rule::unique('users', 'email')->ignore($student->user_id)],
            'student_no' => ['required', 'string', 'max:50', Rule::unique('students', 'student_no')->ignore($student->id)],
            'sex' => 'required|in:Male,Female',
            'year_level' => 'required|integer|between:1,5',
            'program_id' => 'required|exists:programs,id',
        ]);

        // Update the users row and the students row together
        DB::transaction(function () use ($validated, $student) {
            $student->user->update([
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'] ?? null,
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
            ]);

            $student->update([
                'program_id' => $validated['program_id'],
                'student_no' => $validated['student_no'],
                'sex' => $validated['sex'],
                'year_level' => $validated['year_level'],
            ]);
        });

        return redirect()->route('officer.students.index')->with('success', 'Student updated.');
    }

    public function destroy(Student $student)
    {
        // Deleting the user also removes the student row, because of the cascade on user_id
        $student->user->delete();

        return redirect()->route('officer.students.index')->with('success', 'Student deleted.');
    }
}
