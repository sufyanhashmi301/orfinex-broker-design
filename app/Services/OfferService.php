<?php

namespace App\Services;

use App\Models\Offer;
use App\Models\User;
use App\Models\UserOffer;
use App\Traits\NotifyTrait;

class OfferService
{

  use NotifyTrait;
  private function check_elgibility($offer, $userOffer) {
      $eligible_for_offer = false;
      
      if(!$offer->multiple_time_usage && $userOffer->exists()) {
          $eligible_for_offer = false;
      }
      if(!$offer->multiple_time_usage && !$userOffer->exists()) {
          $eligible_for_offer = true;
      }
      if($offer->multiple_time_usage) {
          $eligible_for_offer = true;
      }

      return $eligible_for_offer;
  }

  public function createUserOffer($offer_type, $user_id) {
    $offer = Offer::where('condition', $offer_type)->where('status', '1')->orderBy('id', 'DESC')->first();
    $userOffer = UserOffer::where('user_id', $user_id)->where('on_condition', $offer_type);

    if(!$offer || !$userOffer) {
      return false;
    }

    $eligible_for_offer = $this->check_elgibility($offer, $userOffer);
    
    if($eligible_for_offer) {

      // Only create a new offer of same type if the available one does not exist
      if(!$userOffer->where('status', 'available')->exists()) {
        $addOffer = new UserOffer();
        $addOffer->user_id = $user_id;
        $addOffer->offer_id = $offer->id;
        $addOffer->on_condition = $offer_type;
        $addOffer->status = 'available';
        $addOffer->save();

        $this->sendEmail($user_id);
      }
        
    }

    return true;    
  }

  private function sendEmail($user_id) {
    $user = User::find($user_id);
    $shortcodes = [
      '[[full_name]]' => $user->first_name . ' ' . $user->last_name,
      '[[email]]' => $user->email,
      '[[site_title]]' => setting('site_title', 'global')
    ];
    $mail = $this->mailNotify($user->email, 'offer_awarded', $shortcodes);
  }
  
}
