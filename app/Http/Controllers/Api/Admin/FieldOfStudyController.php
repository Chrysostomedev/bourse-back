<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\FieldOfStudy;
use Illuminate\Http\Request;

class FieldOfStudyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return FieldOfStudy::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(FieldOfStudy $fieldOfStudy)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FieldOfStudy $fieldOfStudy)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FieldOfStudy $fieldOfStudy)
    {
        //
    }
}
