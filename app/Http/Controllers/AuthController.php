<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Mostrar formulario de registro.
     */
    public function showRegisterForm(): View
    {
        return view('auth.register');
    }

    /**
     * Procesar registro de usuario.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:users,name'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'security_color_answer' => ['required', 'string', 'max:255'],
            'security_animal_answer' => ['required', 'string', 'max:255'],
            'security_padre_answer' => ['required', 'string', 'max:255'],
        ], [
            'name.required' => 'El nombre de usuario es obligatorio.',
            'name.unique' => 'El nombre de usuario ya está registrado.',
            'name.max' => 'El nombre de usuario no puede superar los 255 caracteres.',

            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico no tiene un formato válido.',
            'email.unique' => 'El correo electrónico ya está registrado.',
            'email.max' => 'El correo electrónico no puede superar los 255 caracteres.',

            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
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
            // El cast "hashed" del modelo se encarga de encriptar la contraseña
            'password' => $validated['password'],
            'role' => 'user',
            'security_color_answer' => $validated['security_color_answer'],
            'security_animal_answer' => $validated['security_animal_answer'],
            'security_padre_answer' => $validated['security_padre_answer'],
        ]);

        // No iniciar sesión automáticamente. Redirigir al login con mensaje de éxito.
        return redirect()->route('login')->with('status', 'Registro completado con éxito. Ahora puedes iniciar sesión con tu usuario y contraseña.');
    }

    /**
     * Mostrar formulario de inicio de sesión.
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Procesar inicio de sesión.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            // Usamos "name" como nombre de usuario
            'name' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'name.required' => 'El nombre de usuario es obligatorio.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);
        // Buscar usuario por nombre de usuario
        $user = User::where('name', $credentials['name'])->first();

        // Si el usuario está bloqueado temporalmente, no permitir el intento
        if ($user && $user->locked_until && $user->locked_until->isFuture()) {
            // Diferencia en segundos (firmada) entre ahora y el fin del bloqueo
            $diff = now()->diffInSeconds($user->locked_until, false);

            if ($diff > 0) {
                $totalSeconds = $diff;

                // Solo mandamos el tiempo restante; el mensaje se muestra en la vista
                return Redirect::back()
                    ->withInput($request->only('name'))
                    ->with('lock_remaining', $totalSeconds);
            }

            // Si el tiempo ya expiró, limpiar bloqueo
            $user->locked_until = null;
            $user->login_attempts = 0;
            $user->save();
        }

        // Intentar iniciar sesión usando el nombre de usuario y contraseña
        if (Auth::attempt(['name' => $credentials['name'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();

            if ($user) {
                // Restablecer intentos y bloqueo al iniciar sesión correctamente
                $user->login_attempts = 0;
                $user->locked_until = null;
                $user->save();
            }

            return Redirect::route('dashboard');
        }

        // Si las credenciales no son válidas, incrementar intentos y bloquear si llega a 3
        if ($user) {
            $user->login_attempts = ($user->login_attempts ?? 0) + 1;

            if ($user->login_attempts >= 3) {
                // Bloquear 1 minuto a partir de este tercer intento fallido
                $user->locked_until = now()->addMinute();
                $user->login_attempts = 0; // reiniciar el contador tras bloquear
                $user->save();

                // Para el primer mensaje de bloqueo, mostramos siempre 60 segundos en el contador visual
                $totalSeconds = 60;

                return Redirect::back()
                    ->withInput($request->only('name'))
                    ->with('lock_remaining', $totalSeconds);
            }

            $user->save();
        }

        return Redirect::back()
            ->withErrors(['name' => 'Las credenciales no son válidas.'])
            ->withInput($request->only('name'));
    }



    /**
     * Cerrar sesión.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::route('login');
    }

    /**
     * Mostrar formulario de recuperación (flujo unificado por pasos).
     * Paso 1: correo
     * Paso 2: dos preguntas (y tercera opcional si fallan)
     * Paso 3: nueva contraseña
     */
    public function showRecoveryForm(Request $request): View
    {
        // Si viene desde el enlace de "¿Olvidaste tu contraseña?" con reset=1, reiniciamos el flujo
        if ($request->query('reset')) {
            $request->session()->forget(['password_recover_user_id', 'password_reset_user_id', 'recovery_step', 'show_third_question']);
        }

        $step = (int) $request->session()->get('recovery_step', 1);
        $showThirdQuestion = (bool) $request->session()->get('show_third_question', false);

        return view('auth.password_recover', [
            'step' => $step,
            'showThirdQuestion' => $showThirdQuestion,
        ]);
    }

    /**
     * Manejar todos los pasos de recuperación en una sola ruta POST.
     */
    public function handleRecovery(Request $request): RedirectResponse
    {
        $step = (int) $request->input('step', 1);

        // Paso 1: recibir correo
        if ($step === 1) {
            $validated = $request->validate([
                'email' => ['required', 'email'],
            ], [
                'email.required' => 'El correo electrónico es obligatorio.',
                'email.email' => 'El correo electrónico no tiene un formato válido.',
            ]);

            $user = User::where('email', $validated['email'])->first();

            if (! $user) {
                return back()->withErrors(['email' => 'No se encontró ningún usuario con ese correo.'])->onlyInput('email');
            }

            $request->session()->put('password_recover_user_id', $user->id);
            $request->session()->put('recovery_step', 2);
            $request->session()->forget('show_third_question');

            return redirect()->route('password.recover');
        }

        // Paso 2: primero dos preguntas; si fallan, solo la tercera
        if ($step === 2) {
            $userId = $request->session()->get('password_recover_user_id');
            if (! $userId) {
                return redirect()->route('password.recover')->withErrors(['email' => 'Sesión de recuperación no válida.']);
            }

            $user = User::find($userId);
            if (! $user) {
                return redirect()->route('password.recover')->withErrors(['email' => 'Usuario no encontrado.']);
            }

            // Si ya se activó la tercera pregunta, solo validamos esa
            if ($request->session()->get('show_third_question', false)) {
                $validated = $request->validate([
                    'security_padre_answer' => ['required', 'string'],
                ], [
                    'security_padre_answer.required' => 'La respuesta del nombre de tu padre es obligatoria.',
                ]);

                if (! $this->compareAnswer($user->security_padre_answer, $validated['security_padre_answer'])) {
                    return back()->withErrors(['security_padre_answer' => 'Respuesta incorrecta.']);
                }

                // Tercera respuesta correcta: pasar a cambio de contraseña
                $request->session()->forget(['show_third_question', 'password_recover_user_id']);
                $request->session()->put('password_reset_user_id', $user->id);
                $request->session()->put('recovery_step', 3);

                return redirect()->route('password.recover');
            }

            // Primer intento: validar solo color y animal
            $validated = $request->validate([
                'security_color_answer' => ['required', 'string'],
                'security_animal_answer' => ['required', 'string'],
            ], [
                'security_color_answer.required' => 'La respuesta de color favorito es obligatoria.',
                'security_animal_answer.required' => 'La respuesta de animal favorito es obligatoria.',
            ]);

            $colorOk = $this->compareAnswer($user->security_color_answer, $validated['security_color_answer']);
            $animalOk = $this->compareAnswer($user->security_animal_answer, $validated['security_animal_answer']);

            // Si ambas son correctas, pasar al cambio de contraseña
            if ($colorOk && $animalOk) {
                $request->session()->forget(['show_third_question', 'password_recover_user_id']);
                $request->session()->put('password_reset_user_id', $user->id);
                $request->session()->put('recovery_step', 3);

                return redirect()->route('password.recover');
            }

            // Alguna falló: activar tercera pregunta y volver a mostrar el formulario
            $request->session()->put('show_third_question', true);

            return back()->withErrors(['security_color_answer' => 'Alguna respuesta es incorrecta, ahora debes responder la tercera pregunta de seguridad.']);
        }

        // Paso 3: cambio de contraseña
        if ($step === 3) {
            $validated = $request->validate([
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ], [
                'password.required' => 'La nueva contraseña es obligatoria.',
                'password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
                'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            ]);

            $userId = $request->session()->get('password_reset_user_id');
            if (! $userId) {
                return redirect()->route('password.recover')->withErrors(['email' => 'Sesión de recuperación no válida.']);
            }

            $user = User::find($userId);
            if (! $user) {
                return redirect()->route('password.recover')->withErrors(['email' => 'Usuario no encontrado.']);
            }

            $user->password = $validated['password']; // se encripta por el cast "hashed"
            $user->login_attempts = 0;
            $user->save();

            $request->session()->forget(['password_reset_user_id', 'password_recover_user_id', 'recovery_step', 'show_third_question']);

            return redirect()->route('login')->with('status', 'Contraseña actualizada correctamente. Ahora puedes iniciar sesión.');
        }

        // Si el paso no es válido, reiniciamos el flujo
        $request->session()->forget(['password_reset_user_id', 'password_recover_user_id', 'recovery_step', 'show_third_question']);

        return redirect()->route('password.recover');
    }

    /**
     * Comparar respuestas de seguridad ignorando mayúsculas/minúsculas y espacios.
     */
    protected function compareAnswer(string $stored, string $input): bool
    {
        return mb_strtolower(trim($stored)) === mb_strtolower(trim($input));
    }
}
