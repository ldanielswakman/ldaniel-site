<?php if ($page->related()->count()) : ?>

<section class="bg-greylighter u-pv2">
  <div>
    <p style="margin: 4rem 0 1rem; font-size: 2rem;">related projects</p>
  </div>
  
  <!-- Project Cards -->
  <div class="card-container owl-carousel u-mt1">
    <? foreach ($page->related() as $project) : ?>

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

<?php endif ?>