<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Order;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    // Function comment to a service
    public function add_service(Request $request, string $id) {
        $data = $request->validate([
            'body' => 'required|string',
        ],[
            'body.required' => 'Le champ contenu est obligatoire.',
            'body.string' => 'Le champ contenu doit être une chaîne de caractères.',
        ]);
        if( !Auth::guard('user')->check()){
            return response()->json(['status' => false, 'message' => 'Veuillez vous connecter pour laisser un commentaire.'],200);
        }
        $userId = Auth::guard('user')->id();
        $reservation = Reservation::where('user_id',$userId)->where('service_id',$id)->first();
        if(!$reservation) {
            return response()->json(['status' => false, 'message' => 'Vous devez réserver ce service avant de pouvoir commenter.'],200);
        }
        if ( strcasecmp(trim($reservation->status), "livré") !== 0 ) {
            return response()->json(['status' => false, 'message' => 'Vous devez attendre la livraison avant de pouvoir commenter.'],200);
        }
        Comment::create([
            'body' => $data['body'],
            'user_id' => $userId,
            'commenteable_id' => $id,
            'commenteable_type' => 'services'
        ]);
        return response()->json(['status' => true, 'message' => 'Merci pour votre commentaire, il a été enregistré avec succès.'],200);
    }

    // Function comment to product
    public function add_product(Request $request, string $id) {
        $data = $request->validate([
            'body' => 'required|string',
        ],[
            'body.required' => 'Le champ contenu est obligatoire.',
            'body.string' => 'Le champ contenu doit être une chaîne de caractères.',
        ]);
        if( !Auth::guard('user')->check()){
            return response()->json(['status' => false, 'message' => 'Veuillez vous connecter pour laisser un commentaire.'],200);
        }
        $userId = Auth::guard('user')->id();
        $order = Order::where('user_id',$userId)->where('product_id',$id)->first();
        if(!$order){
            return response()->json(['status' => false, 'message' => 'Vous devez commander ce produit avant de pouvoir commenter.'],200);
        }
        if ( strcasecmp(trim($order->status), "livré") !== 0 ) {
            return response()->json(['status' => false, 'message' => 'Vous devez attendre la livraison avant de pouvoir commenter.'],200);
        }
        Comment::create([
            'body' => $data['body'],
            'user_id' => $userId,
            'commenteable_id' => $id,
            'commenteable_type' => 'products'
        ]);
        return response()->json(['status' => true, 'message' => 'Merci pour votre commentaire, il a été enregistré avec succès.'],200);
    }

}
