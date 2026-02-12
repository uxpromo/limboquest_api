<?php

namespace App\Http\Resources\V1\Admin\User;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class CurrentUserResource extends JsonResource
{
    public function __construct($resource, private ?Request $request = null)
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'is_active' => $this->is_active,
            'is_admin' => $this->is_admin,
            'is_superadmin' => $this->is_superadmin,
            'last_login_at' => $this->last_login_at,

            // Роли и права будут управляться пакетом
            'roles' => $this->whenLoaded('roles'),
            'permissions' => $this->whenLoaded('permissions'),
        ];
    }
}
