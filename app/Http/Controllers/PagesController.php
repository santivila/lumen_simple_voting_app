<?php namespace App\Http\Controllers;

use App\Vote;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PagesController extends Controller {

    /**
     * Confirms a vote.
     * Called when the user clicks on a confirm link sent by email.
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function confirm(Request $request, $token)
    {
            $v = Vote::where('token','=',$token)->where('confirmed_vote','=','false')->first();
            if(!$v){
            	// If the vote with the corresponding token is not found or it is already confirmed
            	return redirect()->to( env('APP_FAILEDVOTEURL') );
            }
            else{
                // Validate if this  user has already voted for this target
                if( env('APP_VOTESFORTARGET') > 0 )
                {
                    $existingVotes = Vote::where('voter_email','=',$v->voter_email)
                                    ->where('target_id','=',$v->target_id)
                                    ->where('confirmed_vote','=',true)
                                    ->count();
                    if( $existingVotes>=env('APP_VOTESFORTARGET') ){
                        return redirect()->to( env('APP_FAILEDVOTEURL') );
                    }
                }
            	$v->confirmed_vote = true;
            	$v->save();
            	return redirect()->to( env('APP_CONFIRMEDVOTEURL') );
            }
    }
}