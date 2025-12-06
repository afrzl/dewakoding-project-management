<?php

namespace App\Filament\Resources\Tickets\Pages;

use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use App\Filament\Resources\Tickets\TicketResource;
use App\Models\User;
use App\Notifications\TicketAssignedNotification;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditTicket extends EditRecord
{
    protected static string $resource = TicketResource::class;
    
    protected array $previousAssigneeIds = [];

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function beforeSave(): void
    {
        // Store current assignees before save
        $this->previousAssigneeIds = $this->record->assignees()->pluck('users.id')->toArray();
    }

    protected function afterSave(): void
    {
        // Get new assignees after Filament's relationship handling
        $newAssigneeIds = $this->record->assignees()->pluck('users.id')->toArray();
        
        // Find newly added assignees
        $addedAssigneeIds = array_diff($newAssigneeIds, $this->previousAssigneeIds);
        
        // Notify newly assigned users
        $assignedBy = auth()->user();
        foreach ($addedAssigneeIds as $userId) {
            $user = User::find($userId);
            if ($user && $user->id !== $assignedBy?->id) {
                $user->notify(new TicketAssignedNotification($this->record, $assignedBy));
            }
        }
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Ticket updated')
            ->body('The ticket has been updated successfully.');
    }
}