<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Posto;

class PostiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id_aula)
    {
        $posti = Posto::where('id_aula',$id_aula);
        return response()->json($posti, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $posto = new Posto;

        $posto->numero_posto = $request->numero_posto;

        if($request->disponibilita != null){
            $posto->disponibilita = $request->disponibilita;
        }

        $posto->id_utente = $request->id_utente;
        $posto->id_aula = $request->id_aula;

        $posto->save();

        return response()->json($posto, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id_posto)
    {
        $posto= Posto::find($id_posto);
        if($posto == null){
            return response()->json(["errore"=>"posto non trovato"], 404);
        }

        return response()->json($posto, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $posto = Posto::findOrFail($request->id);

        $posto->numero_posto = $request->numero_posto;
        $posto->id_aula = $request->id_aula;

        $posto->save();

        return response()->json($posto,200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $posto = Posto::findOrFail($id);
        $posto->delete();

        return response()->json($posto,200);
    }
}
