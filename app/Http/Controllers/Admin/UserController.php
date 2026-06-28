<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminCreated;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('role')->orderBy('name')->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required|in:admin,super_admin',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required'      => 'Le nom est obligatoire.',
            'email.required'     => "L'email est obligatoire.",
            'email.unique'       => 'Cette adresse email est déjà utilisée.',
            'role.required'      => 'Le rôle est obligatoire.',
            'password.required'  => 'Le mot de passe est obligatoire.',
            'password.min'       => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ]);

        $plainPassword = $validated['password'];
        $user = User::create($validated);

        if ($request->boolean('send_mail')) {
            try {
                Mail::to($user->email)->send(new AdminCreated($user, $plainPassword));
            } catch (\Exception $e) {
                \Log::error('Mail AdminCreated failed: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.users.index')
            ->with('success', "Compte de {$user->name} créé avec succès.");
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name'  => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|in:admin,super_admin',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'string|min:8|confirmed';
        }

        $validated = $request->validate($rules, [
            'name.required'      => 'Le nom est obligatoire.',
            'email.unique'       => 'Cette adresse email est déjà utilisée.',
            'password.min'       => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', "Compte de {$user->name} mis à jour.");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', "Compte supprimé.");
    }
}
