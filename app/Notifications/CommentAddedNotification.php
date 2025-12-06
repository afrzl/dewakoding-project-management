<?php

namespace App\Notifications;

use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CommentAddedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public TicketComment $comment,
        public Ticket $ticket,
        public User $commenter
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return FilamentNotification::make()
            ->title('New Comment on Ticket')
            ->body("{$this->commenter->name} added a comment on \"{$this->ticket->name}\"")
            ->icon('heroicon-o-chat-bubble-left-ellipsis')
            ->iconColor('info')
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
