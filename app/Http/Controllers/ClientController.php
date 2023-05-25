<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Client::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            //Validate informartion
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255', Rule::unique('clients')],
                'birth_date' => ['required', 'date'],
                'skills' => ['string', 'nullable'],
                'education_level' => ['string', 'nullable'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validator->errors()->first()
                ], 401);
            }

            $record = Client::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Record Created Successfully',
                'record' => $record
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        //
    }
}
