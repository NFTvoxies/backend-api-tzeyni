<?php

namespace App\Http\Controllers\Professional;

use App\Models\Order;
use App\Models\Product;
use App\Models\Service;
use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index() {

        if( !Auth::guard('professional')->check() ){
            return response()->json(['message' => 'Vous devez être connecté en tant que professionnel pour accéder à cette ressource.'],401);
        }

        if (is_null(Auth::guard('professional')->user()->email_verified_at)) {
            return response()->json(['message' => 'Votre email n\'a pas encore été vérifié. Veuillez vérifier votre email pour accéder à cette ressource.'], 401);
        }
        $professionalID = Auth::guard('professional')->id();

        $services = Service::where('professional_id',$professionalID)->where('is_visible',true)
        ->with(['images' => function($query){
            $query->first();
        }])->paginate(10);

        $products = Product::where('professional_id',$professionalID)->where('is_visible',true)
        ->with(['images' => function($query){
            $query->first();
        }])->paginate(10);

        $serviceIDs = Service::where('professional_id',$professionalID)->where('is_visible',true)->pluck('id');
        $productIDs = Product::where('professional_id',$professionalID)->where('is_visible',true)->pluck('id');

        $servicesCount = Service::where('professional_id',$professionalID)->where('is_visible',true)->count();
        $productsCount =  Product::where('professional_id',$professionalID)->where('is_visible',true)->count();
        $ordersCount = Order::whereIn('product_id', $productIDs)->count();
        $reservationsCount = Order::whereIn('product_id', $serviceIDs)->count();

        $orders = Order::whereIn('product_id', $productIDs)
            ->with(['product' => function($query) {
                $query->with(['images' => function($query) {
                    $query->first(); 
                }]);
            }])->paginate(10);

        $reservations = Reservation::whereIn('service_id', $serviceIDs)
            ->with(['service' => function($query) {
                $query->with(['images' => function($query) {
                    $query->first(); 
                }]);
            }])->paginate(10);

            return response()->json([
                'servicesCount' => $servicesCount,
                'productsCount' => $productsCount,
                'ordersCount' => $ordersCount,
                'reservationsCount' => $reservationsCount,
                'services' => $services,
                'products' => $products,
                'orders' => $orders,
                'reservations' => $reservations

            ]);
        }
        
}
