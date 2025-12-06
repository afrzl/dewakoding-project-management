<?php

namespace App\Notifications;

use App\Models\Ticket;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TicketAssignedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Ticket $ticket,
        public ?User $assignedBy = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $assignedByName = $this->assignedBy?->name ?? 'Someone';
        
        return FilamentNotification::make()
            ->title('Ticket Assigned to You')
            ->body("You have been assigned to ticket \"{$this->ticket->name}\"" . ($this->assignedBy ? " by {$assignedByName}" : ''))
            ->icon('heroicon-o-user-plus')
            ->iconColor('primary')
            ->actions([
                Action::make('view')
                    ->label('View Ticket')
                    ->url(route('filament.admin.resources.tickets.view', [
                        'tenant' => $this->ticket->project?->team?->slug ?? 'default',
                        'record' => $this->ticket->id,
                    ]))
                    ->markAsRead(),
            ])
            ->getDatabaseMessage();
    }
}
