<?php

namespace App\Http\Controllers;

use App\Aula;
use Illuminate\Http\Request;

class AuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $aule=Aula::all();
        return ['aule'=>$aule];
        // return view('aule.index',['aule'=>$aule]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('aule.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $json = $request->input('json',null);
        $params = \json_decode($json);
        $params_array = \json_decode($json,true);
        $aula = new Aula();
        $aula->codice= $params_array['codice'];
        $aula->capienza= $params_array['capienza'];
        $aula->tipo= $params_array['tipo'];
        $aula->disponibilita= $params_array['disponibilita'];
        $aula->save();
       // redirect('/aule');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Aula  $aula
     * @return \Illuminate\Http\Response
     */
    public function show($codice)
    {
        $aula= Aula::findOrFail($codice);
        return ['aula'=>$aula];
        //return view('aule.show',['aula'=>$aula]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Aula  $aula
     * @return \Illuminate\Http\Response
     */
    public function edit($codice)
    {
        $aula= Aula::findOrFail($codice);
        return view('aule.edit',compact('aula'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Aula  $aula
     * @return \Illuminate\Http\Response
     */
    public function update($codice)
    {
        $aula= Aula::findOrFail($codice);
        $aula->codice= request('codice');
        $aula->capienza= request('capienza');
        $aula->tipo= request('tipo');
        $aula->disponibilita= request('disponibilita');
        $aula->save();
        redirect('/aule');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Aula  $aula
     * @return \Illuminate\Http\Response
     */
    public function destroy($codice)
    {
        $aula= Aula::findOrFail($codice);
        $aula->delete();
        redirect('/aule');
    }
}
