<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
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
     * Crear un nuevo usuario desde el panel de administración.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:users,name'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'in:admin,user'],
            'password' => ['required', 'string', 'min:16', 'confirmed'],
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

            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 16 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',

            'security_color_answer.required' => 'La respuesta de color favorito es obligatoria.',
            'security_color_answer.max' => 'La respuesta de color favorito no puede superar los 255 caracteres.',

            'security_animal_answer.required' => 'La respuesta de animal favorito es obligatoria.',
            'security_animal_answer.max' => 'La respuesta de animal favorito no puede superar los 255 caracteres.',

            'security_padre_answer.required' => 'La respuesta del nombre de tu padre es obligatoria.',
            'security_padre_answer.max' => 'La respuesta del nombre de tu padre no puede superar los 255 caracteres.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => $validated['role'],
            'security_color_answer' => $validated['security_color_answer'],
            'security_animal_answer' => $validated['security_animal_answer'],
            'security_padre_answer' => $validated['security_padre_answer'],
        ]);

        Bitacora::registrar(
            'usuarios',
            'crear',
            $user->id,
            sprintf('Creó el usuario "%s" (ID %d, rol %s).', $user->name, $user->id, $user->role)
        );

        return redirect()->route('users.index')->with('status', 'Usuario creado correctamente.');
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
        $oldRole = $user->role; // Guardar rol previo para detectar cambios
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:users,name,' . $user->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'in:admin,user'],
            'password' => ['nullable', 'string', 'min:16'],
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

            'password.min' => 'La nueva contraseña debe tener al menos 16 caracteres.',

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

        // Bitácora: si cambió el rol, registrar el cambio específico
        if ($oldRole !== $validated['role']) {
            \App\Models\Bitacora::registrar(
                'usuarios',
                'cambiar_rol',
                $user->id,
                sprintf('Cambió el rol del usuario "%s" (ID %d) de %s a %s.', $user->name, $user->id, $oldRole, $user->role)
            );
        }

        Bitacora::registrar(
            'usuarios',
            'actualizar',
            $user->id,
            sprintf('Actualizó el usuario "%s" (ID %d, rol %s).', $user->name, $user->id, $user->role)
        );

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

        // Segunda capa: validar contraseña del administrador autenticado
        request()->validate([
            'admin_password' => ['required', 'current_password']
        ], [
            'admin_password.required' => 'Debes ingresar tu contraseña para confirmar la eliminación.',
            'admin_password.current_password' => 'La contraseña ingresada no es correcta.'
        ]);

        $nombre = $user->name;
        $id = $user->id;
        $role = $user->role;

        $user->delete();

        Bitacora::registrar(
            'usuarios',
            'eliminar',
            $id,
            sprintf('Eliminó el usuario "%s" (ID %d, rol %s).', $nombre, $id, $role)
        );

        return redirect()
            ->route('users.index')
            ->with('status', 'Usuario eliminado correctamente.');
    }

    /**
     * Desbloquear usuario (solo admin) con confirmación de contraseña del admin.
     */
    public function unlock(Request $request, User $user): RedirectResponse
    {
        // No permitir desbloquearse a sí mismo si no está bloqueado
        if (!$user->locked_until) {
            return redirect()->route('users.index')->with('status', 'Este usuario no está bloqueado.');
        }

        $request->validate([
            'admin_password' => ['required', 'current_password']
        ], [
            'admin_password.required' => 'Debes ingresar tu contraseña para confirmar el desbloqueo.',
            'admin_password.current_password' => 'La contraseña ingresada no es correcta.'
        ]);

        $user->locked_until = null;
        $user->login_attempts = 0;
        $user->save();

        Bitacora::registrar(
            'usuarios',
            'desbloquear',
            $user->id,
            sprintf('Desbloqueó al usuario "%s" (ID %d).', $user->name, $user->id)
        );

        return redirect()->route('users.index')->with('status', 'Usuario desbloqueado correctamente.');
    }
}
