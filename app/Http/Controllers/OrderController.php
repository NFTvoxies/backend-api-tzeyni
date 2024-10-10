<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Function create order
    public function store(OrderRequest $request,string $id) {
        $userId = Auth::guard('user')->id();
        $data = $request->post();
        $product = Product::findOrFail($id);
        $price = $product->is_promo ? $product->promotion_price * $data['quantity'] : $product->price * $data['quantity'];
        Order::create([
            'product_id' => $product->id,
            'user_id' => $userId,
            'price' => $price,
            'quantity' => $data['quantity'],
            'livrable_addresse' => $data['livrable_addresse'],
        ]);

        return response()->json(['status'=>true,'message'=> 'Votre commande pour le produit a été effectuée.'],200);
    }

    // Function list of orders for user
    public function index( ){
        $id = Auth::guard('user')->id();
        $orders = Order::where('user_id',$id)->with(['product' => function ($query) {
            $query->select('id', 'name')->with(['images'=> function ($query){
                $query->select('*')->first();
            }]);
        }])->get();
        return response()->json(['data'=>$orders]);
    }

    // Function list of orders for professional
    public function list() {
        $orders = Order::with(['product' => function($query){
            $professionalId = Auth::guard('professional')->id();
            $query->where('professional_id',$professionalId)->select('id','name')
            ->with(['images' => function($query){
                $query->select('*')->first();
            }]);
        }])
        ->with(['user' => function($query){
            $query->select('id','full_name','phone');
        }])
        ->get(['id','livrable_addresse','quantity','price','status','user_id','product_id']);
        if($orders->isEmpty()) {
            return response()->json(['status' => true, 'message' => 'La liste des commandes est vide. '], 200);
        }
        return response()->json(['status' => true, 'data' => $orders],200);
    }

    // Function changing status for professional
    public function change(Request $request){
        $data = $request->validate([
            'status' => 'required|string',
            'id' => 'required|string',
        ],
        [
            'status.required' => 'Le champ "statut" est obligatoire.',
            'status.string' => 'Le champ "statut" doit être une chaîne de caractères.',
            
            'id.required' => 'Le champ "id" est obligatoire.',
            'id.string' => 'Le champ "id" doit être une chaîne de caractères.',
        ]);
        if($data['status'] == 'livré'){
            return response()->json(['status' => false, 'message' => 'Votre commande a été soumise, et aucune modification ne peut être apportée  '], 200);
        }
        $order = Order::where('id',$data['id'])->first();
        if(!$order){
            return response()->json(['status' => false, 'message' => 'Aucune commande n\'a été trouvée.'], 200);
        }
        $order->status = $data['status'];
        $order->save();
        return response()->json(['status' => true, 'message' => 'Le statut a été modifié.'], 200);
    }
}
