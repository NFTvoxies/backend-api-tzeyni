<?php

namespace App\Http\Controllers\Professional;

use App\Models\Image;
use App\Models\Service;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Professional\Service\StoreRequest;
use App\Http\Requests\Professional\Service\UpdateService;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $professional = Auth::guard('professional')->user()->id;
        $services = Service::where('professional_id',$professional)->paginate(10);
        if($services->isEmpty()) {
            return response()->json(['status' => true,'message' => 'Ajouter Des Services'],200);
        }
        return response()->json(['status' => true,'message' => 'La liste des services', 'data' => $services],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $professional = Auth::guard('professional')->user()->id;
        $service = new Service();
        $service->name = $request->name;
        $service->description = $request->description;
        $service->time = $request->time;
        $service->price = $request->price;
        $service->is_visible = $request->is_visible;
        $service->is_promo = $request->is_promo;
        $service->promotion_price = $request->promotion_price;
        $service->professional_id = $professional;
        $service->save();
        if($request->hasFile('images')){
            foreach($request->file('images') as $image){
                $path = $image->store('/assets/images','public');
                Image::create([
                    'path' => $path,
                    'imageable_id' => $service->id,
                    'imageable_type' => 'services',
                ]);
            }
        }
        return response()->json(['status' => true, 'message' => 'Service crée avec succès'],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $professionalId = Auth::guard('professional')->user()->id;
        $service = Service::where('professional_id',$professionalId)->where('id',$id)->with('images')->with('comments')->first();
        if($service == null) {
            return response()->json(['status' => false,'message' => 'Aucun service trouve'],200);
        }
        return response()->json(['status' => true,'message' => 'Voila votre service','data' => $service],200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $professionalId = Auth::guard('professional')->user()->id;
        $service = Service::where('professional_id',$professionalId)->where('id',$id)->with('images')->first();
        if($service == null) {
            return response()->json(['status' => false,'message' => 'Aucun service trouve'],200);
        }
        return response()->json(['status' => true,'message' => 'Voila votre service','data' => $service]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateService $request, string $id)
    {
        $professionalId = Auth::guard('professional')->id(); 
        $service = Service::where('id',$id)->where('professional_id',$professionalId)->with('images')->first();
        $service->name = $request->name;
        $service->description = $request->description;
        $service->price = $request->price;
        $service->is_visible = $request->is_visible;
        $service->is_promo = $request->is_promo;
        $service->promotion_price  = $request->promotion_price;
        $service->professional_id  = $professionalId;
        $service->save();
    
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Save the image to storage and get the path
                $path = $image->store('/assets/images', 'public');
                // Create a new entry in image_services table
                Image::create([
                    'path' => $path,
                    'imageable_id' => $service->id,
                    'imageable_type' => 'services',
                ]);
            }
        }
        return response()->json(['status' => true,'message' => 'Les modifications sont enregistres','data' => $service]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $professionalId = Auth::guard('professional')->id;
        $service = Service::where('professional_id',$professionalId)->where('id',$id)->first();
        if($service==null){
            return response()->json(['status' => false,'message' => 'Aucun service trouve'],200);
        }
         // Retrieve all the images associated with this service
        $images = Image::where('imageable_id', $service->id)->get();

        // Loop through each image and delete it from the storage
        foreach ($images as $image) {
            // Check if the file exists before attempting to delete it
            if (Storage::disk('public')->exists($image->path)) {
                Storage::disk('public')->delete($image->path);
            }
        }
        $service->delete();
        return response()->json(['status' => true,'message' => 'Votre service est supprime'],200);
    }

    public function destroyImage(string $id){
        $image = Image::where('id',$id)->first();
        if($image == null){
            return response()->json(['status' => false,'message' => 'Aucun Image trouve'],200);
        }
        // Check if the file exists before attempting to delete it
        if (Storage::disk('public')->exists($image->path)) {
            Storage::disk('public')->delete($image->path);
        }
        $image->delete();
        return response()->json(['status' => true, 'message' => 'Image est Supprime'],200);
    }
}
