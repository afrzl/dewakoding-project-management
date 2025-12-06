<?php

namespace App\Filament\Resources\Tickets\Pages;

use App\Filament\Resources\Tickets\TicketResource;
use App\Models\Project;
use App\Models\User;
use App\Notifications\TicketAssignedNotification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ticket;

class CreateTicket extends CreateRecord
{
    protected static string $resource = TicketResource::class;

    protected function fillForm(): void
    {
        $copyFromId = request()->query('copy_from');

        if ($copyFromId) {
            $ticket = Ticket::find($copyFromId);

            if ($ticket) {
                $data = $ticket->toArray();
                unset(
                    $data['id'],
                    $data['uuid'],
                    $data['created_at'],
                    $data['updated_at'],
                    $data['created_by']
                );

                $data['assignees'] = $ticket->assignees()->pluck('users.id')->toArray();
                $this->form->fill($data);

                return;
            }
        }

        parent::fillForm();
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();

        return $data;
    }

    protected function afterCreate(): void
    {
        // Get assignees that were set by Filament's relationship handling
        $assigneeIds = $this->record->assignees()->pluck('users.id')->toArray();
        
        // Notify assigned users (except the creator)
        $assignedBy = auth()->user();
        foreach ($assigneeIds as $userId) {
            $user = User::find($userId);
            if ($user && $user->id !== $assignedBy?->id) {
                $user->notify(new TicketAssignedNotification($this->record, $assignedBy));
            }
        }
    }

    protected function getRedirectUrl(): string
    {
        $referer = request()->header('referer');

        if ($referer && str_contains($referer, 'project-board-page')) {
            return '/admin/project-board-page';
        }

        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Ticket created')
            ->body('The ticket has been created successfully.');
    }
}