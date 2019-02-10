<?
  
  /**
   * Helper function to prepare an array of values for text comparison
   * @param array
   * @return array
   */
  function normalizeWords($words) {
    $result = [];
    foreach ($words as $word) {
      if (str::length($word) <= 3) continue; // Discard short words (the, and, by, ...)
      $result[] = str::slug($word); // Slugify the word to remove case sensitivity and punctuation
    }
    return $result;
  }

  // Get current page data
  $page_tags = $page->tags()->split();
  $page_words = normalizeWords(array_merge($page->title()->split(' '), $page->description()->split(' ')));

  // Prepare related pages
  $related_pages = [];

  // Loop through visible sibling pages
  foreach ($page->siblings()->visible() as $sibling) {
    
    // Don't compare the current page with itself
    if ($page->is($sibling)) continue;

    // Get words from the title and description
    $sibling_words = normalizeWords(array_merge($sibling->title()->split(' '), $sibling->description()->split(' ')));

    // Compare tags and words with the current page, and calculate relevance score
    $common_tags = count(array_intersect(array_map('strtolower', $page_tags), array_map('strtolower', $sibling->tags()->split())));
    $common_words = count(array_intersect(array_map('strtolower', $page_words), array_map('strtolower', $sibling_words)));
    $relevance = $common_tags + $common_words;

    // Add sibling to related pages list, using the relevance score as the array key
    $related_pages[$relevance.'_'.$sibling->uid()] = $sibling;
  }

  // Get the three highest-relevance siblings
  krsort($related_pages);
  $related_pages = array_slice($related_pages, 0, 3);
?>

<section class="bg-greylighter u-pv2">
  <div>
    <p style="margin: 4rem 0 1rem; font-size: 2rem;">related projects</p>
  </div>
  
  <!-- Project Cards -->
  <div class="card-container owl-carousel u-mt1">
    <? foreach ($related_pages as $project) : ?>

      <a href="<?php echo $project->url() ?>" class="card card--related">
        <? if($image = $project->featuredimage()->toFile()) : ?>
          <figure>
            <img src="<?= $image->thumb(['width' => 800])->url() ?>" alt="">
          </figure>
        <? endif ?>
      </a>

    <? endforeach ?>

  </div>

  <a href="<?= $page->parent()->url() ?>">
      <div class="row cta u-pv1 u-mt2">
          <div class="col-md-4">
            <i class="ion ion-ios-arrow-thin-left ion-2x u-floatleft u-mr10"></i>
            <span class="u-inlineblock u-floatleft u-pt5">
              <? if($next = $page->nextVisible()) { echo 'or, '; } ?>
              get back to work
            </span>
          </div>
      </div>
  </a>

</section>