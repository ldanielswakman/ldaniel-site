<?php

@include_once __DIR__ . '/vendor/autoload.php';

Kirby::plugin('ldaniel/related-pages', [
    'pageMethods' => [
        'related' => function () {
            return $this->siblings()->not($this->nextVisible())->shuffle()->limit(3);
        }
    ]
]);
