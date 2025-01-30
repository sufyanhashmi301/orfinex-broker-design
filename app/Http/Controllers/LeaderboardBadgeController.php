<?php

namespace App\Http\Controllers;

use App\Models\LeaderboardBadge;
use Illuminate\Http\Request;

class LeaderboardBadgeController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:leaderboard-badge-edit', ['only' => ['store']]);
    }

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
        // Decode the JSON data into a PHP array
        $badgesData = json_decode($request->input('badges_data'), true);
        
        // Iterate over each badge data
        foreach ($badgesData as $badge) {
            // Find the badge in the database using the slug
            $leaderboardBadge = LeaderboardBadge::where('title_slug', $badge['slug'])->first();

            // If the badge exists, update it
            if ($leaderboardBadge) {
                // Prepare the fields to update
                $fieldsToUpdate = [];
                $detailsData = [];

                // Iterate over each key-value pair in the badge data
                foreach ($badge as $key => $value) {
                    if (str_starts_with($key, 'details_')) {
                        // If the key starts with 'details_', remove 'details_' and add to detailsData
                        $detailKey = str_replace('details_', '', $key);
                        $detailsData[$detailKey] = $value;
                    } else {
                        // Otherwise, it's a normal field; add it to fieldsToUpdate
                        $fieldsToUpdate[$key] = $value;
                    }
                }

                // Convert detailsData to JSON
                $fieldsToUpdate['details'] = $detailsData;

                // Update the fields in the database
                $leaderboardBadge->update($fieldsToUpdate);
            }
        }

        return redirect()->back();

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LeaderboardBadge  $leaderboardBadge
     * @return \Illuminate\Http\Response
     */
    public function show(LeaderboardBadge $leaderboardBadge)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LeaderboardBadge  $leaderboardBadge
     * @return \Illuminate\Http\Response
     */
    public function edit(LeaderboardBadge $leaderboardBadge)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LeaderboardBadge  $leaderboardBadge
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LeaderboardBadge $leaderboardBadge)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LeaderboardBadge  $leaderboardBadge
     * @return \Illuminate\Http\Response
     */
    public function destroy(LeaderboardBadge $leaderboardBadge)
    {
        //
    }
}
