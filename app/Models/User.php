<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;

class User extends Authenticatable implements FilamentUser, HasTenants
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
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

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_members')
            ->withTimestamps();
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function assignedTickets(): BelongsToMany
    {
        return $this->belongsToMany(Ticket::class, 'ticket_users');
    }

    public function createdTickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'created_by');
    }

    public function isAssignedToTicket(Ticket $ticket): bool
    {
        return $this->assignedTickets()->where('ticket_id', $ticket->id)->exists();
    }

    public function assignToTicket(Ticket $ticket): void
    {
        $this->assignedTickets()->syncWithoutDetaching($ticket->id);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class)->orderBy('created_at', 'desc');
    }

    public function unreadNotifications(): HasMany
    {
        return $this->hasMany(Notification::class)->unread()->orderBy('created_at', 'desc');
    }

    public function getUnreadNotificationsCountAttribute(): int
    {
        return $this->unreadNotifications()->count();
    }

    public function isSuperAdmin(): bool
    {
        // Cek apakah user punya role dengan team_id null menggunakan direct DB query
        // untuk bypass Spatie Permission team scoping
        return \Illuminate\Support\Facades\DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('model_has_roles.model_id', $this->id)
            ->where('model_has_roles.model_type', self::class)
            ->where('roles.name', 'super_admin')
            ->whereNull('model_has_roles.team_id')
            ->exists();
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'superadmin') {
            return $this->isSuperAdmin();
        }

        return true;
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'team_user')
            ->withTimestamps();
    }

    public function getTenants(Panel $panel): Collection
    {
        // Jika superadmin, return semua teams
        if ($this->isSuperAdmin()) {
            return \App\Models\Team::query()->get();
        }
        
        return $this->teams;
    }

    public function canAccessTenant(\Illuminate\Database\Eloquent\Model $tenant): bool
    {
        // Superadmin bisa akses semua tenant
        if ($this->isSuperAdmin()) {
            return true;
        }
        
        return $this->teams->contains($tenant);
    }

    /**
     * Determine if the entity has the given abilities.
     * Override untuk memberikan superadmin akses ke semua permissions
     */
    public function can($abilities, $arguments = []): bool
    {
        // Jika user adalah superadmin global, otomatis punya semua permissions
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Gunakan logic default dari Laravel
        return parent::can($abilities, $arguments);
    }
}
