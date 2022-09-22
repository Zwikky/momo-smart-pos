<?php

namespace App\Http\Controllers;

use App\Models\Payer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PayersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payers = Payer::all();

        return response()->json([
            "payers" => $payers
        ], 200);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'cellphone' => 'required',
            'momo_balance' => 'required',
            
        ]);
        $uuid = Str::uuid()->toString();

        $payer = Payer::create([
            'name' => $request->name,
            'cellphone' => $request->cellphone,
            'uuid' => $uuid,
            'momo_balance' => $request->momo_balance,
            'verified' => $request->verified
        ]);


        // dd($uuid);

        return response()->json([
             'data' => $payer,
             'message' => 'Payer Added Successfully'
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payer  $payer
     * @return \Illuminate\Http\Response
     */
    public function show(Request $payer)
    {
        $get_payer = Payer::find($payer);

        dd($payer);

        if(!$get_payer){
            return response()->json([
                'message' => 'No Data'
            ], 405); 
        } else {
            return response()->json([
            'data' => $get_payer,
            'message' => 'success'
        ], 200);
        }

        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payer  $payer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payer $payer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payer  $payer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payer $payer)
    {
        //
    }
}