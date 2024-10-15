<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Service;
use App\Models\Professional;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function landing() {
        $professionals = Professional::where('is_verify', true)->orderBy('created_at', 'asc')->limit(3)
        ->with(['services' => function ($query) 
            {
                $query->limit(3)->select('id','name','professional_id');
            }
        ])->get(['id','full_name', 'profile', 'experience', 'city']);  

        return response()->json(['data' => $professionals],200);
    }

    public function search(Request $request) {
        $validated = $request->validate([
            'service' => 'required|string',
            'addresse' => 'required|string'
        ],
        [
            'service.required' => 'Le champ service est obligatoire.',
            'service.string' => 'Le champ service doit être une chaîne de caractères.',
            'addresse.required' => 'Le champ adresse est obligatoire.',
            'addresse.string' => 'Le champ adresse doit être une chaîne de caractères.',
        ]);
        $services = Service::where('name', 'like', '%' . $validated["service"] . '%')
        ->whereHas('professional', function($query) use ($validated) {
            $query->where('city', 'like', '%' . $validated["addresse"] . '%')
                ->orWhere('addresse', 'like', '%' . $validated["addresse"] . '%');
        })
        ->with(['professional' => function($query) {
            $query->select('id', 'full_name');
        }])
        ->withCount('comments')
        ->paginate(9);
        
        if($services->isEmpty()) {
            return response()->json(['status' => false,  'message' => 'Malheureusement, nous n\'avons trouvé aucun service correspondant à votre demande.'],404);
        }
        
        return response()->json(['data' => $services],200);

    }

    public function services() {
        $services = Service::with(['professional' => function ($query) {
            $query->select('id', 'full_name'); 
        }])
        ->with(['images' => function($query){
            $query->first('id','path','imageable_id');
        }])
        ->withCount('comments')->paginate(9);
        return response()->json(['data' => $services],200);
    }

    public function products() {
        $products = Product::with(['professional' => function ($query) {
            $query->select('id', 'full_name'); 
        }])
        ->with(['images' => function($query){
            $query->first('id','path','imageable_id');
        }])
        ->withCount('comments')->paginate(9);
        return response()->json(['data' => $products],200);
    }

    public function product($id) {
        $product = Product::where('id',$id)->with(['professional' => function ($query) {
            $query->select('id', 'full_name'); 
        }])
        ->with('images')->with('comments')->get();
        if( $product->isEmpty() ) {
            return response()->json(['message' => 'Désolé, nous n\'avons trouvé aucun produit correspondant à votre recherche. Veuillez essayer avec d\'autres critères.'], 404);
        }
        return response()->json(['data' => $product],200);
    }

    public function service($id) {
        $service = Service::where('id',$id)->with(['professional' => function ($query) {
            $query->select('id', 'full_name'); 
        }])
        ->with('images')->with('comments')->get();

        if( $service->isEmpty() ) {
            return response()->json(['message' => 'Désolé, nous n\'avons trouvé aucun service correspondant à votre recherche. Veuillez essayer avec d\'autres critères.'], 404);
        }

        return response()->json(['data' => $service],200);
    }
}
