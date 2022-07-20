<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $departments = [];


        if($request->batches) {
            $departments = Department::with([
                'batches' =>
                    function($query) {
                        $query->withCount('students');
                    }

                ])
                ->simplePaginate(10);

        } else {
            $departments = Department::simplePaginate(10);

        }

        // dd($departments);

        return response()->json([
            'status' => 200,
            'departments_paginated' => $departments
        ]);
    }


    public function search(Request $request)
    {
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

        $result = DB::table('departments')
                    ->whereIn('id', $numericTerms)
                    ->orWhere(function($query) use($alphaTerms) {
                        foreach($alphaTerms as $word){
                            $query->orWhere('name', 'LIKE', '%'.$word.'%');
                        }
                    })
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
            'name' => 'required|string'
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors()
            ]);
        }

        $validated = $validator->validated();

        $department = Department::create($validated);

        return response()->json([
            'status' => 200,
            'department' => $department
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department, Request $request)
    {
        $summary = DB::select(
            'select (select count(*) from batches where department_id = ?) as batches_count, (select count(*) from students where department_id = ?) as students_count',
            [
                $department->id,
                $department->id
            ]
        );

        if($request->batches) {
            $batches = $department->batches()->withCount('students')->latest()->simplePaginate(10);
            $department->batches = $batches->toArray();
        }

        $department->students_count = $summary[0]->students_count;
        $department->batches_count = $summary[0]->batches_count;

        return response()->json([
            'status' => 200,
            'department' => $department,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit(Department $department)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Department $department)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department)
    {
        //
    }
}
