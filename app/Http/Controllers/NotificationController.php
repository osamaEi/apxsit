<?php

namespace App\Http\Controllers;



use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    /**
     * Display a listing of the notifications.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Paginate notifications
        $notifications = auth()->user()->notifications()->paginate(15);
        
        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * Mark a specific notification as read.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function markAsRead($id)
    {
        $notification = DatabaseNotification::findOrFail($id);
        
        // Check if the notification belongs to the current user
        if ($notification->notifiable_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }
        
        $notification->markAsRead();
        
        return redirect()->back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all notifications as read.
     *
     * @return \Illuminate\Http\Response
     */
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        
        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Delete a specific notification.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $notification = DatabaseNotification::findOrFail($id);
        
        // Check if the notification belongs to the current user
        if ($notification->notifiable_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }
        
        $notification->delete();
        
        return redirect()->back()->with('success', 'Notification deleted successfully.');
    }

    /**
     * Delete all notifications.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroyAll()
    {
        auth()->user()->notifications()->delete();
        
        return redirect()->back()->with('success', 'All notifications deleted successfully.');
    }
}