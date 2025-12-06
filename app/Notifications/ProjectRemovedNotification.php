<?php

namespace App\Notifications;

use App\Models\Project;
use App\Models\User;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProjectRemovedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Project $project,
        public User $removedBy
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return FilamentNotification::make()
            ->title('Removed from Project')
            ->body("You have been removed from project \"{$this->project->name}\" by {$this->removedBy->name}")
            ->icon('heroicon-o-folder-minus')
            ->iconColor('danger')
            ->getDatabaseMessage();
    }
}
