<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthSanitizationTest extends TestCase
{
    use RefreshDatabase;

    private const PASSWORD_16 = 'A123456789bcdefg';

    public function test_registro_sanitiza_campos_antes_de_guardar(): void
    {
        $response = $this->post(route('register'), [
            'name' => '   <b>Usuario Demo</b>   ',
            'email' => '   USER@Example.COM   ',
            'password' => self::PASSWORD_16,
            'password_confirmation' => self::PASSWORD_16,
            'security_color_answer' => '   <script>alerta</script> Azul   ',
            'security_animal_answer' => '   Perro   ',
            'security_padre_answer' => '   <i>Juan</i>   ',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', [
            'name' => 'Usuario Demo',
            'email' => 'user@example.com',
            'security_color_answer' => 'alerta Azul',
            'security_animal_answer' => 'Perro',
            'security_padre_answer' => 'Juan',
        ]);
    }

    public function test_registro_rechaza_nombre_de_usuario_basura_o_numerico(): void
    {
        $base = [
            'email' => 'usuario.demo@gmail.com',
            'password' => self::PASSWORD_16,
            'password_confirmation' => self::PASSWORD_16,
            'security_color_answer' => 'Azul',
            'security_animal_answer' => 'Perro',
            'security_padre_answer' => 'Carlos',
        ];

        $response1 = $this->post(route('register'), array_merge($base, [
            'name' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
        ]));
        $response1->assertSessionHasErrors(['name']);

        $response2 = $this->post(route('register'), array_merge($base, [
            'name' => '123123412412412341242345235',
        ]));
        $response2->assertSessionHasErrors(['name']);
    }

    public function test_registro_rechaza_correo_con_local_part_sospechoso(): void
    {
        $base = [
            'name' => 'usuario_demo',
            'password' => self::PASSWORD_16,
            'password_confirmation' => self::PASSWORD_16,
            'security_color_answer' => 'Azul',
            'security_animal_answer' => 'Perro',
            'security_padre_answer' => 'Carlos',
        ];

        $response1 = $this->post(route('register'), array_merge($base, [
            'email' => 'aaaaaaaaaa@gmail.com',
        ]));
        $response1->assertSessionHasErrors(['email']);

        $response2 = $this->post(route('register'), array_merge($base, [
            'email' => '12321421421@gmail.com',
        ]));
        $response2->assertSessionHasErrors(['email']);
    }

    public function test_registro_rechaza_preguntas_de_seguridad_con_numeros_o_texto_sin_sentido(): void
    {
        $base = [
            'name' => 'usuario_demo2',
            'email' => 'usuario.demo2@gmail.com',
            'password' => self::PASSWORD_16,
            'password_confirmation' => self::PASSWORD_16,
        ];

        $response1 = $this->post(route('register'), array_merge($base, [
            'security_color_answer' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
            'security_animal_answer' => 'Perro',
            'security_padre_answer' => 'Carlos',
        ]));
        $response1->assertSessionHasErrors(['security_color_answer']);

        $response2 = $this->post(route('register'), array_merge($base, [
            'security_color_answer' => 'Azul',
            'security_animal_answer' => '232142354235923',
            'security_padre_answer' => 'Carlos',
        ]));
        $response2->assertSessionHasErrors(['security_animal_answer']);
    }

    public function test_registro_rechaza_campos_que_superan_limites(): void
    {
        $response = $this->post(route('register'), [
            'name' => str_repeat('u', 31),
            'email' => str_repeat('a', 73) . '@mail.com',
            'password' => str_repeat('P', 41),
            'password_confirmation' => str_repeat('P', 41),
            'security_color_answer' => str_repeat('c', 41),
            'security_animal_answer' => str_repeat('a', 41),
            'security_padre_answer' => str_repeat('p', 41),
        ]);

        $response->assertSessionHasErrors([
            'name',
            'email',
            'password',
            'security_color_answer',
            'security_animal_answer',
            'security_padre_answer',
        ]);
    }

    public function test_login_sanitiza_nombre_usuario_antes_de_autenticar(): void
    {
        config(['services.recaptcha.enabled' => false]);

        $password = self::PASSWORD_16;

        $user = User::factory()->create([
            'name' => 'usuario_demo',
            'password' => $password,
            'role' => 'user',
            'locked_until' => null,
            'login_attempts' => 0,
        ]);

        $response = $this->post(route('login.post'), [
            'name' => '   <b>usuario_demo</b>   ',
            'password' => $password,
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_rechaza_longitudes_fuera_de_rango(): void
    {
        config(['services.recaptcha.enabled' => false]);

        $response = $this->post(route('login.post'), [
            'name' => str_repeat('u', 31),
            'password' => str_repeat('P', 41),
        ]);

        $response->assertSessionHasErrors(['name', 'password']);
    }

    public function test_registro_rechaza_contrasena_solo_numerica(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'usuario_seguro',
            'email' => 'usuario.seguro@gmail.com',
            'password' => '12412421412412412412',
            'password_confirmation' => '12412421412412412412',
            'security_color_answer' => 'Azul',
            'security_animal_answer' => 'Perro',
            'security_padre_answer' => 'Carlos',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    public function test_registro_rechaza_contrasena_con_patron_aleatorio_debil(): void
    {
        $password = 'ojdasdoasdioasdjaiodnoaisndo124124';

        $response = $this->post(route('register'), [
            'name' => 'usuario_seguro2',
            'email' => 'usuario.seguro2@gmail.com',
            'password' => $password,
            'password_confirmation' => $password,
            'security_color_answer' => 'Azul',
            'security_animal_answer' => 'Perro',
            'security_padre_answer' => 'Carlos',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    public function test_registro_rechaza_contrasena_repetitiva(): void
    {
        $password = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';

        $response = $this->post(route('register'), [
            'name' => 'usuario_seguro3',
            'email' => 'usuario.seguro3@gmail.com',
            'password' => $password,
            'password_confirmation' => $password,
            'security_color_answer' => 'Azul',
            'security_animal_answer' => 'Perro',
            'security_padre_answer' => 'Carlos',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    public function test_recover_paso_3_rechaza_contrasena_solo_numerica(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->withSession([
                'password_reset_user_id' => $user->id,
                'recovery_step' => 3,
            ])
            ->post(route('password.recover.handle'), [
                'step' => 3,
                'password' => '12412421412412412412',
                'password_confirmation' => '12412421412412412412',
            ]);

        $response->assertSessionHasErrors(['password']);
    }

    public function test_recover_paso_3_rechaza_contrasena_repetitiva(): void
    {
        $user = User::factory()->create();
        $password = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';

        $response = $this
            ->withSession([
                'password_reset_user_id' => $user->id,
                'recovery_step' => 3,
            ])
            ->post(route('password.recover.handle'), [
                'step' => 3,
                'password' => $password,
                'password_confirmation' => $password,
            ]);

        $response->assertSessionHasErrors(['password']);
    }

    public function test_recover_paso_3_rechaza_contrasena_patron_debil(): void
    {
        $user = User::factory()->create();
        $password = 'ojdasdoasdioasdjaiodnoaisndo124124';

        $response = $this
            ->withSession([
                'password_reset_user_id' => $user->id,
                'recovery_step' => 3,
            ])
            ->post(route('password.recover.handle'), [
                'step' => 3,
                'password' => $password,
                'password_confirmation' => $password,
            ]);

        $response->assertSessionHasErrors(['password']);
    }
}
