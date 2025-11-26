<?php

if (! function_exists('sanitize_html')) {
    /**
     * Sanitize HTML content to prevent XSS attacks
     * Uses HTML Purifier to clean the content
     */
    function sanitize_html($content) {
        if (empty($content)) {
            return $content;
        }

        // Configure HTML Purifier
        $config = \HTMLPurifier_Config::createDefault();

        // Allow common HTML elements that Summernote generates
        $config->set('HTML.Allowed', 'p,br,strong,em,u,strike,sub,sup,blockquote,ol,ul,li,h1,h2,h3,h4,h5,h6,div,span,img,a,table,thead,tbody,tr,th,td,hr,pre,code');

        // Allow specific attributes for elements
        $config->set('HTML.AllowedAttributes', [
            'img.src',
            'img.alt',      // Allow alt attribute for accessibility
            'img.width',
            'img.height',
            'img.class',
            'img.style',    // Allow style attribute for positioning
            'a.href',
            'a.target',
            'a.rel',
            'table.class',
            'tr.class',
            'td.class',
            'td.colspan',
            'td.rowspan',
            'th.class',
            'th.colspan',
            'th.rowspan',
            'div.class',
            'div.style',
            'span.class',
            'span.style',
            '*.class',       // Allow class attribute for all elements
            '*.style',       // Allow style attribute for all elements
            '*.id'           // Allow id attribute
        ]);

        // Allow classes for styling
        $config->set('Attr.AllowedClasses', '*');

        // Allow IDs for anchor links
        $config->set('Attr.EnableID', true);

        // Allow safe protocols
        $config->set('URI.AllowedSchemes', [
            'http' => true,
            'https' => true,
            'mailto' => true,
            'ftp' => true,
            'data' => true,  // For embedded images
        ]);

        // Create purifier instance and clean the content
        $purifier = new \HTMLPurifier($config);
        return $purifier->purify($content);
    }
}

if (! function_exists('sanitizer_config')) {
    /**
     * Get a custom HTML Purifier configuration
     */
    function sanitizer_config($allowed_elements = null, $allowed_attributes = null) {
        $config = \HTMLPurifier_Config::createDefault();
        
        if ($allowed_elements) {
            $config->set('HTML.Allowed', $allowed_elements);
        } else {
            // Default Summernote elements
            $config->set('HTML.Allowed', 'p,br,strong,em,u,strike,sub,sup,blockquote,ol,ul,li,h1,h2,h3,h4,h5,h6,div,span,img[src,alt,width,height,class],a[href,target],table,thead,tbody,tr,th,td[colspan,rowspan],hr,pre,code');
        }
        
        $config->set('Attr.AllowedClasses', '*');
        $config->set('Attr.EnableID', true);
        
        $config->set('URI.AllowedSchemes', [
            'http' => true,
            'https' => true,
            'mailto' => true,
            'ftp' => true,
            'data' => true,
        ]);
        
        return $config;
    }
}