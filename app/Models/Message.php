<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Message extends Model
{
    protected $fillable = [
        'sender_id',
        'recipient_id',
        'content',
        'read_at',
    ];

    protected $dates = [
        'read_at',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function markAsRead()
    {
        $this->read_at = now();
        $this->save();
    }

    public static function conversation($userId1, $userId2)
    {
        return self::where(function ($query) use ($userId1, $userId2) {
            $query->where('sender_id', $userId1)
                ->where('recipient_id', $userId2);
        })->orWhere(function ($query) use ($userId1, $userId2) {
            $query->where('sender_id', $userId2)
                ->where('recipient_id', $userId1);
        })->orderBy('created_at', 'asc')->get();
    }

    public static function unreadMessages()
    {
        return self::where('recipient_id', Auth::id())
            ->whereNull('read_at')
            ->get();
    }

    public static function markAllAsRead()
    {
        self::where('recipient_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public static function send($senderId, $recipientId, $content)
    {
        return self::create([
            'sender_id' => $senderId,
            'recipient_id' => $recipientId,
            'content' => $content,
        ]);
    }

    public function deleteMessage()
    {
        $this->delete();
    }
}
