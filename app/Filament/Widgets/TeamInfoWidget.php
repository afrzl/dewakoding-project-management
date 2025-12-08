<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Filament\Facades\Filament;

class TeamInfoWidget extends Widget
{
    protected string $view = 'filament.widgets.team-info-widget';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = -1;

    public function getTeam()
    {
        return Filament::getTenant();
    }

    public function getInviteCode(): ?string
    {
        return $this->getTeam()?->invite_code;
    }

    public function getTeamName(): ?string
    {
        return $this->getTeam()?->name;
    }

    public function getMembersCount(): int
    {
        return $this->getTeam()?->members()->count() ?? 0;
    }

    public function getJoinUrl(): string
    {
        return url('/join');
    }
}
