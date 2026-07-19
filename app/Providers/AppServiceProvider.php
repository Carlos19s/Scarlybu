<?php

namespace App\Providers;

use App\Models\AuditLog;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();

        // Log successful logins
        Event::listen(Login::class, function ($event) {
            if ($event->user->role !== 'cliente') {
                AuditLog::create([
                    'user_id' => $event->user->id,
                    'event' => 'login',
                    'description' => "El usuario {$event->user->name} inició sesión.",
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            }
        });

        // Log logouts
        Event::listen(Logout::class, function ($event) {
            if ($event->user && $event->user->role !== 'cliente') {
                AuditLog::create([
                    'user_id' => $event->user->id,
                    'event' => 'logout',
                    'description' => "El usuario {$event->user->name} cerró sesión.",
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            }
        });

        // Log failed login attempts
        Event::listen(Failed::class, function ($event) {
            if ($event->user && $event->user->role === 'cliente') {
                return;
            }
            if (isset($event->credentials['email'])) {
                $user = User::where('email', $event->credentials['email'])->first();
                if ($user && $user->role === 'cliente') {
                    return;
                }
            }

            AuditLog::create([
                'user_id' => $event->user?->id,
                'event' => 'login_failed',
                'description' => 'Intento fallido de inicio de sesión para el correo: '.($event->credentials['email'] ?? 'desconocido'),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        });
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
