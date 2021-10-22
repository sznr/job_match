<?php

namespace App\Http\Controllers;

use App\Models\JobOffer;
use App\Models\Occupation;
use Illuminate\Http\Request;

class JobOfferController extends Controller
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $occupations = Occupation::all();
        return view('job_offers.create', compact('occupations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\JobOffer  $jobOffer
     * @return \Illuminate\Http\Response
     */
    public function show(JobOffer $jobOffer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\JobOffer  $jobOffer
     * @return \Illuminate\Http\Response
     */
    public function edit(JobOffer $jobOffer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\JobOffer  $jobOffer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JobOffer $jobOffer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\JobOffer  $jobOffer
     * @return \Illuminate\Http\Response
     */
    public function destroy(JobOffer $jobOffer)
    {
        //
    }
}
