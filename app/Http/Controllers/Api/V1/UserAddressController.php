<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\Api\AddressResource;
use Illuminate\Http\Request;

class UserAddressController
{
    public function index(Request $request)
    {
        return AddressResource::collection($request->user()->addresses()->paginate());
    }
}
