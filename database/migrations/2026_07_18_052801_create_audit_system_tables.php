<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create audit_logs table
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('event'); // login, logout, login_failed, created, updated, deleted
            $table->string('model_type')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->text('description');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // 2. Create audit_reports table
        Schema::create('audit_reports', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->foreignId('generated_by')->constrained('users')->onDelete('cascade');
            $table->string('status')->default('pending'); // pending, reviewed
            $table->text('comments')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });

        // 3. Register Spatie roles and permissions
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create new permissions
        $permissions = [
            'view_audit_logs',
            'manage_audit_reports',
            'review_audit_reports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Auditor role
        $roleAuditor = Role::firstOrCreate(['name' => 'auditor']);
        $roleAuditor->givePermissionTo(['view_audit_logs', 'manage_audit_reports']);

        // Assign review permission to Gerente role
        $roleGerente = Role::firstOrCreate(['name' => 'gerente']);
        $roleGerente->givePermissionTo(['review_audit_reports']);

        // 4. Create Auditor sample user
        $auditorUser = User::firstOrCreate(
            ['email' => 'auditor@example.com'],
            [
                'name' => 'Auditor User',
                'password' => Hash::make('password'),
                'role' => 'auditor',
                'email_verified_at' => now(),
            ]
        );

        if (! $auditorUser->hasRole('auditor')) {
            $auditorUser->assignRole('auditor');
        }

        // 5. Update user roles columns in database for consistency
        User::where('email', 'admin@example.com')->update(['role' => 'admin_sistema']);
        User::where('email', 'gerente@example.com')->update(['role' => 'gerente']);
        User::where('email', 'vendedor@example.com')->update(['role' => 'vendedor']);
        User::where('email', 'cliente@example.com')->update(['role' => 'cliente']);
        User::where('email', 'auditor@example.com')->update(['role' => 'auditor']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_reports');
        Schema::dropIfExists('audit_logs');

        // Optional: delete permissions and roles created
        Permission::whereIn('name', ['view_audit_logs', 'manage_audit_reports', 'review_audit_reports'])->delete();
        Role::where('name', 'auditor')->delete();
        User::where('email', 'auditor@example.com')->delete();
    }
};
