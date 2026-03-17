<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Sanitiza cadenas de texto comunes de entrada.
     */
    protected function sanitizeText(?string $value, bool $collapseSpaces = true): string
    {
        $clean = strip_tags((string) $value);
        $clean = preg_replace('/[\x00-\x1F\x7F]/u', '', $clean) ?? '';
        if ($collapseSpaces) {
            $clean = preg_replace('/\s+/u', ' ', $clean) ?? '';
        }

        return trim($clean);
    }

    /**
     * Sanitiza nombre de usuario para login/registro.
     */
    protected function sanitizeUsername(?string $value): string
    {
        return $this->sanitizeText($value, true);
    }

    /**
     * Sanitiza correo electrónico para login/registro.
     */
    protected function sanitizeEmail(?string $value): string
    {
        return mb_strtolower($this->sanitizeText($value, true));
    }

    /**
     * Genera un CAPTCHA simple (suma) y lo guarda en sesión para el contexto dado.
     * Contextos soportados: 'login', 'recover1', 'recover2'.
     */
    protected function generateCaptcha(string $context): void
    {
        $a = random_int(1, 9);
        $b = random_int(1, 9);
        $question = sprintf('¿Cuánto es %d + %d?', $a, $b);
        $answer = (string) ($a + $b);

        // Guardar como arreglo anidado para acceder con dot notation en Blade
        $captcha = session()->get('captcha', []);
        $captcha[$context] = [
            'question' => $question,
            'answer' => $answer,
            'generated_at' => now()->toISOString(),
        ];
        session()->put('captcha', $captcha);
    }

    /**
     * Valida la respuesta del CAPTCHA para el contexto indicado.
     */
    protected function validateCaptcha(Request $request, string $context): bool
    {
        $captcha = session()->get("captcha.$context", null);
        $input = (string) $request->input('captcha_answer', '');
        $ok = is_array($captcha) && isset($captcha['answer']) && trim($input) === (string) $captcha['answer'];
        // Invalida el captcha usado para evitar reuso
        if ($ok) {
            $all = session()->get('captcha', []);
            unset($all[$context]);
            session()->put('captcha', $all);
        }
        return $ok;
    }

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
        $request->merge([
            'name' => $this->sanitizeUsername($request->input('name')),
            'email' => $this->sanitizeEmail($request->input('email')),
            'security_color_answer' => $this->sanitizeText($request->input('security_color_answer')),
            'security_animal_answer' => $this->sanitizeText($request->input('security_animal_answer')),
            'security_padre_answer' => $this->sanitizeText($request->input('security_padre_answer')),
        ]);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'min:3',
                'max:30',
                'regex:/^(?=.{3,30}$)(?=.*[A-Za-zÁÉÍÓÚÜÑáéíóúüñ])[A-Za-zÁÉÍÓÚÜÑáéíóúüñ0-9._\- ]+$/u',
                'not_regex:/<[^>]*>/',
                'unique:users,name',
            ],
            'email' => ['required', 'string', 'email', 'max:80', 'unique:users,email'],
            'password' => ['required', 'string', 'min:16', 'confirmed'],
            'security_color_answer' => [
                'required',
                'string',
                'min:2',
                'max:40',
                'regex:/^(?=.{2,40}$)(?=.*[A-Za-zÁÉÍÓÚÜÑáéíóúüñ])[A-Za-zÁÉÍÓÚÜÑáéíóúüñ .,\-]+$/u',
                'not_regex:/\d/',
                'not_regex:/<[^>]*>/',
            ],
            'security_animal_answer' => [
                'required',
                'string',
                'min:2',
                'max:40',
                'regex:/^(?=.{2,40}$)(?=.*[A-Za-zÁÉÍÓÚÜÑáéíóúüñ])[A-Za-zÁÉÍÓÚÜÑáéíóúüñ .,\-]+$/u',
                'not_regex:/\d/',
                'not_regex:/<[^>]*>/',
            ],
            'security_padre_answer' => [
                'required',
                'string',
                'min:2',
                'max:40',
                'regex:/^(?=.{2,40}$)(?=.*[A-Za-zÁÉÍÓÚÜÑáéíóúüñ])[A-Za-zÁÉÍÓÚÜÑáéíóúüñ .,\-]+$/u',
                'not_regex:/\d/',
                'not_regex:/<[^>]*>/',
            ],
        ], [
            'name.required' => 'El nombre de usuario es obligatorio.',
            'name.min' => 'El nombre de usuario debe tener al menos 3 caracteres.',
            'name.unique' => 'El nombre de usuario ya está registrado.',
            'name.max' => 'El nombre de usuario no puede superar los 30 caracteres.',
            'name.regex' => 'El nombre de usuario debe contener letras y solo usar caracteres válidos.',
            'name.not_regex' => 'El nombre de usuario no puede contener etiquetas HTML o código.',

            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico no tiene un formato válido.',
            'email.unique' => 'El correo electrónico ya está registrado.',
            'email.max' => 'El correo electrónico no puede superar los 80 caracteres.',

            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 16 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',

            'security_color_answer.required' => 'La respuesta de color favorito es obligatoria.',
            'security_color_answer.min' => 'La respuesta de color favorito debe tener al menos 2 caracteres.',
            'security_color_answer.max' => 'La respuesta de color favorito no puede superar los 40 caracteres.',
            'security_color_answer.regex' => 'La respuesta de color favorito solo debe contener letras y texto válido.',
            'security_color_answer.not_regex' => 'La respuesta de color favorito no debe contener números ni etiquetas HTML.',

            'security_animal_answer.required' => 'La respuesta de animal favorito es obligatoria.',
            'security_animal_answer.min' => 'La respuesta de animal favorito debe tener al menos 2 caracteres.',
            'security_animal_answer.max' => 'La respuesta de animal favorito no puede superar los 40 caracteres.',
            'security_animal_answer.regex' => 'La respuesta de animal favorito solo debe contener letras y texto válido.',
            'security_animal_answer.not_regex' => 'La respuesta de animal favorito no debe contener números ni etiquetas HTML.',

            'security_padre_answer.required' => 'La respuesta del nombre de tu padre es obligatoria.',
            'security_padre_answer.min' => 'La respuesta del nombre de tu padre debe tener al menos 2 caracteres.',
            'security_padre_answer.max' => 'La respuesta del nombre de tu padre no puede superar los 40 caracteres.',
            'security_padre_answer.regex' => 'La respuesta del nombre de tu padre solo debe contener letras y texto válido.',
            'security_padre_answer.not_regex' => 'La respuesta del nombre de tu padre no debe contener números ni etiquetas HTML.',
        ]);

        $this->ensureRegistrationTextQuality($validated);
        $this->ensurePasswordQuality((string) ($validated['password'] ?? ''));

        // Si es el primer usuario del sistema, lo marcamos como administrador.
        // A partir del segundo, serán usuarios operadores (rol "user").
        $role = User::count() === 0 ? User::ROLE_ADMIN : User::ROLE_OPERADOR;

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            // El cast "hashed" del modelo se encarga de encriptar la contraseña
            'password' => $validated['password'],
            'role' => $role,
            'security_color_answer' => Hash::make($validated['security_color_answer']),
            'security_animal_answer' => Hash::make($validated['security_animal_answer']),
            'security_padre_answer' => Hash::make($validated['security_padre_answer']),
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
        $request->merge([
            'name' => $this->sanitizeUsername($request->input('name')),
        ]);

        $credentials = $request->validate([
            // Usamos "name" como nombre de usuario
            'name' => ['required', 'string', 'min:3', 'max:30'],
            'password' => ['required', 'string', 'min:16'],
        ], [
            'name.required' => 'El nombre de usuario es obligatorio.',
            'name.min' => 'El nombre de usuario debe tener al menos 3 caracteres.',
            'name.max' => 'El nombre de usuario no puede superar los 30 caracteres.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 16 caracteres.',
            
        ]);
        // Buscar usuario por nombre de usuario
        $user = User::where('name', $credentials['name'])->first();

        // Si el usuario está bloqueado, no permitir el intento (bloqueo indefinido hasta que el admin lo desbloquee)
        if ($user && $user->locked_until) {
            return Redirect::back()
                ->withInput($request->only('name'))
                ->withErrors(['name' => 'El usuario está bloqueado. Contacte al administrador para desbloquear.']);
        }

        // Validar reCAPTCHA (si falla, no continuar)
        if (! $this->validateRecaptcha($request)) {
            return Redirect::back()
                ->withInput($request->only('name'))
                ->withErrors(['recaptcha' => 'Verificación reCAPTCHA fallida.']);
        }

        // Intentar iniciar sesión usando el nombre de usuario y contraseña
        if (Auth::attempt(['name' => $credentials['name'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();

            if ($user) {
                // Restablecer intentos y bloqueo al iniciar sesión correctamente
                $user->login_attempts = 0;
                $user->locked_until = null;
                $user->save();

                // Bitácora: ingreso (login) exitoso
                \App\Models\Bitacora::registrar(
                    'auth',
                    'login',
                    $user->id,
                    sprintf('Inicio de sesión exitoso del usuario "%s" (ID %d) desde IP %s.', $user->name, $user->id, $request->ip())
                );
            }

            // Limpiar requisito de captcha al iniciar sesión
            session()->forget(['captcha_login_required', 'captcha.login']);

            return Redirect::route('dashboard');
        }

        // Si las credenciales no son válidas, incrementar intentos y bloquear si llega a 3
        if ($user) {
            $user->login_attempts = ($user->login_attempts ?? 0) + 1;
            // Si el usuario va por el tercer intento, mostrar aviso
            if ($user->login_attempts === 2) {
                session()->flash('lock_remaining', '¡Atención! Si fallas el siguiente intento, tu cuenta será bloqueada.');
            }
            if ($user->login_attempts >= 3) {
                // Bloqueo indefinido (persistente) hasta acción del administrador
                $user->locked_until = now();
                $user->login_attempts = 0; // reiniciar el contador tras bloquear
                $user->save();

                    \App\Models\Bitacora::registrar(
                        'usuarios',
                        'bloqueado ',
                        $user->id,
                        sprintf('Bloqueó automáticamente al usuario "%s" (ID %d) por intentos fallidos de login.', $user->name, $user->id)
                    );

                return Redirect::back()
                    ->withInput($request->only('name'))
                    ->withErrors(['name' => 'Usuario bloqueado por intentos fallidos. Contacte al administrador.']);
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
        // Registrar logout antes de cerrar la sesión para conservar el user_id
        $uid = Auth::id();
        if ($uid) {
            \App\Models\Bitacora::registrar(
                'auth',
                'logout',
                $uid,
                sprintf('Cierre de sesión del usuario ID %d desde IP %s.', $uid, $request->ip())
            );
        }
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::route('login');
    }

    /**
     * Mostrar formulario de recuperación (flujo unificado por pasos).
     * Paso 1: correo
     * Paso 2: dos preguntas (y tercera opcional si fallan)
     * Paso 3: validación de token enviado por correo
     * Paso 4: nueva contraseña
     */
    public function showRecoveryForm(Request $request): View
    {
        // Si viene desde el enlace de "¿Olvidaste tu contraseña?" con reset=1, reiniciamos el flujo
        if ($request->query('reset')) {
            $request->session()->forget([
                'password_recover_user_id',
                'password_token_user_id',
                'password_token_email',
                'password_reset_user_id',
                'recovery_step',
                'show_third_question',
            ]);
        }

        $step = (int) $request->session()->get('recovery_step', 1);
        $showThirdQuestion = (bool) $request->session()->get('show_third_question', false);
        $tokenRemainingMs = 0;

        // Si estamos en el paso de token, calculamos el tiempo restante real.
        if ($step === 3) {
            $email = (string) $request->session()->get('password_token_email', '');
            if ($email !== '') {
                $record = DB::table('password_reset_tokens')->where('email', $email)->first();
                if ($record && isset($record->created_at)) {
                    $ttlMinutes = (int) config('security.recovery_token_ttl_minutes', 1);
                    $createdAt = \Illuminate\Support\Carbon::parse((string) $record->created_at);
                    $expiresAt = $createdAt->copy()->addMinutes($ttlMinutes);
                    $remainingMs = now()->diffInMilliseconds($expiresAt, false);
                    $tokenRemainingMs = max(0, (int) $remainingMs);
                }
            }
        }

        // Con reCAPTCHA v2 no generamos captcha local

        return view('auth.password_recover', [
            'step' => $step,
            'showThirdQuestion' => $showThirdQuestion,
            'tokenRemainingMs' => $tokenRemainingMs,
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
            // Validar reCAPTCHA en paso 1
            if (! $this->validateRecaptcha($request)) {
                return back()->withErrors(['recaptcha' => 'Verificación reCAPTCHA fallida.'])->onlyInput('email');
            }
            $request->merge([
                'email' => $this->sanitizeEmail($request->input('email')),
            ]);

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

            // Limpiar cualquier estado previo de recuperación
            $this->clearRecoveryTokenState($user->id, $user->email);

            $request->session()->put('password_recover_user_id', $user->id);
            $request->session()->put('recovery_step', 2);
            $request->session()->forget('show_third_question');

            return redirect()->route('password.recover');
        }

        // Paso 2: primero dos preguntas; si fallan, solo la tercera
        // Rate limit para solicitud de token (por usuario/correo)
        $rateLimitSeconds = (int) config('security.recovery_token_request_interval', 60);
        if ($step === 2) {
            $userId = $request->session()->get('password_recover_user_id');
            $user = $userId ? User::find($userId) : null;
            if ($user) {
                $lastTokenTime = $request->session()->get('last_token_sent_at_' . $user->id);
                if ($lastTokenTime && now()->diffInSeconds($lastTokenTime) < $rateLimitSeconds) {
                    $wait = $rateLimitSeconds - now()->diffInSeconds($lastTokenTime);
                    return back()->withErrors(['email' => "Debes esperar $wait segundos antes de solicitar un nuevo token."]);
                }
            }
            // Validar reCAPTCHA en paso 2
            if (! $this->validateRecaptcha($request)) {
                return back()->withErrors(['recaptcha' => 'Verificación reCAPTCHA fallida.']);
            }
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

                // Tercera respuesta correcta: generar y enviar token por correo
                $this->clearRecoveryTokenState($user->id, $user->email); // Limpia tokens previos
                $token = $this->generateRecoveryToken();
                $this->storeRecoveryToken($user->email, $token);
                try {
                    $this->sendRecoveryTokenByEmail($user, $token);
                } catch (\Throwable $e) {
                    Log::error('Error enviando token de recuperación: ' . $e->getMessage(), [
                        'user_id' => $user->id,
                        'email' => $user->email,
                    ]);
                    return back()->withErrors([
                        'email' => 'No fue posible enviar el token al correo. Intenta nuevamente en unos minutos.',
                    ]);
                }
                // Guardar timestamp del último envío de token
                $request->session()->put('last_token_sent_at_' . $user->id, now());
                $request->session()->forget(['show_third_question', 'password_recover_user_id']);
                $request->session()->put('password_token_user_id', $user->id);
                $request->session()->put('password_token_email', $user->email);
                $request->session()->put('recovery_step', 3);
                // Reiniciar contador de intentos de token
                $request->session()->put('token_attempts_' . $user->id, 0);
                return redirect()->route('password.recover')->with('status', 'Te enviamos un token a tu correo. Ingrésalo para continuar.');
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
                $this->clearRecoveryTokenState($user->id, $user->email); // Limpia tokens previos
                $token = $this->generateRecoveryToken();
                $this->storeRecoveryToken($user->email, $token);
                try {
                    $this->sendRecoveryTokenByEmail($user, $token);
                } catch (\Throwable $e) {
                    Log::error('Error enviando token de recuperación: ' . $e->getMessage(), [
                        'user_id' => $user->id,
                        'email' => $user->email,
                    ]);
                    return back()->withErrors([
                        'email' => 'No fue posible enviar el token al correo. Intenta nuevamente en unos minutos.',
                    ]);
                }
                $request->session()->put('last_token_sent_at_' . $user->id, now());
                $request->session()->forget(['show_third_question', 'password_recover_user_id']);
                $request->session()->put('password_token_user_id', $user->id);
                $request->session()->put('password_token_email', $user->email);
                $request->session()->put('recovery_step', 3);
                $request->session()->put('token_attempts_' . $user->id, 0);
                return redirect()->route('password.recover')->with('status', 'Te enviamos un token a tu correo. Ingrésalo para continuar.');
            }

            // Alguna falló: activar tercera pregunta y volver a mostrar el formulario
            $request->session()->put('show_third_question', true);

            return back()->withErrors(['security_color_answer' => 'Alguna respuesta es incorrecta, ahora debes responder la tercera pregunta de seguridad.']);
        }

        // Paso 3: validación de token enviado por correo o reenvío
        if ($step === 3) {
            $userId = $request->session()->get('password_token_user_id');
            $email = (string) $request->session()->get('password_token_email', '');
            if (! $userId || $email === '') {
                return redirect()->route('password.recover')->withErrors(['email' => 'Sesión de validación no válida.']);
            }
            $user = User::find($userId);
            if (! $user || mb_strtolower($user->email) !== mb_strtolower($email)) {
                return redirect()->route('password.recover')->withErrors(['email' => 'Usuario no encontrado para validar token.']);
            }

            // Si se presionó el botón de reenviar token
            if ($request->has('resend_token')) {
                $rateLimitSeconds = (int) config('security.recovery_token_request_interval', 60);
                $lastTokenTime = $request->session()->get('last_token_sent_at_' . $user->id);
                if ($lastTokenTime && now()->diffInSeconds($lastTokenTime) < $rateLimitSeconds) {
                    $wait = $rateLimitSeconds - now()->diffInSeconds($lastTokenTime);
                    return back()->withErrors(['token' => "Debes esperar $wait segundos antes de reenviar el token."]);
                }
                $this->clearRecoveryTokenState($user->id, $user->email); // Limpia tokens previos
                $token = $this->generateRecoveryToken();
                $this->storeRecoveryToken($user->email, $token);
                try {
                    $this->sendRecoveryTokenByEmail($user, $token);
                } catch (\Throwable $e) {
                    Log::error('Error reenviando token de recuperación: ' . $e->getMessage(), [
                        'user_id' => $user->id,
                        'email' => $user->email,
                    ]);
                    return back()->withErrors([
                        'token' => 'No fue posible reenviar el token. Intenta nuevamente en unos minutos.',
                    ]);
                }
                $request->session()->put('last_token_sent_at_' . $user->id, now());
                $request->session()->put('password_token_user_id', $user->id);
                $request->session()->put('password_token_email', $user->email);
                $request->session()->put('recovery_step', 3);
                $request->session()->put('token_attempts_' . $user->id, 0);
                return back()->with('status', 'Te reenviamos un nuevo token a tu correo.');
            }

            // Validación de token
            $validated = $request->validate([
                'token' => ['required', 'digits:6'],
            ], [
                'token.required' => 'Debes ingresar el token enviado a tu correo.',
                'token.digits' => 'El token debe tener exactamente 6 dígitos.',
            ]);

            // Limitar intentos de validación de token
            $maxAttempts = (int) config('security.recovery_token_max_attempts', 5);
            $attempts = (int) $request->session()->get('token_attempts_' . $user->id, 0) + 1;
            $request->session()->put('token_attempts_' . $user->id, $attempts);
            if ($attempts > $maxAttempts) {
                $this->clearRecoveryTokenState($user->id, $user->email);
                return redirect()->route('password.recover')->withErrors(['token' => 'Has superado el número máximo de intentos. Debes iniciar de nuevo el proceso.']);
            }

            $record = DB::table('password_reset_tokens')->where('email', $user->email)->first();
            if (! $record || ! isset($record->token, $record->created_at)) {
                return back()->withErrors(['token' => 'No se encontró un token activo. Solicita uno nuevo.']);
            }

            $ttlMinutes = (int) config('security.recovery_token_ttl_minutes', 1);
            $createdAt = \Illuminate\Support\Carbon::parse((string) $record->created_at);
            $expiresAt = $createdAt->addMinutes($ttlMinutes);
            if (now()->greaterThan($expiresAt)) {
                DB::table('password_reset_tokens')->where('email', $user->email)->delete();
                $this->clearRecoveryTokenState($user->id, $user->email);
                return back()->withErrors(['token' => 'El token ha expirado. Inicia de nuevo el proceso de recuperación.']);
            }

            $providedHash = hash('sha256', (string) $validated['token']);
            if (! hash_equals((string) $record->token, $providedHash)) {
                return back()->withErrors(['token' => 'El token ingresado es incorrecto.']);
            }

            // Token válido: eliminarlo para evitar reutilización y avanzar
            DB::table('password_reset_tokens')->where('email', $user->email)->delete();
            $request->session()->forget(['password_token_user_id', 'password_token_email', 'token_attempts_' . $user->id, 'last_token_sent_at_' . $user->id]);
            $request->session()->put('password_reset_user_id', $user->id);
            $request->session()->put('recovery_step', 4);

            return redirect()->route('password.recover');
        }

        // Paso 4: cambio de contraseña
        if ($step === 4) {
            $validated = $request->validate([
                'password' => ['required', 'string', 'min:16', 'confirmed'],
            ], [
                'password.required' => 'La nueva contraseña es obligatoria.',
                'password.min' => 'La nueva contraseña debe tener al menos 16 caracteres.',
                'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            ]);

            $this->ensurePasswordQuality((string) ($validated['password'] ?? ''));

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
            // Si la política lo permite, desbloqueamos automáticamente al restablecer contraseña
            if (config('auth.unlock_on_password_reset')) {
                $user->locked_until = null;
            }
            $user->save();

            $this->clearRecoveryTokenState($user->id, $user->email);

            return redirect()->route('login')->with('status', 'Contraseña actualizada correctamente. Ahora puedes iniciar sesión.');
        }

        // Si el paso no es válido, reiniciamos el flujo
        $request->session()->forget([
            'password_reset_user_id',
            'password_recover_user_id',
            'password_token_user_id',
            'password_token_email',
            'recovery_step',
            'show_third_question',
        ]);

        return redirect()->route('password.recover');
    }

    /**
     * Limpia todo el estado de recuperación de contraseña para un usuario/email.
     * Elimina token en BD, intentos/rate-limit en sesión y variables del flujo.
     */
    protected function clearRecoveryTokenState(?int $userId, ?string $email): void
    {
        if ($email) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
        }

        if ($userId) {
            session()->forget([
                'last_token_sent_at_' . $userId,
                'token_attempts_' . $userId,
            ]);
        }

        session()->forget([
            'password_recover_user_id',
            'password_token_user_id',
            'password_token_email',
            'password_reset_user_id',
            'recovery_step',
            'show_third_question',
        ]);
    }

    /**
     * Genera token numérico de 6 dígitos para recuperación.
     */
    protected function generateRecoveryToken(): string
    {
        return str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Guarda token de recuperación (hash) y fecha de creación.
     */
    protected function storeRecoveryToken(string $email, string $plainToken): void
    {
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => hash('sha256', $plainToken),
                'created_at' => now(),
            ]
        );
    }

    /**
     * Envía token de recuperación al correo del usuario.
     */
    protected function sendRecoveryTokenByEmail(User $user, string $token): void
    {
        $ttlMinutes = (int) config('security.recovery_token_ttl_minutes', 1);

        Mail::raw(
            "Hola {$user->name},\n\n" .
            "Tu token de verificación para recuperar la contraseña es: {$token}\n\n" .
            "Este token vence en {$ttlMinutes} minutos.\n\n" .
            "Si no solicitaste este cambio, ignora este mensaje.",
            function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Token de recuperación de contraseña');
            }
        );
    }

    /**
     * Compara respuestas de seguridad con hash seguro.
     * Mantiene compatibilidad con registros antiguos en texto plano.
     */
    protected function compareAnswer(string $stored, string $input): bool
    {
        // Si el valor almacenado es un hash bcrypt, usar verificación segura
        if (preg_match('/^\$2[ayb]\$/', $stored)) {
            return Hash::check($input, $stored);
        }
        // Compatibilidad retroactiva con datos existentes en texto plano
        return mb_strtolower(trim($stored)) === mb_strtolower(trim($input));
    }

    /**
     * Validar Google reCAPTCHA v2 usando la secret key del .env.
     */
    protected function validateRecaptcha(Request $request): bool
    {
        if (! config('services.recaptcha.enabled', true)) {
            return true;
        }

        $token = (string) $request->input('g-recaptcha-response', '');
        $secret = (string) config('services.recaptcha.secret_key', '');
        if ($secret === '') {
            Log::warning('reCAPTCHA secret key missing.');
            return false;
        }
        if ($token === '') {
            // Falta marcar el captcha en el formulario
            return false;
        }
        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secret,
                'response' => $token,
                'remoteip' => $request->ip(),
            ]);
            $json = $response->json();
            $ok = (bool) ($json['success'] ?? false);
            if (! $ok) {
                Log::info('reCAPTCHA failed', [
                    'ip' => $request->ip(),
                    'codes' => $json['error-codes'] ?? null,
                ]);
            }
            return $ok;
        } catch (\Throwable $e) {
            Log::error('reCAPTCHA verify exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Reglas heurísticas para bloquear texto basura en registro.
     */
    protected function ensureRegistrationTextQuality(array $validated): void
    {
        $username = (string) ($validated['name'] ?? '');
        if ($this->looksLikeGibberish($username, 18, 4) || preg_match('/^\d+$/u', $username)) {
            throw ValidationException::withMessages([
                'name' => 'El nombre de usuario no parece válido. Evita texto repetitivo o bloques numéricos.',
            ]);
        }

        $email = (string) ($validated['email'] ?? '');
        if ($this->hasSuspiciousEmailLocalPart($email)) {
            throw ValidationException::withMessages([
                'email' => 'El correo electrónico no parece válido. Usa un identificador real antes de @.',
            ]);
        }

        foreach (['security_color_answer', 'security_animal_answer', 'security_padre_answer'] as $field) {
            $value = (string) ($validated[$field] ?? '');

            if (preg_match('/\d/u', $value)) {
                throw ValidationException::withMessages([
                    $field => 'Las respuestas de seguridad no deben contener números.',
                ]);
            }

            if ($this->looksLikeGibberish($value, 18, 4)) {
                throw ValidationException::withMessages([
                    $field => 'La respuesta no parece válida. Evita texto aleatorio o repetitivo.',
                ]);
            }
        }
    }

    protected function ensurePasswordQuality(string $password): void
    {
        $password = trim($password);

        if ($password === '') {
            return;
        }

        if (preg_match('/^\d+$/u', $password)) {
            throw ValidationException::withMessages([
                'password' => 'La contraseña no puede estar compuesta solo por números.',
            ]);
        }

        if (preg_match('/^(.)\1{5,}$/u', $password)) {
            throw ValidationException::withMessages([
                'password' => 'La contraseña no puede ser una repetición del mismo carácter.',
            ]);
        }

        if ($this->looksLikeGibberish($password, 30, 7)) {
            throw ValidationException::withMessages([
                'password' => 'La contraseña no parece segura. Evita texto aleatorio o secuencias repetitivas.',
            ]);
        }
    }

    protected function hasSuspiciousEmailLocalPart(string $email): bool
    {
        $email = trim($email);
        $parts = explode('@', $email, 2);
        $local = $parts[0] ?? '';

        if ($local === '') {
            return true;
        }

        if (preg_match('/^\d+$/u', $local)) {
            return true;
        }

        if (preg_match('/^(.)\1{5,}$/u', $local)) {
            return true;
        }

        return false;
    }

    protected function looksLikeGibberish(string $text, int $maxWordLength, int $maxConsonantCluster): bool
    {
        $clean = $this->sanitizeText($text, true);

        if ($clean === '') {
            return false;
        }

        if (preg_match('/(.)\1{3,}/u', $clean)) {
            return true;
        }

        if (preg_match('/[bcdfghjklmnñpqrstvwxyz]{' . $maxConsonantCluster . ',}/iu', $clean)) {
            return true;
        }

        if (preg_match('/[\p{L}\d]{25,}/u', $clean)) {
            return true;
        }

        $words = preg_split('/[\s,.;:()\-\/#]+/u', $clean, -1, PREG_SPLIT_NO_EMPTY) ?: [];

        foreach ($words as $word) {
            if (mb_strlen($word, 'UTF-8') > $maxWordLength) {
                return true;
            }
        }

        return false;
    }
}
