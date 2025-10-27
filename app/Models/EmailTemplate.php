<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'subject', 
        'message_body',
        'button_level',
        'button_link',
        'bottom_status',
        'bottom_body',
        'status',
        'is_disclaimer',
        'is_risk_warning',
        'use_custom_html',
        'custom_html_content'
    ];
    
    protected $casts = [
        'status' => 'boolean',
        'bottom_status' => 'boolean',
        'is_disclaimer' => 'boolean',
        'is_risk_warning' => 'boolean',
        'use_custom_html' => 'boolean',
    ];

    private const BRACE_OPEN = '___BRACE_OPEN___';
    private const BRACE_CLOSE = '___BRACE_CLOSE___';
    private const STYLE_START = '___STYLE_START_MARKER___';
    private const STYLE_END = '___STYLE_END_MARKER___';
    private const SCRIPT_START = '___SCRIPT_START_MARKER___';
    private const SCRIPT_END = '___SCRIPT_END_MARKER___';

    public function getDecodedCustomHtml()
    {
        if (empty($this->custom_html_content)) {
            return '';
        }

        return $this->decodeHtmlTags($this->custom_html_content);
    }

    private function decodeHtmlTags($content)
    {
        if (empty($content)) {
            return '';
        }

        // Step 1: Protect {style} blocks - replace ALL braces inside with placeholders
        $content = preg_replace_callback(
            '/\{style\}(.*?)\{\/style\}/s',
            function ($matches) {
                $styleContent = $matches[1];
                // Replace ALL CSS braces with unique placeholders
                $styleContent = str_replace(['{', '}'], [self::BRACE_OPEN, self::BRACE_CLOSE], $styleContent);
                // Return with safe markers (no braces)
                return self::STYLE_START . $styleContent . self::STYLE_END;
            },
            $content
        );

        // Step 2: Protect {script} blocks - replace ALL braces inside with placeholders
        $content = preg_replace_callback(
            '/\{script\}(.*?)\{\/script\}/s',
            function ($matches) {
                $scriptContent = $matches[1];
                // Replace ALL JS braces with unique placeholders
                $scriptContent = str_replace(['{', '}'], [self::BRACE_OPEN, self::BRACE_CLOSE], $scriptContent);
                // Return with safe markers (no braces)
                return self::SCRIPT_START . $scriptContent . self::SCRIPT_END;
            },
            $content
        );

        // Step 3: Convert all remaining {tag} to <tag>
        // This only affects HTML tags, not the protected style/script content
        $content = preg_replace('/\{([\/]?[!a-zA-Z][a-zA-Z0-9]*[^}]*)\}/', '<$1>', $content);

        // Step 4: Convert safe markers to proper HTML tags
        $content = str_replace(self::STYLE_START, '<style>', $content);
        $content = str_replace(self::STYLE_END, '</style>', $content);
        $content = str_replace(self::SCRIPT_START, '<script>', $content);
        $content = str_replace(self::SCRIPT_END, '</script>', $content);

        // Step 5: Restore all protected braces
        $content = str_replace([self::BRACE_OPEN, self::BRACE_CLOSE], ['{', '}'], $content);

        return $content;
    }
}
