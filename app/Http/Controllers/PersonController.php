<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Person::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            //Validate informartion
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255', Rule::unique('people')],
                'birth_date' => ['required', 'max:255'],
                'skills' => ['required'],
                'education_level' => ['required'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validator->errors()->first()
                ], 401);
            }

            $record = Person::create($request->all());

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
     * Display the specified resource.
     */
    public function show(Person $person)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Person $person)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Person $person)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($personId)
    {
        $person = Person::find($personId);

        if (!$person->available) {
            return response()->json([
                'status' => false,
                'message' => 'error',
                'errors' => "Person or client is not available to delete"
            ], 401);
        }

        $person->delete();

        return response()->json([
            'status' => true,
            'message' => 'Record Deleted Successfully',
        ], 200);
    }
}
