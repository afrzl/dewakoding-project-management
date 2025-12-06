<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\User;
use App\Notifications\CommentAddedNotification;
use App\Notifications\CommentUpdatedNotification;
use App\Notifications\ProjectAssignedNotification;
use App\Notifications\ProjectRemovedNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    public function notifyCommentAdded(TicketComment $comment): void
    {
        // Eager load relationships to ensure data is available
        $comment->load(['ticket.project.team', 'user']);
        
        $ticket = $comment->ticket;
        $commenter = $comment->user;
        
        if (!$ticket || !$commenter) {
            Log::warning('Cannot send comment notification: missing ticket or commenter', [
                'comment_id' => $comment->id,
                'has_ticket' => (bool) $ticket,
                'has_commenter' => (bool) $commenter,
            ]);
            return;
        }
        
        $usersToNotify = $this->getUsersToNotifyForComment($ticket, $commenter);
        
        foreach ($usersToNotify as $user) {
            try {
                $user->notify(new CommentAddedNotification($comment, $ticket, $commenter));
            } catch (\Exception $e) {
                Log::error('Failed to send comment notification: ' . $e->getMessage(), [
                    'user_id' => $user->id,
                    'ticket_id' => $ticket->id,
                ]);
            }
        }
    }

    public function notifyCommentUpdated(TicketComment $comment): void
    {
        // Eager load relationships to ensure data is available
        $comment->load(['ticket.project.team', 'user']);
        
        $ticket = $comment->ticket;
        $commenter = $comment->user;
        
        $usersToNotify = $this->getUsersToNotifyForComment($ticket, $commenter);
        
        foreach ($usersToNotify as $user) {
            try {
                $user->notify(new CommentUpdatedNotification($comment, $ticket, $commenter));
            } catch (\Exception $e) {
                Log::error('Failed to send comment update notification: ' . $e->getMessage(), [
                    'user_id' => $user->id,
                    'ticket_id' => $ticket->id,
                ]);
            }
        }
    }

    private function getUsersToNotifyForComment(Ticket $ticket, User $commenter): Collection
    {
        $usersToNotify = collect();
        
        if ($ticket->creator && $ticket->creator->id !== $commenter->id) {
            $usersToNotify->push($ticket->creator);
        }
        
        $assignedUsers = $ticket->assignees()->where('users.id', '!=', $commenter->id)->get();
        $usersToNotify = $usersToNotify->merge($assignedUsers);
        
        $commenters = $ticket->comments()
            ->with('user')
            ->where('user_id', '!=', $commenter->id)
            ->get()
            ->pluck('user')
            ->unique('id');
        $usersToNotify = $usersToNotify->merge($commenters);
        
        return $usersToNotify->unique('id');
    }

    public function markAsRead(string $notificationId, int $userId): bool
    {
        $user = User::find($userId);
        if (!$user) {
            return false;
        }

        $notification = $user->notifications()->find($notificationId);
        
        if ($notification) {
            $notification->markAsRead();
            return true;
        }
        
        return false;
    }

    public function markAllAsRead(int $userId): void
    {
        $user = User::find($userId);
        if ($user) {
            $user->unreadNotifications->markAsRead();
        }
    }

    public function notifyProjectAssignment(Project $project, User $assignedUser, User $assignedBy): void
    {
        try {
            $assignedUser->notify(new ProjectAssignedNotification($project, $assignedBy));
        } catch (\Exception $e) {
            Log::error('Failed to send project assignment notification: ' . $e->getMessage(), [
                'project_id' => $project->id,
                'user_id' => $assignedUser->id,
                'assigned_by_id' => $assignedBy->id,
            ]);
        }
    }

    public function notifyProjectRemoval(Project $project, User $removedUser, User $removedBy): void
    {
        try {
            $removedUser->notify(new ProjectRemovedNotification($project, $removedBy));
        } catch (\Exception $e) {
            Log::error('Failed to send project removal notification: ' . $e->getMessage(), [
                'project_id' => $project->id,
                'user_id' => $removedUser->id,
            ]);
        }
    }
}
