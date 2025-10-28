<?php

use League\CommonMark\GithubFlavoredMarkdownConverter;

if (!function_exists('markdown_to_html')) {
    /**
     * Convert Markdown to HTML with GitHub Flavored Markdown support
     * 
     * @param string $markdown
     * @return string
     */
    function markdown_to_html(string $markdown): string
    {
        $converter = new GithubFlavoredMarkdownConverter([
            'html_input' => 'allow',
            'allow_unsafe_links' => false,
        ]);
        
        return $converter->convert($markdown)->getContent();
    }
}
