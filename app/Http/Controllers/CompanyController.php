<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Company::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            //Validate informartion
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:50', Rule::unique('companies')],
                'address' => ['required', 'max:255'],
                'zip_code' => ['numeric', 'nullable', 'max:9999'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validator->errors()->first()
                ], 401);
            }

            $record = Company::create($request->all());

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
    public function update(Request $request, Company $company)
    {
        //
    }
}
