<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BatchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $departmentId = $request->department;
        $batches = [];
        if($departmentId) {
            $batches = Batch::where('department_id', $departmentId)->get();
        }

        return response()->json([
            'status' => 200,
            'batches' => $batches
        ]);
    }

    public function search(Request $request)
    {
        $departmentId = (int) $request->department_id ? $request->department_id : '*';
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

        /* dd(DB::table('batches')
                    ->where('department_id', 5)
                    ->whereIn('id', $numericTerms)
                    ->orWhere('name', 'LIKE', '%'.$request->terms.'%')
                    ->orWhere(function($query) use($alphaTerms) {
                        foreach($alphaTerms as $word){
                            $query->where('department_id', 5)->orWhere('name', 'LIKE', '%'.$word.'%');
                        }
                    })->toSql()); */


        $result = DB::table('batches')
                    ->where('department_id', '=', $departmentId)
                    ->Where('name', 'LIKE', '%'.$request->terms.'%')
                    ->orWhere(function($query) use($numericTerms){
                        if(count($numericTerms) > 0) {
                            $query->orWhereIn('id', $numericTerms);
                        }
                    })
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
            'department_id' => 'required|exists:departments,id',
            'name' => 'required|string'
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors()
            ]);
        }

        $validated = $validator->validated();

        $batch = Batch::create($validated);

        return response()->json([
            'status' => 200,
            'batch' => $batch
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Batch  $batch
     * @return \Illuminate\Http\Response
     */
    public function show(Batch $batch, Request $request)
    {
        $batch->load('department')->loadCount('students');

        if($request->students) {
            $students = $batch->students()->latest()->simplePaginate(10);
            $batch->students = $students->toArray();
        }

        return response()->json([
            'status' => 200,
            'batch' => $batch,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Batch  $batch
     * @return \Illuminate\Http\Response
     */
    public function edit(Batch $batch)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Batch  $batch
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Batch $batch)
    {
        $validator = Validator::make($request->all(), [
            'department_id' => 'required|numeric|exists:departments,id',
            'name' => 'required|string'
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors()
            ]);
        }

        $validated = $validator->validated();

        if($batch->department_id != $validated['department_id']) {
            return response()->json(
                [
                    'status' => 422,
                    'errors' => 'Data constraint failed.'
                ]
            );
        }

        $batch->name = $validated['name'];
        $batch->save();

        return response()->json([
            'status' => 200,
            'batch' => $batch
        ]);
    }


    public function check(Request $request, Batch $batch)
    {
        // TOTO Handle not found error.
        // Suggestion: Make the exception frontend handlable. send execption as json response
        return response()->json([
            'status' => 200,
            'batch' => $batch
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Batch  $batch
     * @return \Illuminate\Http\Response
     */
    public function destroy(Batch $batch)
    {
        $batch->delete();

        return response()->json([
            'status' => 200,
            'batch' => 'Batch deleted'
        ]);
    }
}
