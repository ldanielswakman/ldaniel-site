<?php snippet('header') ?>

  <main>

    <section class="featured bg-darkBlue">

      <div class="row u-pv60">
        <div class="col-md-8 col-md-offset-2">

          <div class="text">
            <p><big><em>
              Currently I'm working on creating a <a href="javascript:void(0)">local governance platform</a> and redesigning the identity of a <a href="javascript:void(0)">chef website</a>. Recently I've worked on:
            </em></big></p>
            <?php // echo $page->text()->kirbytext() ?>
          </div>

        </div>
      </div>

      <div class="row row-nopadding row-full">
        <?php snippet('featured'); ?>
      </div>

    </section>

    <section id="projects">

      <div class="row">
        <div class="col-xs-12">

          <h3 class="u-mb20">
            projects

            <span class="filter-container">
              &rarr;
              <select id="project_filter" data-base-url="<?php echo $page->url(); ?>">
                <?php
                // build a list of available tags in work subpages
                $tag_list = $page->children()->visible()->pluck('tags', ',', true);
                array_unshift($tag_list, 'all');
                foreach($tag_list as $tag):
                  $selected = ($tag == urldecode(param('tag'))) ? ' selected' : '';
                  echo '<option' . $selected . '>' . $tag . '</option>';
                endforeach;
                ?>
              </select>
            </span>
            <script>
              $('#project_filter').change(function() {
                $urlpath = ($(this).val() === 'all') ? '' : '/tag:' + $(this).val();
                window.location.href = $(this).attr('data-base-url') + $urlpath + '#projects';
              });
            </script>

            <?php if($tag = param('tag')) : ?>
            <a href="<?php echo $page->url() . '#projects' ?>" class="u-ml10">
              <i class="ion ion-android-close"></i>
            </a>
            <?php endif; ?>

          </h3>


        </div>
      </div>

      <div class="row project-container">
        <?php 
        $projects = $page->children()->visible()->sortBy('year', 'desc');
        if($tag = param('tag')) {
          $projects = $projects->filterBy('tags', urldecode($tag), ',');
        }
        foreach($projects as $project): ?>
        <div class="col-md-4 project u-pv40">
          <a href="<?php echo $project->url() ?>" title="<?php echo $project->title()->html() ?>" class="u-inlineblock">

            <?php if ($img = $project->featuredimage()): ?>
              <div class="project-teaser u-mb20" style="background-image: url('<?php echo $project->url() . '/' . $img ?>')"></div>
            <?php endif; ?>

            <h4 class="project-title u-mb10"><?php echo $project->title()->html() ?></h4>
            <p class="meta"><?php echo $project->description() ?></p>

          </a>
          <p class="meta">
            <small>
              <?php snippet('project_tags', array('page' => $project) ); ?>
            </small>
          </p>
        </div>
        <?php endforeach; ?>
      </div>

    </section>

  </main>

<?php snippet('footer') ?>