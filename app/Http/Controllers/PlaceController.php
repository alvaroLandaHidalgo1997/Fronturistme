<?php

namespace App\Http\Controllers;

use App\Place;
use Illuminate\Http\Request;
use \Firebase\JWT\JWT;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;

class PlaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $key = $this->key;
      $headers = getallheaders();
      $token = $headers['Authorization'];
      $user = JWT::decode($token, $key, array('HS256'));
      $idUser = $user->user->id;

      $userPlaces = Place::where('user_id', $idUser)->get();

      $places = [];

        foreach ($userPlaces as $place){
          $places[] = $place;
        }
      
        return response()->json([
        'places'=> $places,
        ]);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
      $key = $this->key;
      $headers = getallheaders();
      $token = $headers['Authorization'];
      $user = JWT::decode($token, $key, array('HS256'));

       
      $place = new Place();
      $place->title = $request->title;
      $place->description = $request->description;
      $place->startDate = $request->startDate;
      $place->endDate = $request->endDate;
      $place->coordX = $request->coordX;
      $place->coordY = $request->coordY;
      $place->user_id = $user->user->id;

        if ($request->title == null or $request->description == null or $request->startDate == null or $request->endDate == null or $request->coordX == null or $request->coordY == null)
      {

        return response(204);

       }

       $place->save();
       return response(200);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function show(Place $place)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function edit(Place $place)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Place $place)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function destroy(Place $place)
    {
        $headers = getallheaders();
        $token = $headers['Authorization'];
        $key = $this->key;
        $userData = JWT::decode($token, $key, array('HS256'));
        $id_user = User::where('email',$userData->email)->first()->id;
        $id_place = $_POST['id'];
        $id = $id_place;
        $place = Place::find($id);
        if (is_null($place)) {
          return $this->error(400,'el lugar no existe');
          }else{
            $place_name = Place::where('id',$id_place)->first()->name;
            Place::destroy($id);
            return $this->success('lugar borrado', $place_name);
          }
    }
    public function updatePlace()
    {
        $headers = getallheaders();
        $token = headers['Authorization'];
        $key = $this->key;
        $userData = JWT::decode($token,$key, array('HS256'));
        $id_place = $_POST['id'];
        $newName = $_POST['newname'];
        $newDescription = $_POST['description'];
        $coordX = $_POST['newCoordX'];
        $coordY = $_POST['newCoordY'];
        $startdate = $_POST['newStartDate'];
        $enddate = $_POST['newEndDate'];
        $place = Place::find($id_place);

        if (is_null($place)) {
          return $this->error(400,'el lugar no existe');

        }if(!empty($_POST['description'])){
          $place->description = $newDescription;
        }
        if(!empty($_POST['newname'])){
          $place->name = $newName;
        }
        if(!empty($_POST['newCoordX'])){
          $place->coordenadaX = $coordX;
        }
        if(!empty($_POST['newCoordY'])){
          $place->coordenadaY = $coordY;
        }
        if(!empty($_POST['startDate'])){
          $place->startDate = $startdate;
        }
        if(!empty($_POST['endDate'])){
          $place->endDate = $enddate;
        }
        $place->save();
        return response(200, "sitio modificado");
    }
}
