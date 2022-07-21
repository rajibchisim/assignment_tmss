<?php

namespace App\Http\Controllers;

use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ResultController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


    public function search(Request $request)
    {
        $studentId = (int) $request->student_id ? $request->student_id : false;
        $rawTerms = collect(explode(' ', $request->terms))
                    ->filter(function($item) {
                        return $item != '' || $item != null;
                    })
                    ->all();

        $numericTerms = [];
        $alphaTerms = [];


        foreach ($rawTerms as $item) {
            if(is_numeric($item)) {
                $numericTerms[] = $item;
            } else {
                $alphaTerms[] = $item;
            }
        }


        $max = collect($numericTerms)->max();
        $min = collect($numericTerms)->min();

        $result = DB::table('results')
                    ->when($studentId != false, function($query) use($studentId){
                        $query->where('student_id', '=', $studentId);
                    })
                    ->when(count($numericTerms) > 0, function($query) use($max, $min) {
                        if($max - $min == 0) {
                            $query->where('gpa', $max);
                        } else {
                            $query->whereBetween('gpa', [$min, $max]);
                        }
                    })
                    // ->orWhere('date', 'LIKE', )
                    // ->orWhere(function($query) use($alphaTerms) {
                    //     foreach($alphaTerms as $word){
                    //         $query->where('department_id', 5)->orWhere('name', 'LIKE', '%'.$word.'%');
                    //     }
                    // })
                    ->simplePaginate(10);


        return response()->json([
            'status' => 200,
            'result' => $result
        ]);

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'gpa' => 'required|numeric|max:5|min:1',
            'date' => 'required|date'
        ],
        [
            'gpa' => 'Maximum value for gpa is 5.'
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors()
            ]);
        }

        $validated = $validator->validated();




        $result = Result::create($validated);

        return response()->json([
            'status' => 200,
            'result' => $result
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Result  $result
     * @return \Illuminate\Http\Response
     */
    public function show(Result $result)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Result  $result
     * @return \Illuminate\Http\Response
     */
    public function edit(Result $result)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Result  $result
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Result $result)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'gpa' => 'required|numeric|max:5|min:0',
            'date' => 'required|date'
        ],
        [
            'gpa.max' => 'Maximum value for gpa is 5',
            'gpa.numeric' => 'Number only! Eg. use 0 for F'
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors()
            ]);
        }

        $validated = $validator->validated();

        if($result->student_id != $validated['student_id']) {
            return response()->json([
                'status' => 422,
                'errors' => 'Not permitted. Data constranit failed.'
            ]);
        }

        $result->gpa = $validated['gpa'];
        $result->date = $validated['date'];
        $result->save();

        return response()->json([
            'status' => 200,
            'result' => $result
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Result  $result
     * @return \Illuminate\Http\Response
     */
    public function destroy(Result $result)
    {
        $result->delete();

        return response()->json([
            'status' => 200,
            'result' => 'Result deleted'
        ]);
    }
}
