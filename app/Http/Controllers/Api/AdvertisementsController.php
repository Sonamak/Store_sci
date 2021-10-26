<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AdvertisementsController extends Controller
{
    public function get_user_ads($user_id = null)
    {
        if(empty($user_id)) {
            return $this->responseJson(false, null, 'Invalid user id', 400);
        }
        // return the active ad for user.
        $user = User::find($user_id);
        if(!$user) {
            return $this->responseJson(false, null, 'User does not exist', 400);
        }
        $now = now()->toDateString();

        $ad = $user->advertisements()
        ->where('start_date', '<=', $now)
        ->where('end_date', '>=', $now)
        ->where('seen', 0)
        ->orderBy('id', 'ASC')
        ->first();

        if(! $ad)
            return $this->responseJson(false, null, 'There Is No Active Ads For This User', 400);

        return $this->responseJson(true, $ad, 'Advertisement Found', 200);
    }

    public function active_ad_has_seen($user_id = null, $ad_id = null)
    {
        if(empty($user_id)) {
            return $this->responseJson(false, null, 'Invalid user id', 400);
        }
        if(empty($ad_id)) {
            return $this->responseJson(false, null, 'Invalid Ad id', 400);
        }
        $user = User::find($user_id);
        if(!$user) {
            return $this->responseJson(false, null, 'User does not exist', 400);
        }

        $id = $user->id;

        $ad_exist = $user->advertisements()
        ->where('advertisement_user.advertisement_id', $ad_id)
        ->where('advertisement_user.user_id', $id)
        ->first();
        
        if($ad_exist == null)
            return $this->responseJson(false, null, 'Invalid Ad Id', 200);

        if($ad_exist->pivot->seen == 1)
            return $this->responseJson(false, null, 'User has already seen this Ad', 200);

        $user->advertisements()
        ->where('advertisement_user.advertisement_id', $ad_id)
        ->where('advertisement_user.user_id', $id)
        ->update(['seen' => 1]);
        
        return $this->responseJson(true, ['success' => 1], 'User Ad has been seen', 200);
    }
}
