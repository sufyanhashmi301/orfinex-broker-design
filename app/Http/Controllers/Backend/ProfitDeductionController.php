<?php

namespace App\Http\Controllers\Backend;


use App\Http\Controllers\Controller;
use App\Models\ProfitDeduction;
use Carbon\Carbon;
use Illuminate\Http\Request;


class ProfitDeductionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $profits = ProfitDeduction::whereDate('start_date', '>=', Carbon::now())->orderBy('id','DESC')->get();
//        dd($profits);
        return view('backend.profit_deduction.index',['profits'=>$profits]);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function store(Request $request)
    {
        $request->validate([
            'percentage' => ['required_if:type,==,add', 'numeric', 'gt:0','max:100'],
//            'start_date' => ['required'],

        ], [
            'percentage.gt' => __('Percentage should be greater than 0 (zero).'),
            'percentage.numeric' => __('Percentage should be valid number to add.'),
            'percentage.required' => __('Please enter the percentage to add profits.'),
//            'start_date.required' => __('Please enter the date to add profits.')
        ]);
//        dd($request->all());
        switch ($request->type) {
            case 'add':
                $event = ProfitDeduction::create([
                    'plan_name' => 'daily',
                    'start_date' => $request->start_date,
                    'end_date' => $request->start_date,
                    'percentage' => $request->percentage,
                ]);

                return response()->json($event);
                break;

            case 'update':
                $event = ProfitDeduction::find($request->id)->update([
                    'start_date' => $request->start_date,
                    'end_date' => $request->start_date,
                ]);
                return response()->json($event);
                break;

            case 'delete':
                $event = ProfitDeduction::find($request->id)->delete();

                return response()->json($event);
                break;

            default:
                # code...
                break;
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'percentage' => ['required', 'numeric', 'gt:0'],
            'start_date' => ['required'],

        ], [
            'percentage.gt' => __('Percentage should be greater than 0 (zero).'),
            'percentage.numeric' => __('Percentage should be valid number to add.'),
            'percentage.required' => __('Please enter the percentage to add profits.'),
            'start_date.required' => __('Please enter the date to add profits.')
        ]);
        $date = Carbon::createFromFormat('m/d/Y',$request->start_date)->format('Y-m-d');
        $profit = ProfitDeduction::whereDate('start_date',$date)->whereDate('end_date',$date)->where('id','<>',$id)->exists();
        if($profit){
            return redirect()->route('admin.profits')->withErrors(['danger'=> 'The date '.$request->start_date.' already exists.']);
        }
        $data = [
            'plan_name'=> 'daily',
            'start_date'=> $date,
            'end_date'=> $date,
            'percentage'=> $request->percentage,
        ];
        ProfitDeduction::updateOrCreate(['id'=>$id],$data);
        return redirect()->route('admin.profits')->withErrors(['success'=> 'The date '.$request->start_date.' successfully updated.']);

    }


}
