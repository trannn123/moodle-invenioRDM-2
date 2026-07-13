<?php

namespace mod_invenioresource\service;

defined('MOODLE_INTERNAL') || die();

use cache;

class keyword_service
{
    public function increase_keyword(string $keyword): void
    {
        $keyword = trim(
            mb_strtolower($keyword)
        );

        if ($keyword === '') {
            return;
        }

        $cache = cache::make(
            'mod_invenioresource',
            'popular_keywords'
        );

        $keywords = $cache->get('keywords');

        if ($keywords === false) {
            $keywords = [];
        }

        if (isset($keywords[$keyword])) {
            $keywords[$keyword]++;
        } else {
            $keywords[$keyword] = 1;
        }

        $cache->set(
            'keywords',
            $keywords
        );
    }

}