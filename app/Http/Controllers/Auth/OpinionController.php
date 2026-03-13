<?php

namespace BolsaTrabajo\Http\Controllers\Auth;

use BolsaTrabajo\Opinion;
use Illuminate\Http\Request;
use BolsaTrabajo\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OpinionController extends Controller
{


    public function index()
    {
        return view('auth.opinion.index'); 
    }


    public function list_all(Request $request)
    {
        $query = Opinion::orderby('id', 'desc');
    
        if ($request->date_from) {
            $query->where('created_at', '>=', $request->date_from);
        }
    
        if ($request->date_to) {
            $query->where('created_at', '<=', $request->date_to);
        }
    
        $events = $query->get(['id', 'opinion', 'rating', 'created_at']);
    
        $events = $events->map(function ($event) {
            return [
                'id' => $event->id,
                'opinion' => $event->opinion,
                'rating' => $event->rating,
                'created_at' => $event->created_at,
            ];
        });
    
        return response()->json($events);
    }
    


}