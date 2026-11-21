<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProgramController extends Controller
{
    // List programs. join() adds the institute code to each row.
    public function index(Request $request)
    {
        $query = DB::table('programs')
            ->join('institutes', 'institutes.id', '=', 'programs.institute_id')
            ->select('programs.*', 'institutes.code as institute_code');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('programs.code', 'like', "%{$search}%")
                  ->orWhere('programs.name', 'like', "%{$search}%")
                  ->orWhere('institutes.code', 'like', "%{$search}%");
            });
        }

        $programs = $query->orderBy('programs.code')->paginate(10)->withQueryString();

        return view('admin.programs.index', compact('programs'));
    }

    public function create()
    {
        $institutes = DB::table('institutes')->orderBy('code')->get();

        return view('admin.programs.create', compact('institutes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'institute_id' => 'required|exists:institutes,id',
            'code' => 'required|string|max:20|unique:programs,code',
            'name' => 'required|string|max:255',
        ]);

        DB::table('programs')->insert([
            'institute_id' => $validated['institute_id'],
            'code' => $validated['code'],
            'name' => $validated['name'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.programs.index')->with('success', 'Program added.');
    }

    public function edit($id)
    {
        $program = DB::table('programs')->where('id', $id)->first();
        abort_if(!$program, 404);

        $institutes = DB::table('institutes')->orderBy('code')->get();

        return view('admin.programs.edit', compact('program', 'institutes'));
    }

    public function update(Request $request, $id)
    {
        abort_if(!DB::table('programs')->where('id', $id)->exists(), 404);

        $validated = $request->validate([
            'institute_id' => 'required|exists:institutes,id',
            'code' => ['required', 'string', 'max:20', Rule::unique('programs', 'code')->ignore($id)],
            'name' => 'required|string|max:255',
        ]);

        DB::table('programs')->where('id', $id)->update([
            'institute_id' => $validated['institute_id'],
            'code' => $validated['code'],
            'name' => $validated['name'],
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.programs.index')->with('success', 'Program updated.');
    }

    public function destroy($id)
    {
        // A program that still has students cannot be deleted
        if (DB::table('students')->where('program_id', $id)->exists()) {
            return back()->with('error', 'Cannot delete this program because it still has students.');
        }

        DB::table('programs')->where('id', $id)->delete();

        return redirect()->route('admin.programs.index')->with('success', 'Program deleted.');
    }
}
