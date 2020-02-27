<?php

namespace App\Http\Controllers;

use App\Department;
use App\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Factory|View
     */
    public function index()
    {
        $users = User::with('departments')
            ->latest()
            ->paginate(5);

        return view('users.index', compact('users'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Factory|View
     */
    public function create()
    {
        $departments = Department::all();

        return view('users.create', compact('departments'));
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
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string',
            'departments' => 'array',
            'departments.*' => 'integer|exists:departments,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($request->departments) {
            $user->departments()->attach($request->departments);
        }

        return redirect()
            ->route('users.index')
            ->with('success', 'Пользователь успешно создан.');
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return Factory|View
     */
    public function show(User $user)
    {
        $user = User::with('departments')->find($user->id);
        $departmentsOfUser = $user->departments->unique();

        return view('users.show', compact('user', 'departmentsOfUser'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @return Factory|View
     */
    public function edit(User $user)
    {
        $user = User::with('departments')->find($user->id);
        $departmentsOfUser = $user->departments->unique()->pluck('id')->toArray();
        $departments = Department::all();

        return view('users.edit', compact('user', 'departmentsOfUser', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'string|nullable',
            'departments' => 'array',
            'departments.*' => 'integer|exists:departments,id',
        ]);

        $user->departments()->detach();
        if ($request->departments) {
            $user->departments()->attach($request->departments);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'Пользователь успешно обновлен.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return RedirectResponse
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        $user->departments()->detach();
        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'Пользователь успешно удален.');
    }
}
