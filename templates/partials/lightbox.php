<?php
/** @var array $project */
/** @var string $uid */

$slides = $project['images'];
?>
<div
  class="lightbox"
  data-lightbox="<?= e($uid) ?>"
  hidden
  aria-hidden="true"
  role="dialog"
  aria-modal="true"
  aria-label="Просмотр проекта: <?= e($project['name']) ?>"
>
  <div class="lightbox__backdrop"></div>

  <div class="lightbox__stage">
    <button type="button" class="lightbox__close" data-lightbox-close aria-label="Закрыть галерею">✕</button>
    <span class="lightbox__counter" data-lightbox-counter aria-live="polite"></span>

    <div class="swiper lightbox__swiper" data-lightbox-swiper>
      <div class="swiper-wrapper">
        <?php foreach ($slides as $i => $image): ?>
          <div class="swiper-slide lightbox__slide">
            <img src="<?= e($image['src']) ?>" alt="<?= e($image['alt']) ?>" loading="lazy" />
          </div>
        <?php endforeach; ?>
      </div>

      <button type="button" class="swiper-button-prev" data-lightbox-prev></button>
      <button type="button" class="swiper-button-next" data-lightbox-next></button>
    </div>

    <div class="lightbox__strip">
      <?php foreach ($slides as $i => $image): ?>
        <button type="button" class="lightbox__thumb" data-lightbox-thumb="<?= $i ?>" aria-label="Кадр <?= $i + 1 ?>" aria-current="<?= $i === 0 ? 'true' : 'false' ?>">
          <img src="<?= e($image['src']) ?>" alt="" loading="lazy" />
        </button>
      <?php endforeach; ?>
    </div>
  </div>
</div>
