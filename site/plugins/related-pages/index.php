<?php

@include_once __DIR__ . '/vendor/autoload.php';

use Kirby\Cms\Pages;
use Ldaniel\RelatedPages\RelatedPages;

Kirby::plugin('ldaniel/relatedpages', [
    'hooks' => require_once __DIR__ . '/hooks.php',
    'options' => [
        'cache' => true,
        'limit' => 3
    ],
    'pageMethods' => [
        'related' => function (int $limit = 0): Pages {
            $limit = $limit > 0 ? $limit : option('ldaniel.relatedpages.limit');
            $siblings = $this->siblings(false)->not($this->nextVisible());
            $relatedPages = new RelatedPages($this);

            return $relatedPages->get($siblings)->limit($limit);
        }
    ]
]);
