<?php

@include_once __DIR__ . '/vendor/autoload.php';

use Kirby\Cms\Pages;
Kirby::plugin('ldaniel/relatedpages', [
    'options' => [
        'limit' => 3
    ],
    'pageMethods' => [
        'related' => function (int $limit = 0): Pages {
            $limit = $limit > 0 ? $limit : option('ldaniel.relatedpages.limit');

            return $this->siblings()
                ->not($this->nextVisible())
                ->shuffle()
                ->limit($limit);
        }
    ]
]);
