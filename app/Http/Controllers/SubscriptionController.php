<?php


namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SubscriptionConfirmation;

class SubscriptionController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $subscriber = Subscriber::where('email', $request->email)->first();

        if ($subscriber) {
            // L'utilisateur est déjà inscrit, vous pouvez envoyer un message ou rediriger avec un message d'erreur
            return response()->json(['message' => 'Vous êtes déjà inscrit!']);
        }

        // Si l'utilisateur n'est pas déjà inscrit, créez un nouvel enregistrement
        $subscriber = Subscriber::create(['email' => $request->email]);

        // Envoyer un email de confirmation
        Mail::to($subscriber->email)->send(new SubscriptionConfirmation($subscriber));

        return response()->json(['message' => 'Vous êtes bien inscrit!']);
    }
}
