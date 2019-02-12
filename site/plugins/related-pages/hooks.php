<?php

return [
    'page.changeSlug:after' => function ($page) {
        kirby()->cache('ldaniel.relatedpages')
            ->remove(md5($page->id()));
    },
    'page.changeTitle:after' => function ($page) {
        kirby()->cache('ldaniel.relatedpages')
            ->remove(md5($page->id()));
    },
    'page.update:after' => function ($page) {
        kirby()->cache('ldaniel.relatedpages')
            ->remove(md5($page->id()));
    },
    'page.delete:after' => function ($status, $page) {
        kirby()->cache('ldaniel.relatedpages')
            ->remove(md5($page->id()));
    }
];
