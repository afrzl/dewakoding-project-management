<?php

namespace App\Filament\Superadmin\Widgets;

use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\TicketHistory;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class ActiveUsersChart extends ChartWidget
{
    protected ?string $heading = 'Active Users Over Time';
    
    protected static ?int $sort = 2;
    
    protected ?string $maxHeight = '250px';

    public ?string $filter = '7d';

    protected function getFilters(): ?array
    {
        return [
            '7d' => 'Last 7 days',
            '30d' => 'Last 30 days',
            '90d' => 'Last 90 days',
            '12m' => 'Last 12 months',
        ];
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter;
        
        [$labels, $data] = $this->getChartData($activeFilter);

        return [
            'datasets' => [
                [
                    'label' => 'Active Users',
                    'data' => $data,
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    private function getChartData(string $filter): array
    {
        $labels = [];
        $data = [];

        switch ($filter) {
            case '7d':
                for ($i = 6; $i >= 0; $i--) {
                    $date = now()->subDays($i);
                    $labels[] = $date->format('D, M j');
                    $data[] = $this->getActiveUsersForDate($date);
                }
                break;

            case '30d':
                for ($i = 29; $i >= 0; $i--) {
                    $date = now()->subDays($i);
                    $labels[] = $date->format('M j');
                    $data[] = $this->getActiveUsersForDate($date);
                }
                break;

            case '90d':
                // Group by week for 90 days
                for ($i = 12; $i >= 0; $i--) {
                    $weekStart = now()->subWeeks($i)->startOfWeek();
                    $weekEnd = now()->subWeeks($i)->endOfWeek();
                    $labels[] = $weekStart->format('M j') . ' - ' . $weekEnd->format('M j');
                    $data[] = $this->getActiveUsersForDateRange($weekStart, $weekEnd);
                }
                break;

            case '12m':
                // Group by month for 12 months
                for ($i = 11; $i >= 0; $i--) {
                    $monthStart = now()->subMonths($i)->startOfMonth();
                    $monthEnd = now()->subMonths($i)->endOfMonth();
                    $labels[] = $monthStart->format('M Y');
                    $data[] = $this->getActiveUsersForDateRange($monthStart, $monthEnd);
                }
                break;
        }

        return [$labels, $data];
    }

    private function getActiveUsersForDate(Carbon $date): int
    {
        $startDate = $date->copy()->startOfDay();
        $endDate = $date->copy()->endOfDay();

        return $this->getActiveUsersForDateRange($startDate, $endDate);
    }

    private function getActiveUsersForDateRange(Carbon $startDate, Carbon $endDate): int
    {
        $ticketUsers = Ticket::whereBetween('created_at', [$startDate, $endDate])
            ->distinct()
            ->pluck('created_by');

        $historyUsers = TicketHistory::whereBetween('created_at', [$startDate, $endDate])
            ->distinct()
            ->pluck('user_id');

        $commentUsers = TicketComment::whereBetween('created_at', [$startDate, $endDate])
            ->distinct()
            ->pluck('user_id');

        return $ticketUsers->merge($historyUsers)
            ->merge($commentUsers)
            ->unique()
            ->filter()
            ->count();
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
        ];
    }
}
