<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\helpers\ApiRseponse;
use App\Models\Follower;
use App\Models\User;
use App\Notifications\Follow;
use Illuminate\Http\Request;
use Notification;

class FollowController extends Controller
{
    public function store(User $user){

      
        Follower::updateOrCreate([
            'follower_id' => auth()->id(),
            'user_id' => $user->id
        ]);

     

      Notification::send($user, new Follow(auth()->user()));

return ApiRseponse::sendresponse(200, 'followed successfully', []);

    }

    public function destroy(User $user){
        Follower::where('follower_id', auth()->id())->where('user_id', $user->id)->delete();
        return ApiRseponse::sendresponse(200, 'unfollowed successfully', []);
    }
}
