<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class APIController extends Controller {

	/**
	* $var int
	*/
	protected $statusCode = 200;


	/**
	* @return mixed
	*/
	public function getStatusCode(){
		return $this->statusCode;
	}

	/**
	* @param mixed $statusCode
	* @return $this
	*/
	public function setStatusCode($statusCode){
		$this->statusCode = $statusCode;
		return $this;
	}

	public function respond($data, $headers = [])
	{
		return response()->json($data, $this->getStatusCode(), $headers);
	}

	/**
	* @param Paginator $items
	* @param $data
	* @return mixed
	*/
	public function respondWithPagination(LengthAwarePaginator $items, $data)
	{
		//dd($items->nextPageUrl());
		$data = array_merge( $data, [
			'paginator' => [
				'total_count' => $items->total(),
				'total_pages' => ceil( $items->total() / $items->perPage() ),
				'current_page' => $items->currentPage(),
				'limit' => $items->perPage()
			]
		]);
		return $this->respond($data);
	}

	public function respondWithError($message)
	{
		return $this->respond([
				'error' => [
					'status_code' => $this->getStatusCode(),
					'message'   => $message
				]
		],[$this->getStatusCode()] );
	}

	public static function respondNotFound($message = 'Not found'){
		return $this->setStatusCode(404)->respondWithError($message);
	}

	public function respondInternalError($message = 'Internal error'){
		return $this->setStatusCode(500)->respondWithError($message);
	}



}
