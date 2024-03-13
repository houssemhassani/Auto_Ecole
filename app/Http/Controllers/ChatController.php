<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    public function index()
    {
        // Récupérer les conversations de l'utilisateur actuellement authentifié
        $conversations = Message::select('recipient_id', 'sender_id')
            ->where('sender_id', auth()->id())
            ->orWhere('recipient_id', auth()->id())
            ->distinct()
            ->get();

        return response()->json(['conversations' => $conversations]);
    }

    public function conversation($userId)
    {
        // Récupérer les messages échangés entre l'utilisateur authentifié et l'utilisateur spécifié
        $messages = Message::where(function ($query) use ($userId) {
            $query->where('sender_id', auth()->id())
                ->where('recipient_id', $userId);
        })->orWhere(function ($query) use ($userId) {
            $query->where('sender_id', $userId)
                ->where('recipient_id', auth()->id());
        })->orderBy('created_at', 'asc')->get();

        // Marquer tous les messages comme lus
        Message::where('recipient_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['messages' => $messages]);
    }

    public function send(Request $request)
    {
        // Valider les données d'entrée
        $validator = Validator::make($request->all(), [
            'recipient_id' => 'required|exists:users,id',
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Envoyer le message
        $message = Message::create([
            'sender_id' => auth()->id(),
            'recipient_id' => $request->recipient_id,
            'content' => $request->content,
        ]);

        return response()->json(['message' => $message]);
    }

    public function delete($id)
    {
        $message = Message::findOrFail($id);

        // Vérifier si l'utilisateur est l'expéditeur ou le destinataire du message
        if ($message->sender_id == auth()->id()) {
            $message->delete();
            return response()->json(['message' => 'Message deleted']);
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }
}
