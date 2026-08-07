<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminCreated;
use App\Mail\AdminPasswordReset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $actor = $request->user();

        // Super admin voit tout ; admin ne voit que les gestionnaires de contenu.
        $users = User::query()
            ->when(! $actor->isSuperAdmin(), fn ($q) => $q->where('role', User::ROLE_CONTENT))
            ->orderByRaw("CASE role WHEN 'super_admin' THEN 0 WHEN 'admin' THEN 1 ELSE 2 END")
            ->orderBy('name')
            ->get();

        return view('admin.users.index', compact('users'));
    }

    public function create(Request $request)
    {
        $roles = $this->assignableRoleLabels($request->user());
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $actor = $request->user();

        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'role'     => ['required', Rule::in($actor->assignableRoles())],
            'password' => 'required|string|min:8|confirmed',
        ], $this->messages());

        $plain = $validated['password'];

        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->is_active = true;
        $user->setPasswordAndCopy($plain);
        $user->save();

        if ($request->boolean('send_mail')) {
            try {
                Mail::to($user->email)->send(new AdminCreated($user, $plain));
            } catch (\Throwable $e) {
                Log::error('[AdminCreated] mail FAILED: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.users.index')
            ->with('success', "Compte de {$user->name} créé avec succès.");
    }

    public function edit(Request $request, User $user)
    {
        $this->authorizeManage($request->user(), $user);

        $roles = $this->assignableRoleLabels($request->user());
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $actor = $request->user();
        $this->authorizeManage($actor, $user);

        $rules = [
            'name'  => 'required|string|max:100',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'role'  => ['required', Rule::in($actor->assignableRoles())],
        ];
        if ($request->filled('password')) {
            $rules['password'] = 'string|min:8|confirmed';
        }

        $validated = $request->validate($rules, $this->messages());

        // Empêche de rétrograder le dernier super admin.
        if ($user->isSuperAdmin() && $validated['role'] !== User::ROLE_SUPER_ADMIN && $this->isLastSuperAdmin($user)) {
            return back()->withInput()->with('error', "Impossible de modifier le rôle du dernier super administrateur.");
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        if ($request->filled('password')) {
            $user->setPasswordAndCopy($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', "Compte de {$user->name} mis à jour.");
    }

    public function resetPassword(Request $request, User $user)
    {
        $this->authorizeManage($request->user(), $user);

        $plain = Str::password(12, symbols: false);
        $user->setPasswordAndCopy($plain);
        $user->save();

        $envoye = true;

        try {
            Mail::to($user->email)->send(new AdminPasswordReset($user, $plain));
        } catch (\Throwable $e) {
            $envoye = false;
            Log::error('[AdminPasswordReset] envoi échoué pour ' . $user->email . ' : ' . $e->getMessage());
        }

        return back()->with('success', "Nouveau mot de passe de {$user->name} : {$plain}"
            . ($envoye ? ' (envoyé par email)' : " — l'email n'a pas pu être envoyé, transmettez-le manuellement."));
    }

    public function toggleActive(Request $request, User $user)
    {
        $actor = $request->user();
        $this->authorizeManage($actor, $user);

        if ($user->id === $actor->id) {
            return back()->with('error', 'Vous ne pouvez pas désactiver votre propre compte.');
        }
        if ($user->is_active && $user->isSuperAdmin() && $this->isLastSuperAdmin($user)) {
            return back()->with('error', "Impossible de désactiver le dernier super administrateur.");
        }

        $user->is_active = ! $user->is_active;
        $user->save();

        $etat = $user->is_active ? 'réactivé' : 'désactivé';
        return back()->with('success', "Accès de {$user->name} {$etat}.");
    }

    public function destroy(Request $request, User $user)
    {
        $actor = $request->user();
        $this->authorizeManage($actor, $user);

        if ($user->id === $actor->id) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }
        if ($user->isSuperAdmin() && $this->isLastSuperAdmin($user)) {
            return back()->with('error', "Impossible de supprimer le dernier super administrateur.");
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Compte supprimé.');
    }

    // ─── Helpers ────────────────────────────────────────────────────────────────

    /** Autorise l'action ou renvoie une 403. */
    private function authorizeManage(User $actor, User $target): void
    {
        abort_unless($actor->canManage($target), 403, "Vous n'êtes pas autorisé à gérer ce compte.");
    }

    private function assignableRoleLabels(User $actor): array
    {
        return collect($actor->assignableRoles())
            ->mapWithKeys(fn ($role) => [$role => User::ROLES[$role]])
            ->all();
    }

    private function isLastSuperAdmin(User $user): bool
    {
        return $user->isSuperAdmin()
            && User::where('role', User::ROLE_SUPER_ADMIN)->where('id', '!=', $user->id)->doesntExist();
    }

    private function messages(): array
    {
        return [
            'name.required'      => 'Le nom est obligatoire.',
            'email.required'     => "L'email est obligatoire.",
            'email.unique'       => 'Cette adresse email est déjà utilisée.',
            'role.required'      => 'Le rôle est obligatoire.',
            'role.in'            => "Vous n'êtes pas autorisé à attribuer ce rôle.",
            'password.required'  => 'Le mot de passe est obligatoire.',
            'password.min'       => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ];
    }
}
