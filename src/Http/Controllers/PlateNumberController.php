<?php

namespace MPWT\VRDL\Http\Controllers;

use Illuminate\Routing\Controller;
use MPWT\VRDL\Http\Requests\GetPlateNumberRequest;

class PlateNumberController extends Controller
{
	public function __construct(
		protected PlateNumberService $service
	)
	{ }

	public function getPlateNumbers(GetPlateNumberRequest $request)
	{
		if($request->plate_number) {
			$data = $this->service->search($request);
		}
		else {
			$data = $this->service->listing($request);
		}
		return $this->responseSuccess($data);
	}
}
