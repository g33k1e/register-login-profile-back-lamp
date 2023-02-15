<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return User::all();
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

        $data = $request->toArray($request->getContent(true));
        Log::channel('stderr')->info("form info:",$data);
        User::create($data);

        return response()->json(['info'=>true],200);
    }

     /**
     * Check a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        //
        $data = $request->toArray($request->getContent(true));

        Log::channel('stderr')->info("form info:",$data);
        $user =User::where('username',$data['eou'])->orWhere('email',$data['eou'])->where('password',$data['password'])->firstOrFail();

        Log::channel('stderr')->info("retrieved info:",[$user]);
        return response()->json(['id'=>$user->id,'name'=>$user->name,'username'=>$user->username,'user_image'=>$user->user_image,'email'=>$user->email],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function imageUpload(Request $request)
    {
        //
        $fileName = $request->file('picture')->getClientOriginalName();
        Log::channel('stderr')->info("id:",[$request->id]);
        Log::channel('stderr')->info("file name:",[$fileName]);
        try{
            Storage::disk('custom')->put("/".$request->user_id."/".$fileName,file_get_contents($request->file('picture')));

            $result = User::where('id',$request->user_id)->update(['user_image'=>$fileName]);
            Log::channel('stderr')->info("update res:",[$result]);


        }catch(Exception $e){
            return response()->json($e);
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
        //
    }
}
