<?php

namespace App\Http\Controllers;

use App\Department;
use App\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Factory|View
     */
    public function index()
    {
        $departments = Department::with('users')->paginate(5);

        return view('departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Factory|View
     */
    public function create()
    {
        $users = User::all();

        return view('departments.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:departments,name',
            'description' => 'required|string',
            'logo' => 'required|image|mimes:jpg,jpeg,bmp,png,gif|max:5120',
            'users' => 'array',
            'users.*' => 'integer|exists:users,id',
        ]);

        $fileName = time() . '.' . $request->logo->extension();

        $request->logo->move(storage_path('app/public/app/logo'), $fileName);

        $department = Department::create([
            'name' => $request->name,
            'description' => $request->description,
            'logo' => $fileName,
        ]);

        if ($request->users) {
            $department->users()->attach($request->users);
        }

        return redirect()
            ->route('departments.index')
            ->with('success', 'Отдел успешно создан.');
    }

    /**
     * Display the specified resource.
     *
     * @param Department $department
     * @return Factory|View
     */
    public function show(Department $department)
    {
        $department = Department::with('users')->find($department->id);
        $usersInDepartment = $department->users->unique();

        return view('departments.show', compact('department', 'usersInDepartment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Department $department
     * @return Factory|View
     */
    public function edit(Department $department)
    {
        $department = Department::with('users')->find($department->id);
        $usersInDepartament = $department->users->unique()->pluck('id')->toArray();
        $users = User::all();

        return view('departments.edit', compact('department', 'usersInDepartament', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Department $department
     * @return RedirectResponse
     */
    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                Rule::unique('departments')->ignore($department->id),
            ],
            'description' => 'required|string',
            'logo' => 'image|mimes:jpg,jpeg,bmp,png,gif|max:5120',
            'users' => 'array',
            'users.*' => 'integer|exists:users,id',
        ]);

        if ($request->logo) {
            $fileName = time() . '.' . $request->logo->extension();

            $request->logo->move(storage_path('app/public/app/logo'), $fileName);
        }

        $department->users()->detach();
        if ($request->users) {
            $department->users()->attach($request->users);
        }

        $department->update([
            'name' => $request->name,
            'description' => $request->description,
            'logo' => $fileName ?? $department->logo,
        ]);

        return redirect()
            ->route('departments.index')
            ->with('success', 'Отдел успешно обновлен.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Department $department
     * @return RedirectResponse
     * @throws \Exception
     */
    public function destroy(Department $department)
    {
        $logo = '/public/app/logo/' . $department->logo;
        if (Storage::exists($logo)) {
            Storage::delete($logo);
        }

        $department->users()->detach();
        $department->delete();

        return redirect()
            ->route('departments.index')
            ->with('success', 'Отдел успешно удален.');
    }
}
