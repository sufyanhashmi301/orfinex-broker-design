<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ForexSchema;
use App\Models\Schema;
use App\Models\User;
use App\Traits\ForexApiTrait;

class ForexSchemaController extends Controller
{
    use ForexApiTrait;
    public function index()
    {

//        $this->sendApiPostRequest('url','data');
//        $this->getUserApi(554944);

        $tagNames = auth()->user()->riskProfileTags()->pluck('name')->toArray();

        $schemas = ForexSchema::where('status', true)
            ->where(function($query) use ($tagNames) {
                $query->whereJsonContains('country', auth()->user()->country)
                    ->orWhereJsonContains('country', 'All')
                    ->orWhere(function($subQuery) use ($tagNames) {
                        foreach ($tagNames as $tagName) {
                            $subQuery->orWhereJsonContains('tags', $tagName);
                        }
                    });
            })
            ->orderBy('priority', 'asc')
            ->get();
//        dd($schemas);

        return view('frontend::forex_schema.index', compact('schemas'));
    }

    public function schemaPreview($id)
    {
        $tagNames = auth()->user()->riskProfileTags()->pluck('name')->toArray();
        $schemas = ForexSchema::where('status', true)
            ->where(function($query) use ($tagNames) {
                $query->whereJsonContains('country', auth()->user()->country)
                    ->orWhereJsonContains('country', 'All')
                    ->orWhere(function($subQuery) use ($tagNames) {
                        foreach ($tagNames as $tagName) {
                            $subQuery->orWhereJsonContains('tags', $tagName);
                        }
                    });
            })
            ->orderBy('priority', 'asc')
            ->get();
//        $schemas = ForexSchema::where('status', true)->orderBy('priority','asc')->get();
        $schema = ForexSchema::find($id);

        return view('frontend::forex_schema.preview', compact('schema', 'schemas'));
    }

    public function schemaSelect($id)
    {
        $schema = ForexSchema::find($id);
        $currency = setting('site_currency', 'global');

        $leveragedropdown = '';
        foreach (explode(',', $schema->leverage) as $leverage) {
            $leveragedropdown .= '<option value="' . $leverage . '">' . $leverage . '</option>';
        }

        return [
            'leverage' => $leveragedropdown,
            'display_leverage' => explode(',', $schema->leverage)[0],
            'first_min_deposit' => !empty($schema->first_min_deposit) ? $schema->first_min_deposit : 0,
            'spread' => !empty($schema->spread) ? $schema->spread : 0,
            'commission' => $schema->commission == 0 ? 'No Commission' : $schema->commission,
            'is_real_islamic' => $schema->is_real_islamic,
            'is_demo_islamic' => $schema->is_demo_islamic,
        ];
    }


}
