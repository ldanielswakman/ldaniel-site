<?php

Kirby::plugin('ldanielswakman/related-projects', [
  'pageMethods' => [
    'relatedProjects' => function ($limit = 3) {

      $comparedPage = $this;
      $siblings = $this->siblings(false);

      $relatedProjects = array();
      $allMatches = array();

      foreach($siblings as $page) {

        $matches = 0;

        // Comparing tags:
        $sameTags = array_intersect($comparedPage->tags()->split(), $page->tags()->split());
        $matches = $matches + count($sameTags);

        // Comparing titles:
        $sameTitleWords = array_intersect($comparedPage->title()->split(" "), $page->title()->split(" "));
        $matches = $matches + count($sameTitleWords);

        // Comparing descriptions:
        $sameDescriptionWords = array_intersect($comparedPage->description()->split(" "), $page->description()->split(" "));
        $matches = $matches + count($sameDescriptionWords);

        $relatedProjects[] = array(
          "uri" => $page->uri(),
          "matches" => $matches
        );

        $allMatches[] = $matches;

      }

      foreach($relatedProjects as $key => $row) {
        $allMatches[$key] = $row["matches"];
      }
      array_multisort($allMatches, SORT_DESC, $relatedProjects);
      
      $sortedProjects = new Pages;
      for ($i=0; $i < $limit; $i++) { 
        $sortedProjects->add($relatedProjects[$i]["uri"]);
      }

      return $sortedProjects;

    } 
  ]
]);