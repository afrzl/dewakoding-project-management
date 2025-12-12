<?php

namespace App\Filament\Superadmin\Widgets;

use App\Models\Team;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalTeams = Team::count();
        $totalUsers = User::count();
        
        // Users created this month
        $usersThisMonth = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        // Users created last month for comparison
        $usersLastMonth = User::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
        
        // Calculate percentage change
        $userGrowth = $usersLastMonth > 0 
            ? round((($usersThisMonth - $usersLastMonth) / $usersLastMonth) * 100, 1)
            : ($usersThisMonth > 0 ? 100 : 0);
        
        // Teams created this month
        $teamsThisMonth = Team::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        // Teams created last month
        $teamsLastMonth = Team::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
        
        $teamGrowth = $teamsLastMonth > 0 
            ? round((($teamsThisMonth - $teamsLastMonth) / $teamsLastMonth) * 100, 1)
            : ($teamsThisMonth > 0 ? 100 : 0);

        return [
            Stat::make('Total Workspaces', $totalTeams)
                ->description($teamsThisMonth . ' new this month')
                ->descriptionIcon($teamGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($teamGrowth >= 0 ? 'success' : 'danger')
                ->chart($this->getTeamChartData()),
            
            Stat::make('Total Users', $totalUsers)
                ->description($usersThisMonth . ' new this month')
                ->descriptionIcon($userGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($userGrowth >= 0 ? 'success' : 'danger')
                ->chart($this->getUserChartData()),
            
            Stat::make('Active Users (7 days)', $this->getActiveUsersCount())
                ->description('Users with activity')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info')
                ->chart($this->getActiveUsersChartData()),
        ];
    }

    private function getTeamChartData(): array
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $data[] = Team::whereDate('created_at', $date)->count();
        }
        return $data;
    }

    private function getUserChartData(): array
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $data[] = User::whereDate('created_at', $date)->count();
        }
        return $data;
    }

    private function getActiveUsersCount(): int
    {
        $startDate = now()->subDays(6)->startOfDay();
        $endDate = now()->endOfDay();

        // Get unique user IDs from tickets, ticket history, and comments
        $ticketUsers = \App\Models\Ticket::whereBetween('created_at', [$startDate, $endDate])
            ->distinct()
            ->pluck('created_by');

        $historyUsers = \App\Models\TicketHistory::whereBetween('created_at', [$startDate, $endDate])
            ->distinct()
            ->pluck('user_id');

        $commentUsers = \App\Models\TicketComment::whereBetween('created_at', [$startDate, $endDate])
            ->distinct()
            ->pluck('user_id');

        return $ticketUsers->merge($historyUsers)
            ->merge($commentUsers)
            ->unique()
            ->count();
    }

    private function getActiveUsersChartData(): array
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $startDate = $date->copy()->startOfDay();
            $endDate = $date->copy()->endOfDay();

            $ticketUsers = \App\Models\Ticket::whereBetween('created_at', [$startDate, $endDate])
                ->distinct()
                ->pluck('created_by');

            $historyUsers = \App\Models\TicketHistory::whereBetween('created_at', [$startDate, $endDate])
                ->distinct()
                ->pluck('user_id');

            $commentUsers = \App\Models\TicketComment::whereBetween('created_at', [$startDate, $endDate])
                ->distinct()
                ->pluck('user_id');

            $data[] = $ticketUsers->merge($historyUsers)
                ->merge($commentUsers)
                ->unique()
                ->count();
        }
        return $data;
    }
}
