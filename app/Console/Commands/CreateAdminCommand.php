<?php

namespace App\Console\Commands;

use App\Models\User;
use FinzorDev\Roles\Models\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;
use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

class CreateAdminCommand extends Command
{
    protected $signature = 'user:create-admin';

    protected $description = 'Создание администратора через пошаговый мастер';

    public function handle(): int
    {
        info('🔧 Мастер создания администратора');
        info('');

        // Шаг 1: Email
        $email = $this->askEmail();

        // Шаг 2: Пароль
        $password = $this->askPassword();

        // Шаг 3: Имя
        $firstName = text(
            label: 'Имя',
            placeholder: 'Иван',
            required: false,
        );

        // Шаг 5: Фамилия
        $secondName = text(
            label: 'Фамилия',
            placeholder: 'Иванов',
            required: false,
        );

        // Шаг 6: Роли
        $roleIds = $this->askRoles();

        // Шаг 7: Подтверждение
        info('');
        info('📋 Проверьте данные:');
        info("Email: {$email}");
        info("Имя: " . ($firstName ?: '—'));
        info("Фамилия: " . ($secondName ?: '—'));
        if (!empty($roleIds)) {
            $roles = Role::query()->whereIn('id', $roleIds)->pluck('title')->toArray();
            info("Роли: " . implode(', ', $roles));
        }
        info('');

        $confirmed = confirm(
            label: 'Создать пользователя с указанными данными?',
            default: true,
        );

        if (!$confirmed) {
            info('❌ Создание отменено');
            return self::FAILURE;
        }

        // Создание пользователя
        try {
            $user = new User([
                'email' => $email,
                'password' => Hash::make($password),
                'first_name' => $firstName ?: null,
                'last_name' => $secondName ?: null,
            ]);
            $user->forceFill([
                'is_admin' => true,
                'is_active' => true,
                'is_superadmin' => false,
            ]);
            $user->save();

            // Назначение ролей
            if (!empty($roleIds)) {
                foreach ($roleIds as $roleId) {
                    $role = Role::query()->find($roleId);
                    if ($role) {
                        $user->attachRole($role);
                    }
                }
            }

            info('');
            info("✅ Пользователь успешно создан (ID: {$user->id})");

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error("❌ Ошибка при создании пользователя: {$e->getMessage()}");
            return self::FAILURE;
        }
    }

    /**
     * Запрашивает email с валидацией
     */
    private function askEmail(): string
    {
        return text(
            label: 'Email',
            placeholder: 'admin@example.com',
            required: true,
            validate: function (string $value): ?string {
                $validator = Validator::make(
                    ['email' => $value],
                    ['email' => 'required|email|unique:users,email']
                );

                if ($validator->fails()) {
                    return $validator->errors()->first('email');
                }

                return null;
            }
        );
    }

    /**
     * Запрашивает пароль с подтверждением
     */
    private function askPassword(): string
    {
        $minLength = 6;
        $password = password(
            label: 'Пароль',
            placeholder: 'Минимум ' . $minLength . ' символов',
            required: true,
            validate: fn(string $value): ?string => strlen($value) < $minLength
                ? 'Пароль должен содержать минимум ' . $minLength . ' символов'
                : null
        );

        $passwordConfirmation = password(
            label: 'Подтверждение пароля',
            required: true,
        );

        if ($password !== $passwordConfirmation) {
            $this->error('❌ Пароли не совпадают. Попробуйте снова.');
            return $this->askPassword();
        }

        return $password;
    }

    /**
     * Запрашивает роли пользователя
     *
     * @return array<int>
     */
    private function askRoles(): array
    {
        $roles = Role::query()->get();

        if ($roles->isEmpty()) {
            info('⚠️  В системе нет доступных ролей');
            return [];
        }

        $options = [];
        foreach ($roles as $role) {
            $options[$role->id] = $role->title;
        }

        $selected = multiselect(
            label: 'Выберите роли пользователя',
            options: $options,
            required: false,
            hint: 'Можно пропустить, если роли будут назначены позже'
        );

        return $selected;
    }
}
