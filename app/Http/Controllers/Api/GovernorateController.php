<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Governorate;

class GovernorateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $governorates = Governorate::orderBy('name_ar', 'Asc')->get();
            
            $this->initResponse('suessfulley listed',$governorates, 200, 'data');
        }
        catch(Exception $e){
            $this->initResponse('faild', $e->getMessage(), 400, 'error');
        }
        return response()->json($this->response, $this->code);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $governorate = Governorate::find($id);
        
        $this->initResponse('suessfulley listed',$governorate, 200, 'data');
    }
    catch(Exception $e){
        $this->initResponse('faild', $e->getMessage(), 400, 'error');
    }
    return response()->json($this->response, $this->code);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

        /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showCities($id)
    {
        try{
            $governorate = Governorate::find($id);
        
        $this->initResponse('suessfulley listed',$governorate->cities, 200, 'data');
    }
    catch(Exception $e){
        $this->initResponse('faild', $e->getMessage(), 400, 'error');
    }
    return response()->json($this->response, $this->code);
    }
}
