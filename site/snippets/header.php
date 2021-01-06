<!DOCTYPE html>
<html lang="en">

<?php snippet('head') ?>

<body class="<?= $page->template() ?>">

  <a href="<?= ($page->url() != $site->url()) ? $site->url() : '#top' ?>" class="logo logo--init">
    <?php snippet('svg/logo') ?>
  </a>

  <?php snippet('nav') ?>

  <?php snippet('migrate-modal') ?>

  <?php snippet('contact', ['page' => $page]) ?>
  <?php if(isset($contact_active) && $contact_active == true) : ?>
    <script>openContactForm()</script>
  <?php endif ?>
