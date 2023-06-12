<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Company;
use App\Models\Person;
use App\Models\Positions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;

class PositionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $company_id)
    {
        return Positions::where('company_id', $company_id)->get();
    }

    public function getPosition($id)
    {
        return Positions::with('applicants.person')->where('id', $id)->first();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) //: JsonResponse
    {
        try {
            //Validate informartion
            $validator = Validator::make($request->all(), [
                'role' => ['required', 'string', 'max:255', Rule::unique('positions')],
                'company_id' => ['required', 'max:255'],
                'description' => ['required'],
                'years_of_experience' => ['numeric', 'nullable', 'max:10'],
                'budget' => ['numeric', 'required', 'max:15000'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validator->errors()->first()
                ], 401);
            }

            $usedBudget = Positions::where('company_id', $request->company_id)->sum('budget');
            $company = Company::find($request->company_id);

            if (($usedBudget + $request->budget) > $company->budget) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => 'Budget not enough'
                ], 401);
            }


            $record = Positions::create($request->all());
            $company->open_positions = $company->open_positions + 1;
            $company->save();

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

    public function saveApplicant(Request $request): JsonResponse
    {
        $position = $request->input('positions_id');
        try {
            //Validate informartion
            $validator = Validator::make($request->all(), [
                'positions_id' => ['required', 'max:255'],
                'person_id' => ['required', 'max:255', Rule::unique('applicants')
                    ->where(function ($query) use ($position) {
                        return $query->where('positions_id', $position);
                    })],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validator->errors()->first()
                ], 401);
            }

            $person = Person::find($request->input('person_id'));

            if (!$person->available) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => 'This person is not currently available'
                ], 401);
            }

            $record = Applicant::create($request->all() + ['score' => 1]);

            $person->available = false;
            $person->save();

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

    public function deleteApplicant(string $id): JsonResponse
    {
        Applicant::find($id)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Record Deleted Successfully',
        ], 200);
    }

    public function markWinner(string $id): JsonResponse
    {
        $applicant = Applicant::find($id);
        $applicant->selected = true;
        $applicant->save();

        $position = Positions::find($applicant->positions_id);
        $position->person_id = $applicant->person_id;
        $position->open = false;
        $position->save();

        return response()->json([
            'status' => true,
            'message' => 'Winner Updated Successfully',
            'record' => $position
        ], 200);
    }
}
