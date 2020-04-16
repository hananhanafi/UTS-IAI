<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MediaSocial;
use JWTAuth;

class MediaSocialController extends Controller
{
    protected $user;

    public function __construct(){
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index(){
        return $this->user->mediasocials()->get([
            'media_social','username'
        ])->toArray();
    }

    public function show($id){
        $mediaSocial = $this->user->mediasocials()->find($id);

        if(!$mediaSocial){
            return response()->json([
                'success' => false,
                'message' => 'Sorry, Media Social with id ' . $id . ' cannot be found'
            ],400);
        }
        return $mediaSocial;
    }

    public function store(Request $request){
        $this->validate($request,[
            'media_social' => 'required',
            'username' => 'required'
        ]);
        
        $mediaSocial =new MediaSocial;
        $mediaSocial->media_social = $request->media_social;
        $mediaSocial->username = $request->username;

        if($this->user->mediasocials()->save($mediaSocial)){
            return response()->json([
                'success' => true,
                'media_social' => $mediaSocial
            ]);

        }else{
            return response()->json([
                'success' => false,
                'message' => 'Sorry Media Social could not be added'
            ],500);
        }

    }

    public function update(Request $request, $id){
        $mediaSocial =$this->user->mediasocials()->find($id);

        if(!$mediaSocial){
            return response()->json([
                'success' => false,
                'message' => 'Sorry, media social with id ' . $id . ' cannot be found'
            ],400);
        }

        $updated = $mediaSocial->fill($request->all())->Save();

        if($updated){
            return response()->json([
                'success'=>true
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Sorry, media social could not be updated'
            ],500);
        }
    }

    public function destroy($id){
        $mediaSocial = $this->user->mediasocials->find($id);

        if(!$mediaSocial){
            return response()->json([
                'success' => false,
                'message' => 'Sorry, media social with id ' . $id .' cannot be found'
            ]);
        }
        if($mediaSocial->delete()){
            return response()->json([
                'success' => true,
                'message' => 'Media social has been deleted'
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Media social could not be deleted'
            ],500);
        }
    }

}
