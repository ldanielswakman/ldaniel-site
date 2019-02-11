<?php

namespace Ldaniel\RelatedPages;

use StopWordFactory;
use TextAnalysis\Documents\TokensDocument;
use TextAnalysis\Filters;
use TextAnalysis\Tokenizers\WhitespaceTokenizer;

class Parser
{
    protected $tokenFilters = [];

    protected $contentFilters = [];

    public function keywords(string $content): array
    {
        foreach ($this->getContentFilters() as $contentFilter) {
            $content = $contentFilter->transform($content);
        }

        return $this->getKeywords($content);
    }

    protected function getContentFilters(): array
    {
        // TODO: extract to it's own method
        $removeNonPrintableChars = function($word) {
            return preg_replace('/[\x00-\x1F\x80-\xFF]/u', ' ', $word);
        };

        // TODO: extract to it's own method
        $removeDashes = function($word) {
            return preg_replace('/[^\x8211-\x8212]/u', ' ', $word);
        };

        // TODO: add lambda filter for Kirby Tags

        $this->contentFilters = [
            new Filters\StripTagsFilter(),
            new Filters\LowerCaseFilter(),
            new Filters\NumbersFilter(),
            new Filters\EmailFilter(),
            new Filters\UrlFilter(),
            new Filters\PossessiveNounFilter(),
            new Filters\QuotesFilter(),
            new Filters\PunctuationFilter(),
            new Filters\CharFilter(),
            new Filters\LambdaFilter($removeNonPrintableChars),
            new Filters\LambdaFilter($removeDashes),
            new Filters\WhitespaceFilter()
        ];

        return $this->contentFilters;
    }

    protected function getTokenFilters(): array
    {
        if (empty($this->tokenFilters)) {
            $stopwords = StopWordFactory::get('stop-words_english_6_en.txt');

            $this->tokenFilters = [
                new Filters\StopWordsFilter($stopwords)
            ];
        }

        return $this->tokenFilters;
    }

    protected function getKeywords(string $content): array
    {

        $tokens = (new WhitespaceTokenizer())->tokenize($content);
        $document = new TokensDocument(array_map('strval', $tokens));

        foreach ($this->getTokenFilters() as $filter) {
            $document->applyTransformation($filter);
        }

        return $document->getDocumentData();
    }
}
