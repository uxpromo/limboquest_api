<?php

use FinzorDev\Roles\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $userModelClass = config('roles.user_model', Auth::getProvider()->getModel());

        Schema::create('roles', function (Blueprint $table) use($userModelClass) {
            $table->id();
            $table->string('code')->nullable()->unique();
            $table->string('title');
            $table->text('description')->nullable()->default(null);
            $table->boolean('active')->default(true);
            $table->boolean('protected')->default(false);
            $table->boolean('visible')->default(true);
            $table->json('permissions')->nullable()->default(null);
            $table->foreignIdFor($userModelClass)->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('role_user', function (Blueprint $table) use($userModelClass) {
            $table->id();
            $table->foreignIdFor($userModelClass)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Role::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
        Schema::dropIfExists('role_user');
    }
};
