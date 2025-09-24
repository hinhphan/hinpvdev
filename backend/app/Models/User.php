<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Boolean;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Laravel\Passport\Contracts\OAuthenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements OAuthenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Summary of hasPermission
     * @param string $featureCode
     * @param string $permissionCode
     * @return bool
     */
    public function hasPermission(string $featureCode, string $permissionCode = null): bool
    {
        $this->load(['roles.permissions.feature', 'permissions.feature']);

        $roles = $this->roles;
        
        // If user has admin role, allow all permissions
        if ($roles->where('is_admin', Boolean::TRUE)->count() > 0) {
            return true;
        }

        $permissions = $this->roles->pluck('permissions')->flatten()->merge($this->permissions->toArray());
        $features = $permissions->groupBy('feature.code')->toArray();

        // If feature not exists, deny permission
        if (!isset($features[$featureCode])) {
            return false;
        }

        $permissions = $features[$featureCode];
        $actions = Arr::pluck($permissions, 'action');

        // If permission code not exists, deny permission
        if ($permissionCode && !in_array($permissionCode, $actions)) {
            return false;
        }

        return true;
    }

    /**
     * Summary of roles
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Role, User, \Illuminate\Database\Eloquent\Relations\Pivot>
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    /**
     * Summary of permissions
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Permission, User, \Illuminate\Database\Eloquent\Relations\Pivot>
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions', 'user_id', 'permission_id');
    }
}
