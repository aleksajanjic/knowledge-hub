<?php

namespace App\Helpers;

use League\CommonMark\CommonMarkConverter;

class MarkdownHelper
{
    public static function parse(?string $markdown): string
    {
        if (empty($markdown)) {
            return '';
        }

        $converter = new CommonMarkConverter([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);

        return $converter->convert($markdown)->getContent();
    }
}
