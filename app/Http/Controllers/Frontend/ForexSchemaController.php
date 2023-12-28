<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ForexSchema;
use App\Models\Schema;
use App\Traits\ForexApiTrait;

class ForexSchemaController extends Controller
{
    use ForexApiTrait;
    public function index()
    {
        $this->sendApiPostRequest('url','data');
//        $this->getUserApi(9996792);
        $schemas = ForexSchema::where('status', true)->get();

        return view('frontend::forex_schema.index', compact('schemas'));
    }

    public function schemaPreview($id)
    {

        $schemas = ForexSchema::where('status', true)->get();
        $schema = ForexSchema::find($id);

        return view('frontend::forex_schema.preview', compact('schema', 'schemas'));
    }

    public function schemaSelect($id)
    {

        $schema = ForexSchema::find($id);
        $currency = setting('site_currency', 'global');
        $groupDropdown = '';
        if($schema->real_swap_free)
            $groupDropdown .='<option value="real_swap_free">'.__("Real (Swap Free)").'</option>';

        if($schema->real_islamic)
            $groupDropdown .='<option value="real_islamic">'.__("Real (Islamic)").'</option>';

        if($schema->demo_swap_free)
            $groupDropdown .='<option value="demo_swap_free">'.__("Demo (Swap Free)").'</option>';

        if($schema->demo_islamic)
            $groupDropdown .='<option value="demo_islamic">'.__("Demo (Islamic)").'</option>';


        $leveragedropdown = '';
        foreach (explode(',', $schema->leverage) as $leverage){
            $leveragedropdown.='<option value="'.$leverage.'">'.$leverage.'</option>';
        }


        return [
            'leverage' => $leveragedropdown,
            'group' => $groupDropdown,
//            'amount_range' => $schema->type == 'range' ? 'Minimum '.$schema->min_amount.' '.$currency.' - '.'Maximum '.$schema->max_amount.' '.$currency : $schema->fixed_amount.' '.$currency,
//            'return_interest' => ($schema->interest_type == 'percentage' ? $schema->return_interest.'%' : $schema->return_interest.' '.$currency).' ('.$schema->schedule->name.')',
//            'number_period' => ($schema->return_type == 'period' ? $schema->number_of_period : 'Unlimited').($schema->number_of_period == 1 ? ' Time' : ' Times'),
//            'capital_back' => $schema->capital_back ? 'Yes' : 'No',
            'first_min_deposit' =>  !empty($schema->first_min_deposit) ? $schema->first_min_deposit : 0 ,
//            'interest' => $schema->return_interest,
//            'period' => $schema->number_of_period,
//            'interest_type' => $schema->interest_type,
        ];

    }
}
