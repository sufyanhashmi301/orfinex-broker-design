<?php

namespace App\Services;

use App\Models\ThemeColor;
use Illuminate\Support\Facades\File;

class ColorService
{
    public function generateShades(string $base): array
    {
        // Use predefined color mappings that match your template
        $colorMappings = $this->getTemplateColorMappings();
        
        // Check if we have a predefined mapping for this base color
        foreach ($colorMappings as $colorName => $mapping) {
            if (strtolower($mapping[500]) === strtolower($base)) {
                return $mapping;
            }
        }
        
        // Fallback to algorithmic generation if no mapping found
        return $this->generateShadesAlgorithmic($base);
    }

    /**
     * Get predefined color mappings that match your template colors
     */
    private function getTemplateColorMappings(): array
    {
        return [
            'success' => [
                25 => '#f6fef9',
                50 => '#ecfdf3', 
                100 => '#d1fadf',
                200 => '#a6f4c5',
                300 => '#6ce9a6',
                400 => '#32d583',
                500 => '#12b76a',
                600 => '#039855',
                700 => '#027a48',
                800 => '#05603a',
                900 => '#054f31',
                950 => '#053321'
            ],
            'brand' => [
                25 => '#f5f5ff',
                50 => '#ebebff',
                100 => '#d6d9ff',
                200 => '#b4b8ff',
                300 => '#8b8bff',
                400 => '#6366f1',
                500 => '#3641f5',
                600 => '#2f37dc',
                700 => '#252db3',
                800 => '#1f2491',
                900 => '#1e2176',
                950 => '#131546'
            ],
            'error' => [
                25 => '#fffbfa',
                50 => '#fef7f0',
                100 => '#feecdc',
                200 => '#fcd9bd',
                300 => '#f9c394',
                400 => '#f7b27a',
                500 => '#f04438',
                600 => '#d92d20',
                700 => '#b42318',
                800 => '#912018',
                900 => '#7a271a',
                950 => '#55160c'
            ],
            'warning' => [
                25 => '#fffcf5',
                50 => '#fffaeb',
                100 => '#fef0c7',
                200 => '#fedf89',
                300 => '#fec84b',
                400 => '#fdb022',
                500 => '#f79009',
                600 => '#dc6803',
                700 => '#b54708',
                800 => '#93370d',
                900 => '#7a2e0e',
                950 => '#4e1d09'
            ],
            'info' => [
                25 => '#f5fbff',
                50 => '#f0f9ff',
                100 => '#e0f2fe',
                200 => '#bae6fd',
                300 => '#7dd3fc',
                400 => '#38bdf8',
                500 => '#0ea5e9',
                600 => '#0086c9',
                700 => '#0284c7',
                800 => '#0369a1',
                900 => '#075985',
                950 => '#0c4a6e'
            ],
            'secondary' => [
                25 => '#fcfcfd',
                50 => '#f9fafb',
                100 => '#f2f4f7',
                200 => '#eaecf0',
                300 => '#d0d5dd',
                400 => '#98a2b3',
                500 => '#667085',
                600 => '#475467',
                700 => '#344054',
                800 => '#1d2939',
                900 => '#101828',
                950 => '#0c111d'
            ]
        ];
    }

    /**
     * Fallback algorithmic shade generation for custom colors
     */
    private function generateShadesAlgorithmic(string $base): array
    {
        $shades = [];
        foreach ([25,50,100,200,300,400,500,600,700,800,900,950] as $i => $shade) {
            if ($shade == 500) {
                $hex = $base;
            } elseif ($shade < 500) {
                $factor = (5 - $i) * 0.1;
                $hex = $this->lighten($base, $factor);
            } else {
                $factor = ($i - 5) * 0.1;
                $hex = $this->darken($base, $factor);
            }
            $shades[$shade] = $hex;
        }
        return $shades;
    }

    public function regenerateColors(): void
    {
        $colors = ThemeColor::all();
        foreach ($colors as $color) {
            $shades = $this->generateShades($color->base_color);
            $color->update(['shades' => $shades]);
        }

        $this->generateCssFile();
    }

    public function generateCssFile(): void
    {
        $colors = ThemeColor::all();
        $css = ":root {\n";

        foreach ($colors as $color) {
            // Map database names to CSS variable names
            $cssName = $this->mapToCssName($color->name);
            
            foreach ($color->shades as $shade => $hex) {
                $css .= "  --color-{$cssName}-{$shade}: {$hex};\n";
            }
        }

        $css .= "}\n";

        // Generate both files for flexibility
        $paths = [
            public_path('assets/common/css/theme-colors.css'),
            base_path('assets/common/css/dynamic-colors.css')
        ];

        foreach ($paths as $path) {
            if (!file_exists(dirname($path))) {
                mkdir(dirname($path), 0777, true);
            }
            file_put_contents($path, $css);
        }
    }

    /**
     * Map database color names to CSS variable names
     */
    private function mapToCssName(string $dbName): string
    {
        $mapping = [
            'info' => 'blue-light',
            'secondary' => 'gray'
        ];

        return $mapping[$dbName] ?? $dbName;
    }

    /**
     * Generate CSS variables for inline inclusion in templates
     */
    public function generateCssVariables(): string
    {
        $colors = ThemeColor::all();
        $css = "";

        foreach ($colors as $color) {
            // Map database names to CSS variable names
            $cssName = $this->mapToCssName($color->name);
            
            foreach ($color->shades as $shade => $hex) {
                $css .= "    --color-{$cssName}-{$shade}: {$hex};\n";
            }
        }

        return $css;
    }

    /**
     * Get legacy color mapping for backward compatibility
     */
    public function getLegacyColorMapping(): array
    {
        $colors = ThemeColor::all();
        $mapping = [
            'primary_color' => 'brand',
            'success_color' => 'success', 
            'danger_color' => 'error',
            'warning_color' => 'warning',
            'info_color' => 'info',
            'secondary_color' => 'secondary'
        ];

        $result = [];
        foreach ($mapping as $legacyKey => $newKey) {
            $themeColor = $colors->where('name', $newKey)->first();
            if ($themeColor) {
                $result[$legacyKey] = $themeColor->base_color;
            }
        }

        return $result;
    }

    private function lighten(string $hex, float $factor): string
    {
        $rgb = $this->hexToRgb($hex);
        $new = array_map(fn($c) => min(255, $c + (255 - $c) * $factor), $rgb);
        return $this->rgbToHex($new);
    }

    private function darken(string $hex, float $factor): string
    {
        $rgb = $this->hexToRgb($hex);
        $new = array_map(fn($c) => max(0, $c - $c * $factor), $rgb);
        return $this->rgbToHex($new);
    }

    private function hexToRgb(string $hex): array
    {
        $hex = str_replace('#', '', $hex);
        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2))
        ];
    }

    private function rgbToHex(array $rgb): string
    {
        return sprintf("#%02x%02x%02x", $rgb[0], $rgb[1], $rgb[2]);
    }
}
