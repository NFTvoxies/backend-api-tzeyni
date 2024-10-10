<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ReservationRequest;

class ReservationController extends Controller
{
    // Function create reservation
    public function store(ReservationRequest $request,string $id) {
        $user = Auth::guard('user')->user();
        $data = $request->post();
        $service = Service::where('id',$id)->first();
        if($service->is_promo == true){
            $price = $service->promotion_price;
        }else {
            $price = $service->price;
        }

        Reservation::create([
            'service_id' => $id,
            'user_id' => $user->id,
            'price' => $price,
            'time'=> $data['time'],
            'livrable_addresse' => $data['livrable_addresse'],
        ]);

        return response()->json(['status'=>true,'message'=> 'Votre réservation pour le service a été effectuée.'],200);
    }

    // Function list of reservation for user
    public function index( ){
        $id = Auth::guard('user')->id();
        $reservations = Reservation::where('user_id',$id)->with(['service' => function ($query) {
            $query->select('id', 'name')->with(['images'=> function ($query){
                $query->select('*')->first();
            }]);
        }])->get();
        return response()->json(['data'=>$reservations]);
    }

    // Function list of reservations for professional
    public function list() {
        $reservations = Reservation::with(['service' => function($query){
            $professionalId = Auth::guard('professional')->id();
            $query->where('professional_id',$professionalId)->select('id','name')
            ->with(['images' => function($query){
                $query->select('*')->first();
            }]);
        }])
        ->with(['user' => function($query){
            $query->select('id','full_name','phone');
        }])
        ->get(['id','time','livrable_addresse','price','status','user_id','service_id']);
        if($reservations == null) {
            return response()->json(['status' => true, 'message' => 'Aucune réservation trouvée.'], 200);
        }
        return response()->json(['status' => true, 'data' => $reservations],200);
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
        $reservation = Reservation::where('id',$data['id'])->first();
        if(!$reservation){
            return response()->json(['status' => false, 'message' => 'Aucune réservation n\'a été trouvée.'], 200);
        }
        if( $reservation->status == 'livré'){
            return response()->json(['status' => false, 'message' => 'Votre réservation a été soumise, et aucune modification ne peut être apportée  '], 200);
        }
        $reservation->status = $data['status'];
        $reservation->save();
        return response()->json(['status' => true, 'message' => 'Le statut a été modifié.'], 200);
    }
}
