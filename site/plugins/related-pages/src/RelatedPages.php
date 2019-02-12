<?php

namespace Ldaniel\RelatedPages;

use Kirby\Cms\Page;
use Kirby\Cms\Pages;

class RelatedPages
{
    protected $cache;

    protected $fields = [
        'title',
        'description',
        'tags',
        'text',
    ];

    protected $keywords;

    protected $page;

    protected $parser;

    public function __construct(Page $page)
    {
        $this->cache = kirby()->cache('ldaniel.relatedpages');
        $this->page = $page;
        $this->parser = new Parser();

        $this->parseFieldsOption();

        $this->keywords = $this->extractKeywords($this->page);
    }

    public function get(Pages $pages = null): Pages
    {
        if (is_null($pages)) {
            $pages = $this->page->siblings(false)->listed();
        }

        $relatedPages = [];

        foreach ($pages as $id => $page) {
            $relatedPages[$id] = $this->calculateRelevance($page);
        }

        arsort($relatedPages);

        return new Pages(array_keys($relatedPages));
    }

    protected function calculateRelevance(Page $page)
    {
        $pageKeywords = $this->extractKeywords($page);

        $result = [];

        foreach ($pageKeywords as $field => $words) {
            $weight = $this->fields[$field];
            $totalWords = count($words);
            $matchingWords = count(
                array_intersect($words, $this->keywords[$field])
            );

            $result[] = $matchingWords > 0
                ? $matchingWords / $totalWords * $weight
                : 0;
        }

        return array_sum($result);
    }

    protected function extractKeywords(Page $page): array
    {
        $cacheKey = md5($page->id());

        if ($cached = $this->cache->get($cacheKey)) {
            return $cached;
        }

        $keywordsByField = [];

        foreach ($this->fields as $field => $weight) {
            $fieldName = $field;

            list($field, $subfield) = $this->parseField($field);

            // Ignore fields not set in the page
            if (! $page->$field()->exists()) continue;

            $fieldContent = '';
            $isTagsField = $this->isFieldType($field, 'tags');
            $isStructureField = $this->isFieldType($field, 'structure');

            if ($subfield && $isStructureField) {
                foreach($page->$field()->toStructure() as $entry) {
                    $fieldContent .= ' ' . $entry->$subfield();
                }
            } else {
                $fieldContent = $page->$field();
            }

            $keywordsByField[$fieldName] = $isTagsField
                ? $fieldContent->split(',')
                : $this->parser->keywords((string) $fieldContent);
        }

        $this->cache->set($cacheKey, $keywordsByField);

        return $keywordsByField;
    }

    protected function isFieldType(string $field, string $type): bool
    {
        // Handle "title" field since
        // it's no longer set in the blueprint
        if ('title' === $field && 'title' === $type) return true;

        $blueprint = $this->page->blueprint()->field($field);

        return $blueprint['type'] === $type;
    }

    protected function parseField(string $field): array
    {
        $subfield = null;

        // Handle fields with dot notation: eg. "sections.text"
        if (strpos($field, '.')) {
            list($field, $subfield) = explode('.', $field);
        }

        return [$field, $subfield];
    }

    protected function parseFieldsOption(): void
    {
        $fieldsOption = option('ldaniel.relatedpages.fields') ?? [];
        $template = $this->page->intendedTemplate()->name();

        if (isset($fieldsOption[$template])) {
            $this->fields = [];

            foreach ($fieldsOption[$template] as $field => $weight) {
                // Invert indexed arrays and add default weight
                if (is_int($field)) {
                    $field = $weight;
                    $weight = 1;
                }

                $this->fields[$field] = $weight;
            }
        }
    }
}
