<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class InstituteController extends Controller
{
    // List institutes with search and pagination
    public function index(Request $request)
    {
        $query = DB::table('institutes');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        $institutes = $query->orderBy('code')->paginate(10)->withQueryString();

        return view('admin.institutes.index', compact('institutes'));
    }

    public function create()
    {
        return view('admin.institutes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:institutes,code',
            'name' => 'required|string|max:255',
        ]);

        // Query Builder does not set the timestamps, so we set them ourselves
        DB::table('institutes')->insert([
            'code' => $validated['code'],
            'name' => $validated['name'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.institutes.index')->with('success', 'Institute added.');
    }

    public function edit($id)
    {
        $institute = DB::table('institutes')->where('id', $id)->first();
        abort_if(!$institute, 404);

        return view('admin.institutes.edit', compact('institute'));
    }

    public function update(Request $request, $id)
    {
        abort_if(!DB::table('institutes')->where('id', $id)->exists(), 404);

        $validated = $request->validate([
            // the code must be unique, but the current record may keep its own code
            'code' => ['required', 'string', 'max:20', Rule::unique('institutes', 'code')->ignore($id)],
            'name' => 'required|string|max:255',
        ]);

        DB::table('institutes')->where('id', $id)->update([
            'code' => $validated['code'],
            'name' => $validated['name'],
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.institutes.index')->with('success', 'Institute updated.');
    }

    public function destroy($id)
    {
        // exists() asks the database if at least one row matches
        if (DB::table('programs')->where('institute_id', $id)->exists()) {
            return back()->with('error', 'Cannot delete this institute because it still has programs.');
        }

        DB::table('institutes')->where('id', $id)->delete();

        return redirect()->route('admin.institutes.index')->with('success', 'Institute deleted.');
    }
}
