<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ThemeColor;
use App\Services\ColorService;
use App\Jobs\GenerateColorCssJob;

class ThemeColorController extends Controller
{
    public function index()
    {
        $colors = ThemeColor::all();
        return view('backend.theme.colors', compact('colors'));
    }

    public function update(Request $request, ColorService $service)
    {
        foreach ($request->colors as $name => $hex) {
            $color = ThemeColor::where('name', $name)->first();
            if ($color) {
                $shades = $service->generateShades($hex);
                $color->update(['base_color' => $hex, 'shades' => $shades]);
            }
        }

        $service->generateCssFile();

        notify()->success(__('Colors updated successfully!'));
        return back();
    }
}
