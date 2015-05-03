<?php namespace App;

use Illuminate\Database\Eloquent\Model;

final class Vote extends Model  
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['target_id', 'target_name', 'voter_email'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['confirmed_vote', 'token'];

	/**
	 * Assigns a new token for the vote.
	 *
	 * @return void
	 */
	public function assignToken()
	{
		$token = $this->getToken();
		while( !is_null( $this->where('token',$token)->first() ) ){
			$token = $this->getToken();
		}
		$this->token = $token;
		$this->save();
	}

	/**
	 * Create a new token 
	 *
	 * @return string
	 */
	public function getToken()
	{
		$token = hash_hmac('sha256', str_random(40), env('APP_KEY'));
		return $token;
	}	

}