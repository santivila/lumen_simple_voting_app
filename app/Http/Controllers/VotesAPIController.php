<?php namespace App\Http\Controllers;

use App\Vote;
use Validator;
use Mail;
// use Illuminate\Mail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VotesAPIController extends APIController {

    protected $transformer;

    /**
     * Returns data corresponding to the given target_id.
     * Name of the resource, total number of votes ...
     * @param Request $request
     * @param int $target_id
     * @return Response
     */
    public function show( $target_id )
    {
        // Check if the target resource exists
        $target =  Vote::where('target_id',$target_id);
        if($target->count()){
            // Get target votes data
            $total_votes =  $target->where('confirmed_vote','=',true)->count();
            // Names sent for a specific id may differ. Id is unique. Just pick the first one for now.
            $target_name = $target->first();
            $target_name ? $target_name = $target_name->toArray() : $target_name = ['target_name' => null];
            // Return Json 
            return $this->respond([
                    'error' => false,
                    'data'   => [
                                'target_id' => $target_id,
                                'target_name' => $target_name['target_name'],
                                'total_votes' => $total_votes
                                ]
            ]);
        }
        return $this->respondNotFound();

    }

    /**
     * Creates a new vote in the database ans sends an email to the user
     * asking to click on a link to confirm the vote
     * @param Request $request
     * @return Response
     */
    public function store( Request $request )
    {
            // Validate vote fields
            $v = Validator::make($request->all(), [
                'target_id' => 'required|numeric',
                // 'target_name' => 'required',
                'voter_email' => 'required|email'
            ]);
            if ($v->fails()) {
                $firstApiError = $v->errors()->first();
                return $this->setStatusCode(422)->respondWithError($firstApiError);
            }

            // Validate if this  user has already voted for this target
            if( env('APP_VOTESFORTARGET') > 0 )
            {
                $existingVotes = Vote::where('voter_email','=',$request->input('voter_email'))
                                    ->where('target_id','=',$request->input('target_id'))
                                    ->where('confirmed_vote','=',true)
                                    ->count();
                if( $existingVotes>=env('APP_VOTESFORTARGET') ){
                    return $this->setStatusCode(422)->respondWithError('Max. votes per target reached');
                }
            }

            // Create the vote
            $vote = Vote::create($request->all());

            // Assign a token to the vote
            $vote->assignToken();
            
            // Send the confirmation mail
            $confirmation_url = $request->root().'/t/'.$vote->token;
            $subject = env('APP_DOMAIN').': Confirm your vote';
            Mail::send('emails.confirm_vote', ['confirmation_url' => $confirmation_url], function($message) use($vote,$subject) {
                $message->to($vote->voter_email,$vote->voter_email)->subject($subject);
            });
     
            // Return Json 
            return $this->respond([
                    'error' => false,
                    'data'   => ['Vote saved succesfully']
            ]);
    }
}