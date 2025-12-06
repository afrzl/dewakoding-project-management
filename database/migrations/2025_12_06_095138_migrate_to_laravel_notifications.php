<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Backup existing data
        $existingNotifications = DB::table('notifications')->get();
        
        // Drop old table
        Schema::dropIfExists('notifications');
        
        // Create Laravel's standard notifications table
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
        
        // Migrate old data to new format
        foreach ($existingNotifications as $oldNotification) {
            $data = json_decode($oldNotification->data ?? '{}', true) ?? [];
            $data['title'] = $oldNotification->title;
            $data['message'] = $oldNotification->message;
            
            // Map old type to new notification class
            $typeMap = [
                'comment_added' => 'App\\Notifications\\CommentAddedNotification',
                'comment_updated' => 'App\\Notifications\\CommentUpdatedNotification',
                'project_assigned' => 'App\\Notifications\\ProjectAssignedNotification',
                'project_removed' => 'App\\Notifications\\ProjectRemovedNotification',
            ];
            
            $notificationType = $typeMap[$oldNotification->type] ?? 'App\\Notifications\\CommentAddedNotification';
            
            DB::table('notifications')->insert([
                'id' => Str::uuid()->toString(),
                'type' => $notificationType,
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id' => $oldNotification->user_id,
                'data' => json_encode($data),
                'read_at' => $oldNotification->read_at,
                'created_at' => $oldNotification->created_at,
                'updated_at' => $oldNotification->updated_at,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Backup existing data
        $existingNotifications = DB::table('notifications')->get();
        
        // Drop Laravel's table
        Schema::dropIfExists('notifications');
        
        // Recreate old custom table
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'read_at']);
        });
        
        // Migrate data back to old format
        foreach ($existingNotifications as $notification) {
            $data = json_decode($notification->data, true) ?? [];
            
            // Map notification class back to old type
            $reverseTypeMap = [
                'App\\Notifications\\CommentAddedNotification' => 'comment_added',
                'App\\Notifications\\CommentUpdatedNotification' => 'comment_updated',
                'App\\Notifications\\ProjectAssignedNotification' => 'project_assigned',
                'App\\Notifications\\ProjectRemovedNotification' => 'project_removed',
            ];
            
            $type = $reverseTypeMap[$notification->type] ?? 'comment_added';
            $title = $data['title'] ?? 'Notification';
            $message = $data['message'] ?? '';
            unset($data['title'], $data['message']);
            
            DB::table('notifications')->insert([
                'user_id' => $notification->notifiable_id,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'data' => json_encode($data),
                'read_at' => $notification->read_at,
                'created_at' => $notification->created_at,
                'updated_at' => $notification->updated_at,
            ]);
        }
    }
};
