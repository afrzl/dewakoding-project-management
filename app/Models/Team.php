<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Observers\TeamObserver;
use Filament\Models\Contracts\HasName;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


#[ObservedBy(TeamObserver::class)]
class Team extends Model implements HasName
{
    /** @use HasFactory<\Database\Factories\TeamFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'invite_code',
    ];

    protected static function booted()
    {
        static::creating(function ($team) {
            if (empty($team->invite_code)) {
                $team->invite_code = strtoupper(Str::random(8));
            }
        });
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_user')
            ->withTimestamps();
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function getFilamentName(): string
    {
        return $this->name;
    }
}
