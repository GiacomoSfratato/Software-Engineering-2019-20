<?php

namespace App\Http\Controllers;

use App\Classroom;
use Illuminate\Http\Request;
use App\Seat;
use App\Http\Resources\SeatCollection;
use App\Http\Resources\Seat as SeatResource;

class SeatsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $seats = new SeatCollection(Classroom::paginate(10));

        if($seats>isEmpty()){
            $seats->additional(['error' => 'No seat was found!']);

            return $seats->response()->setStatusCode(200);
        } else {
            foreach ($seats as $seat) {
                $classroom = Classroom::findOrFail($seat['classroom_id']);
                $seat['class_code'] = $classroom['code'];
            }
            $seats->additional(['error' => null]);
        }

        return $seats->response()->setStatusCode(200);

    }

    //Restituisce tutti i posti all'interno di un'aula
    public function indexClassroom($classroom_id)
    {
        $seats = new SeatCollection(Seat::find($classroom_id));
        if ($seats->resource == null) {
            $seats->additional(['error' => 'Classroom not found!']);
            return $seats->response()->setStatusCode(200);
        }else {
            $seats->additional(['error' => null]);
        }
        return $seats->response()->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $seat = new SeatResource(Classroom::create([
                'seat_number' => $request->seat_number,
                'availability' => $request->availability,
                'classroom_id' => $request->classroom_id
            ]));
            $seat->additional(['error' => null]);
            return $seat->response()->setStatusCode(201);
        } catch(QueryException $ex){
            return response()->json(['SQL Exception'=>$ex->getMessage()], 500);
    }}

    /**
     * Auto generazione dei posti all'interno di una specifica aula
     *
     * @param $classroom
     */
    public function createSeats($classroom){
        for($i=1; $i<= $classroom->capacity ;$i++){
            $seat = new Seat;
            $seat->seat_number = $i;
            $seat->classroom_id = $classroom->id;
            $seat->save();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $seat = new SeatResource(Seat::find($id));
        if($seat->resource == null){
            $seat->additional(['error' => 'Seat not found!']);

            return $seat->response()->setStatusCode(200);
        } else {
            $seat->additional(['error' => null]);
        }
        return $seat->response()->setStatusCode(200);
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
        try {
            $seat = Seat::find($id);

            if($seat == null){
                $seatResource = new SeatResource($seat);
                $seatResource->additional(['error' => 'seat not found!']);
                return $seatResource->response()->setStatusCode(400);
            }

            $seat->classroom_id = $request->classroom_id;
            $seat->seat_number = $request->seat_number;
            $seat->availability = $request->availability;
            $seat->save();

            $seatResource = new SeatResource($seat);
            $seatResource->additional(['error' => null]);

            return $seatResource->response()->setStatusCode(200);
        } catch(QueryException $ex){
            return response()->json(['SQL Exception'=>$ex->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $seat = Seat::find($id);

        if($seat == null){
            $seatResource = new SeatResource($seat);
            $seatResource->additional(['error' => 'Seat not found!']);
            return $seatResource->response()->setStatusCode(400);
        }
        $seat->delete();

        return response()->json([],204);
    }
}
