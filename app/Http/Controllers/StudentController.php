<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isEmpty;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $byDepartment = $request->has('byDepartment') ? $request->byDepartment : null;
        $byBatch = $request->has('byBatch') ? $request->byBatch : null;

        $sortName = $request->has('name') ? ($request->gpa == 'desc' ? 'desc' : 'asc' ) : null;
        $sortId = $request->has('date') ? ($request->date == 'desc' ? 'desc' : 'asc') : null;

        $students = Student::when($byDepartment, function($query) use($byDepartment){
                                $query->where('department_id', '=', $byDepartment);
                            })
                            ->when($byBatch, function($query) use($byBatch) {
                                $query->where('batch_id', '=', $byBatch);
                            })
                            ->when($sortName, function($query) use($sortName) {
                                $query->orderBy('name', $sortName);
                            })
                            ->when($sortId, function($query) use($sortId) {
                                $query->orderBy('id', $sortId);
                            })
                            ->paginate(10);


        return response()->json([
            'status' => 200,
            'students' => $students
        ]);
    }

    public function search(Request $request)
    {
        $departmentId = (int) $request->department_id ? $request->department_id : null;
        $batchId = (int) $request->batch_id ? $request->batch_id : null;

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


        $result = DB::table('students')
                    ->when($departmentId, function($query) use($departmentId) {
                        $query->where('department_id', '=', $departmentId);
                    })
                    ->when($batchId, function($query) use($batchId) {
                        $query->where('batch_id', '=', $batchId);
                    })
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
            'name' => 'required|string',
            'batch_id' => 'required|numeric'
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors()
            ]);
        }

        $validated = $validator->validated();


        $batch = Batch::find($validated['batch_id']);
        if($batch->department_id != $validated['department_id']) {
            return response()->json([
                'status' => 422,
                'errors' => [
                    'batch_id' => 'Batch not present in given Department'
                ]
            ]);
        }

        $validated['batch_name'] = $batch->name;

        $student = Student::create($validated);

        return response()->json([
            'status' => 200,
            'student' => $student
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student, Request $request)
    {
        $student->load(['department', 'batch']);

        $dateRange = $request->dateRange == 'false' ? false : true;
        $fromDate = $request->fromDate ? $request->fromDate : NULL;
        $toDate = $request->toDate ? $request->toDate : NULL;


        $resultSortGpa = $request->gpa ? ($request->gpa == 'desc' ? 'desc' : 'asc' ) : NULL;
        $resultSortDate = $request->date ? ($request->date == 'desc' ? 'desc' : 'asc') : NULL;

        /* if($request->results) {
            $results = DB::table('results')
                        ->when($fromDate != null, function($query) use($fromDate){
                            $query->whereDate('date', '=', $fromDate)->orWhereDate('date', '>', $fromDate);
                        })
                        ->when($toDate != null, function($query) use($toDate){
                            $query->whereDate('date', '=', $toDate)->orWhereDate('date', '<', $toDate);
                        })
                        ->where('student_id', $student->id)
                        ->when($resultSortGpa, function($query) use($resultSortGpa){
                            $query->orderBy('gpa', $resultSortGpa);
                        })
                        ->when($resultSortDate, function($query) use($resultSortDate) {
                            $query->orderBy('date', $resultSortDate);
                        })
                        // ->simplePaginate(10)
                        ->toSql();

            $student->results = $results;
        } */


        if($request->results) {
            $results = DB::table('results')
                        ->where(function($query) use($fromDate, $toDate, $dateRange){
                            if($dateRange) {
                                if($fromDate != NULL) {
                                    $query->whereDate('date', '=', $fromDate)->orWhereDate('date', '>', $fromDate);
                                }
                                if($toDate != NULL) {
                                    $query->whereDate('date', '=', $toDate)->orWhereDate('date', '<', $toDate);
                                }
                            }
                        })
                        ->where('student_id', $student->id)
                        ->when($resultSortGpa, function($query) use($resultSortGpa){
                            $query->orderBy('gpa', $resultSortGpa);
                        })
                        ->when($resultSortDate, function($query) use($resultSortDate) {
                            $query->orderBy('date', $resultSortDate);
                        })
                        ->simplePaginate(10);

            $student->results = $results;
        }

        return response()->json([
            'status' => 200,
            'student' => $student,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student, $transfer = false)
    {
        $validator = Validator::make($request->all(), [
            'department_id' => 'required|numeric|exists:departments,id',
            'batch_id' => 'required|numeric|exists:batches,id',
            'name' => 'required|string',
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors()
            ]);
        }

        $validated = $validator->validated();

        if(!$transfer) {
            // check student batch and department matches with request

            if($student->department_id != $validated['department_id'] || $student->batch_id != $validated['batch_id']) {
                return response()->json([
                    'status' => 422,
                    'errors' => 'Data constraint failed.'
                ]);
            }
        }

        $student->name = $validated['name'];
        $student->save();

        return response()->json([
            'status' => 200,
            'student' => $student
        ]);
    }

    public function updateTransfer(Request $request, Student $student)
    {
        $this->update($request, $student, true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        //
    }
}
