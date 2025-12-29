<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Listado de usuarios.
     */
    public function index(): View
    {
        $users = User::orderBy('id')->get();

        return view('users.index', compact('users'));
    }

    /**
     * Formulario de edicion de usuario.
     */
    public function edit(User $user): View
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Actualizar datos de un usuario.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:users,name,' . $user->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'in:admin,user'],
            'password' => ['nullable', 'string', 'min:8'],
            'security_color_answer' => ['required', 'string', 'max:255'],
            'security_animal_answer' => ['required', 'string', 'max:255'],
            'security_padre_answer' => ['required', 'string', 'max:255'],
        ], [
            'name.required' => 'El nombre de usuario es obligatorio.',
            'name.unique' => 'El nombre de usuario ya esta registrado.',
            'name.max' => 'El nombre de usuario no puede superar los 255 caracteres.',

            'email.required' => 'El correo electronico es obligatorio.',
            'email.email' => 'El correo electronico no tiene un formato valido.',
            'email.unique' => 'El correo electronico ya esta registrado.',
            'email.max' => 'El correo electronico no puede superar los 255 caracteres.',

            'role.required' => 'El rol es obligatorio.',
            'role.in' => 'El rol seleccionado no es valido.',

            'password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',

            'security_color_answer.required' => 'La respuesta de color favorito es obligatoria.',
            'security_color_answer.max' => 'La respuesta de color favorito no puede superar los 255 caracteres.',

            'security_animal_answer.required' => 'La respuesta de animal favorito es obligatoria.',
            'security_animal_answer.max' => 'La respuesta de animal favorito no puede superar los 255 caracteres.',

            'security_padre_answer.required' => 'La respuesta del nombre de tu padre es obligatoria.',
            'security_padre_answer.max' => 'La respuesta del nombre de tu padre no puede superar los 255 caracteres.',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->security_color_answer = $validated['security_color_answer'];
        $user->security_animal_answer = $validated['security_animal_answer'];
        $user->security_padre_answer = $validated['security_padre_answer'];

        // Si se envia nueva contraseña, la actualizamos
        if (!empty($validated['password'])) {
            $user->password = $validated['password']; // se encripta por el cast "hashed" del modelo
        }

        $user->save();

        return redirect()->route('users.index')->with('status', 'Usuario actualizado correctamente.');
    }

    /**
     * Eliminar un usuario.
     */
    public function destroy(User $user): RedirectResponse
    {
        // Evitar que un usuario elimine su propia cuenta desde aquí
        if (Auth::id() === $user->id) {
            return redirect()
                ->route('users.index')
                ->with('error', 'No puedes eliminar tu propio usuario desde este módulo.');
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('status', 'Usuario eliminado correctamente.');
    }
}
