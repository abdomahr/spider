<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\helpers\ApiRseponse;
use App\Http\Requests\TweetRequest;
use App\Http\Resources\TweetCollection;
use App\Models\Follower;
use App\Models\Tweet;
use Illuminate\Http\Request;

class TweetController extends Controller
{
    public function index(){

    $followings = Follower::where('follower_id', auth()->id())->pluck('user_id')->toArray();
    $tweets = Tweet::whereIn('user_id', $followings)->paginate();
    return ApiRseponse::sendresponse(201, 'tweet created successfully', new TweetCollection($tweets));
       }



    public function store(Request $request){
        $request->validate([
            'content' => 'required|max:255',
        ]);
        auth()->user()->tweets()->create($request->all());
        return ApiRseponse::sendresponse(201, 'tweet created successfully',$request);

    }

 

    public function update(TweetRequest $request , Tweet $tweet){
        if (auth()->id() != $tweet->user_id) {
            return ApiRseponse::sendresponse(403, 'Unauthorized', []);
        }
        $content = $request->validated();
        $tweet ->update($content);
        return ApiRseponse::sendresponse(200, 'tweet updated successfully', $tweet);

    }



    public function destroy( Tweet $tweet){

        if (auth()->id() != $tweet->user_id) {
            return ApiRseponse::sendresponse(403, 'Unauthorized', []);
        }
      $post = Tweet::destroy($tweet);

    return ApiRseponse::sendresponse(200, "success", []);
        
    }
   
}
