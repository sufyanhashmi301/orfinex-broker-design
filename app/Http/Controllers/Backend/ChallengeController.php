<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Challenge;
use Illuminate\Http\Request;

class ChallengeController extends Controller
{
    public function index()
    {
        $challeges = Challenge::get();
        return view('backend.funded.challenge.index', compact('challeges'));
    }

    public function create()
    {
        return view('backend.funded.challenge.create');
    }

    public function store(Request $request)
    {
        Challenge::create($request->all());

        notify()->success('Challenge created Successfully');

        return redirect()->back();
    }

    public function edit($id)
    {
        $challenge = Challenge::find($id);

        return view('backend.funded.challenge.edit', compact('challenge'));
    }

    public function update(Request $request, $id)
    {
        Challenge::find($id)->update($request->all());

        notify()->success('Challenge updated Successfully');

        return redirect()->back();
    }
    public function step_rules_index()
    {
        return view('backend.funded.step_rules');
    }
    public function step_rules_create(Request $request)
    {
        return redirect()->back();
    }
}