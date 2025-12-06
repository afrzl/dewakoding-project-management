<?php

namespace App\Notifications;

use App\Models\Project;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProjectAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Project $project,
        public User $assignedBy
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        return FilamentNotification::make()
            ->title('Added to Project')
            ->body("You have been added to project \"{$this->project->name}\" by {$this->assignedBy->name}")
            ->icon('heroicon-o-folder-plus')
            ->iconColor('success')
            ->actions([
                Action::make('view')
                    ->label('View Project')
                    ->url(route('filament.admin.resources.projects.view', [
                        'tenant' => $this->project->team?->slug ?? 'default',
                        'record' => $this->project->id,
                    ]))
                    ->markAsRead(),
            ])
            ->getDatabaseMessage();
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("You've been added to project: {$this->project->name}")
            ->greeting("Hello {$notifiable->name}!")
            ->line("You have been added to the project **{$this->project->name}** by {$this->assignedBy->name}.")
            ->action('View Project', route('filament.admin.resources.projects.view', [
                'tenant' => $this->project->team?->slug ?? 'default',
                'record' => $this->project->id,
            ]))
            ->line('Thank you for using AturKerja!');
    }
}
