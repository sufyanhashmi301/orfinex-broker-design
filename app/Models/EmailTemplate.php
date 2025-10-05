<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'subject', 'message_body', 'button_level', 'button_link', 'bottom_status', 'bottom_body', 'status', 'is_disclaimer', 'is_risk_warning', 'use_custom_html', 'custom_html_content'];
    protected $guarded = ['id'];

    /**
     * Get decoded custom HTML content for editing
     */
    public function getDecodedCustomHtml()
    {
        if (empty($this->custom_html_content)) {
            return '';
        }

        return $this->decodeHtmlTags($this->custom_html_content);
    }

    /**
     * Decode HTML tags while preserving CSS braces
     */
    private function decodeHtmlTags($content)
    {
        if (empty($content)) {
            return $content;
        }

        // First, protect CSS content inside style tags
        $content = preg_replace_callback('/\{style\}(.*?)\{\/style\}/s', function($matches) {
            $styleContent = $matches[1];
            // Convert HTML tags in style content but preserve CSS braces
            $styleContent = str_replace(['{', '}'], ['<', '>'], $styleContent);
            // Restore CSS braces for CSS rules
            $styleContent = preg_replace('/([a-zA-Z-]+)\s*<([^>]+)>/', '$1 {$2}', $styleContent);
            return '<style>' . $styleContent . '</style>';
        }, $content);

        // Then convert all remaining HTML tags including DOCTYPE
        $content = preg_replace('/\{([\/]?[!a-zA-Z][a-zA-Z0-9]*[^}]*)\}/', '<$1>', $content);

        return $content;
    }
}
