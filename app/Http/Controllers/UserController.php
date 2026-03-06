<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
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
        $this->sanitizeCreateUserInput($request);

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:30', 'regex:/^(?=.{3,30}$)(?=(?:.*[A-Za-zÁÉÍÓÚÜÑáéíóúüñ]){3,})[A-Za-zÁÉÍÓÚÜÑáéíóúüñ0-9._\- ]+$/u', 'not_regex:/<[^>]*>/', 'unique:users,name'],
            'email' => ['required', 'string', 'email', 'max:60', 'unique:users,email'],
            'role' => ['required', 'in:admin,user'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::in(User::availablePermissions())],
            'password' => ['required', 'string', 'min:16', 'max:25', 'confirmed'],
            'security_color_answer' => ['required', 'string', 'min:2', 'max:30', 'regex:/^(?=.{2,30}$)(?=.*[A-Za-zÁÉÍÓÚÜÑáéíóúüñ])[A-Za-zÁÉÍÓÚÜÑáéíóúüñ0-9 .,\-]+$/u', 'not_regex:/<[^>]*>/'],
            'security_animal_answer' => ['required', 'string', 'min:2', 'max:30', 'regex:/^(?=.{2,30}$)(?=.*[A-Za-zÁÉÍÓÚÜÑáéíóúüñ])[A-Za-zÁÉÍÓÚÜÑáéíóúüñ0-9 .,\-]+$/u', 'not_regex:/<[^>]*>/'],
            'security_padre_answer' => ['required', 'string', 'min:2', 'max:30', 'regex:/^(?=.{2,30}$)(?=.*[A-Za-zÁÉÍÓÚÜÑáéíóúüñ])[A-Za-zÁÉÍÓÚÜÑáéíóúüñ0-9 .,\-]+$/u', 'not_regex:/<[^>]*>/'],
        ], [
            'name.required' => 'El nombre de usuario es obligatorio.',
            'name.min' => 'El nombre de usuario debe tener al menos 3 caracteres.',
            'name.unique' => 'El nombre de usuario ya esta registrado.',
            'name.max' => 'El nombre de usuario no puede superar los 30 caracteres.',
            'name.regex' => 'El nombre de usuario contiene caracteres no permitidos.',
            'name.not_regex' => 'El nombre de usuario no puede contener etiquetas HTML o código.',

            'email.required' => 'El correo electronico es obligatorio.',
            'email.email' => 'El correo electronico no tiene un formato valido.',
            'email.unique' => 'El correo electronico ya esta registrado.',
            'email.max' => 'El correo electronico no puede superar los 60 caracteres.',

            'role.required' => 'El rol es obligatorio.',
            'role.in' => 'El rol seleccionado no es valido.',

            'permissions.array' => 'El formato de permisos no es válido.',
            'permissions.*.in' => 'Se intentó asignar un permiso no permitido.',

            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 16 caracteres.',
            'password.max' => 'La contraseña no puede superar los 25 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',

            'security_color_answer.required' => 'La respuesta de color favorito es obligatoria.',
            'security_color_answer.min' => 'La respuesta de color favorito debe tener al menos 2 caracteres.',
            'security_color_answer.max' => 'La respuesta de color favorito no puede superar los 30 caracteres.',
            'security_color_answer.regex' => 'La respuesta de color favorito contiene caracteres no permitidos.',
            'security_color_answer.not_regex' => 'La respuesta de color favorito no puede contener etiquetas HTML o código.',

            'security_animal_answer.required' => 'La respuesta de animal favorito es obligatoria.',
            'security_animal_answer.min' => 'La respuesta de animal favorito debe tener al menos 2 caracteres.',
            'security_animal_answer.max' => 'La respuesta de animal favorito no puede superar los 30 caracteres.',
            'security_animal_answer.regex' => 'La respuesta de animal favorito contiene caracteres no permitidos.',
            'security_animal_answer.not_regex' => 'La respuesta de animal favorito no puede contener etiquetas HTML o código.',

            'security_padre_answer.required' => 'La respuesta del nombre de tu padre es obligatoria.',
            'security_padre_answer.min' => 'La respuesta del nombre de tu padre debe tener al menos 2 caracteres.',
            'security_padre_answer.max' => 'La respuesta del nombre de tu padre no puede superar los 30 caracteres.',
            'security_padre_answer.regex' => 'La respuesta del nombre de tu padre contiene caracteres no permitidos.',
            'security_padre_answer.not_regex' => 'La respuesta del nombre de tu padre no puede contener etiquetas HTML o código.',
        ]);

        /** @var User|null $authUser */
        $authUser = Auth::user();

        // Defensa en profundidad: solo un administrador puede asignar el rol administrador.
        if (
            $validated['role'] === User::ROLE_ADMIN
            && !($authUser?->isAdmin() ?? false)
        ) {
            return redirect()
                ->route('users.index')
                ->with('error', 'Solo un administrador puede asignar el rol administrador.');
        }

        $permissions = $validated['role'] === User::ROLE_ADMIN
            ? User::availablePermissions()
            : array_values(array_intersect(
                $validated['permissions'] ?? User::defaultOperadorPermissions(),
                User::availablePermissions()
            ));

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => $validated['role'],
            'permissions' => $permissions,
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
     * Sanitiza campos del formulario de crear usuario.
     */
    private function sanitizeCreateUserInput(Request $request): void
    {
        $name = is_string($request->input('name'))
            ? $this->normalizeSpaces(strip_tags($request->input('name')))
            : $request->input('name');

        $email = is_string($request->input('email'))
            ? mb_strtolower(trim(strip_tags($request->input('email'))), 'UTF-8')
            : $request->input('email');

        $securityColor = is_string($request->input('security_color_answer'))
            ? $this->normalizeSpaces(strip_tags($request->input('security_color_answer')))
            : $request->input('security_color_answer');

        $securityAnimal = is_string($request->input('security_animal_answer'))
            ? $this->normalizeSpaces(strip_tags($request->input('security_animal_answer')))
            : $request->input('security_animal_answer');

        $securityPadre = is_string($request->input('security_padre_answer'))
            ? $this->normalizeSpaces(strip_tags($request->input('security_padre_answer')))
            : $request->input('security_padre_answer');

        $request->merge([
            'name' => $name,
            'email' => $email,
            'security_color_answer' => $securityColor,
            'security_animal_answer' => $securityAnimal,
            'security_padre_answer' => $securityPadre,
        ]);
    }

    /**
     * Normaliza espacios internos y recorta extremos.
     */
    private function normalizeSpaces(string $value): string
    {
        return preg_replace('/\s+/u', ' ', trim($value)) ?? trim($value);
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
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::in(User::availablePermissions())],
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

            'permissions.array' => 'El formato de permisos no es válido.',
            'permissions.*.in' => 'Se intentó asignar un permiso no permitido.',

            'password.min' => 'La nueva contraseña debe tener al menos 16 caracteres.',

            'security_color_answer.required' => 'La respuesta de color favorito es obligatoria.',
            'security_color_answer.max' => 'La respuesta de color favorito no puede superar los 255 caracteres.',

            'security_animal_answer.required' => 'La respuesta de animal favorito es obligatoria.',
            'security_animal_answer.max' => 'La respuesta de animal favorito no puede superar los 255 caracteres.',

            'security_padre_answer.required' => 'La respuesta del nombre de tu padre es obligatoria.',
            'security_padre_answer.max' => 'La respuesta del nombre de tu padre no puede superar los 255 caracteres.',
        ]);

        /** @var User|null $authUser */
        $authUser = Auth::user();

        // Defensa en profundidad: solo un administrador puede asignar el rol administrador.
        if (
            $validated['role'] === User::ROLE_ADMIN
            && !($authUser?->isAdmin() ?? false)
        ) {
            return redirect()
                ->route('users.index')
                ->with('error', 'Solo un administrador puede asignar el rol administrador.');
        }

        // Evitar que el sistema quede sin administradores.
        if (
            $oldRole === User::ROLE_ADMIN
            && $validated['role'] !== User::ROLE_ADMIN
            && User::where('role', User::ROLE_ADMIN)->count() <= 1
        ) {
            return redirect()
                ->route('users.index')
                ->with('error', 'No se puede cambiar el rol del último administrador del sistema.');
        }

        $permissions = $validated['role'] === User::ROLE_ADMIN
            ? User::availablePermissions()
            : array_values(array_intersect(
                $validated['permissions'] ?? [],
                User::availablePermissions()
            ));

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->permissions = $permissions;
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

        // Evitar eliminar el último administrador del sistema.
        if (
            $user->role === User::ROLE_ADMIN
            && User::where('role', User::ROLE_ADMIN)->count() <= 1
        ) {
            return redirect()
                ->route('users.index')
                ->with('error', 'No se puede eliminar el último administrador del sistema.');
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
