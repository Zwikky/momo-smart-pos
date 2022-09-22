<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cards =    DB::table('cards')
                        ->join('payers', 'cards.holder', '=', 'payers.id')
                        ->select('cards.*', 'payers.cellphone', 'payers.name', 'payers.momo_balance')
                        ->orderByDesc('id')
                        ->get();

        return response()->json([
            "cards" => $cards
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
            'card_number' => 'required|max:12|unique:cards',
            'holder' => 'required',
            'balance' => 'required',
            
        ]);

        $card = Card::create([
            'card_number' => $request->card_number,
            'holder' => $request->holder,
            'balance' => $request->balance
        ]);


        // dd($uuid);

        return response()->json([
             'data' => $card,
             'message' => 'Card Added Successfully'
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function show(Request $card, $id)
    {
        $get_card = Card::find($id);

        // dd($get_card);

        return response()->json([
            "balance" => $get_card->balance
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Card $card)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function destroy(Card $card)
    {
        //
    }
}