<?php

namespace App\Http\Controllers\Professional;

use App\Models\Image;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Professional\Product\StoreProduct;
use App\Http\Requests\Professional\Product\UpdateProduct;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $professionalId = Auth::guard('professional')->id();
        $products = Product::where('professional_id',$professionalId)->paginate(10);
        if($products->isEmpty()) {
            return response()->json(['status' => false,'message' => 'Aucun produit trouvé'],200);
        }
        return response()->json(['status' => true,'message' => 'Voici La liste des produits','data' => $products],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProduct $request)
    {
        $professionalId = Auth::guard('professional')->id(); 
        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'brand' => $request->brand,
            'price' => $request->price,
            'is_visible' => $request->is_visible,
            'is_featured' => $request->is_featured,
            'is_promo' => $request->is_promo,
            'promotion_price' => $request->promotion_price,
            'professional_id' => $professionalId
        ]);
        if($request->hasFile('images')){
            foreach($request->file('images') as $image){
                $path = $image->store('/assets/images','public');
                Image::create([
                    'path' => $path,
                    'imageable_id' => $product->id,
                    'imageable_type' => 'products',
                ]);
            }
        }
        return response()->json(['status' => true, 'message' => 'Produit crée avec succès'],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $professionalId = Auth::guard('professional')->id(); 
        $product = Product::where('id',$id)->where('professional_id',$professionalId)->with('comments')->with('images')->first();
        if ( $product == null){
            return response()->json(['status' => false,'message' => 'N\'existe pas ce produit'],200);
        }
        return response()->json(['status' => true,'message' => 'Voici Votre produit','data' => $product],200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $professionalId = Auth::guard('professional')->id(); 
        $product = Product::where('id',$id)->where('professional_id',$professionalId)->with('images')->first();
        if ( $product == null){
            return response()->json(['status' => false,'message' => 'N\'existe pas ce produit'],200);
        }
        return response()->json(['status' => true,'message' => 'Voici Votre produit','data' => $product],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProduct $request, string $id)
    {
        $professionalId = Auth::guard('professional')->id(); 
        $product = Product::where('id',$id)->where('professional_id',$professionalId)->with('images')->first();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->brand = $request->brand;
        $product->price = $request->price;
        $product->is_visible = $request->is_visible;
        $product->is_featured = $request->is_featured;
        $product->is_promo = $request->is_promo;
        $product->promotion_price  = $request->promotion_price;
        $product->professional_id  = $professionalId;
        $product->save();
        if($request->hasFile('images')){
            foreach($request->file('images') as $image){
                $path = $image->store('/assets/images','public');
                Image::create([
                    'path' => $path,
                    'imageable_id' => $product->id,
                    'imageable_type' => 'products',
                ]);
            }
        }
        return response()->json(['status' => true, 'message' => 'Les modifications sont enregistres', 'data' => $product],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $professionalId = Auth::guard('professional')->id();
        $product = Product::where('professional_id',$professionalId)->where('id',$id)->first();
        if($product == null){
            return response()->json(['status' => false,'message' => 'Aucun produit trouvé'],200);
        }
         // Retrieve all the images associated with this service
        $images = Image::where('imageable_id', $product->id)->get();

        // Loop through each image and delete it from the storage
        foreach ($images as $image) {
            // Check if the file exists before attempting to delete it
            if (Storage::disk('public')->exists($image->path)) {
                Storage::disk('public')->delete($image->path);
            }
        }
        $product->delete();
        return response()->json(['status' => true,'message' => 'Votre produit est supprimé '],200);
    }

    public function destroyImage(string $id){
        $image = Image::where('id',$id)->first();
        if($image == null){
            return response()->json(['status' => false,'message' => 'Aucune image trouvée'],200);
        }
        // Check if the file exists before attempting to delete it
        if (Storage::disk('public')->exists($image->path)) {
            Storage::disk('public')->delete($image->path);
        }
        $image->delete();
        return response()->json(['status' => true, 'message' => 'L\'image est Supprimée'],200);
    }
}
