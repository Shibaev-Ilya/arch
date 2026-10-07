<section id="reviews" class="shell pb-20 md:pb-28">
  <?php partial('section-head', ['index' => '05', 'title' => 'Отзывы', 'note' => 'Заказчики · подрядчики']); ?>

  <div class="reveal" style="--d:80ms">
    <p class="mt-8 max-w-2xl text-[15.5px] leading-relaxed text-graphite">
      Что говорят заказчики, генподрядчики и инженеры, с которыми
      работали по проекту — от частного дома до школы на 1100 мест.
    </p>
  </div>

  <div class="mt-7" role="region" aria-label="Отзывы клиентов" data-reviews>
    <div class="flex items-center justify-between gap-4 border-b border-rule pb-4">
      <span aria-live="polite" class="font-mono text-[11px] tabular-nums tracking-[0.18em] text-mute" data-reviews-label>
        01 <span class="text-rule">/</span> <?= pad2(count(data('reviews'))) ?>
      </span>
      <div class="flex items-center gap-2">
        <button
          type="button"
          aria-label="Предыдущий отзыв"
          data-reviews-prev
          class="flex h-10 w-10 items-center cursor-pointer justify-center border border-ink/30 font-mono text-[13px] leading-none text-graphite transition-colors hover:border-ink hover:bg-ink hover:text-paper disabled:pointer-events-none disabled:opacity-25"
        ><span aria-hidden="true">←</span></button>
        <button
          type="button"
          aria-label="Следующий отзыв"
          data-reviews-next
          class="flex h-10 w-10 items-center cursor-pointer justify-center border border-ink/30 font-mono text-[13px] leading-none text-graphite transition-colors hover:border-ink hover:bg-ink hover:text-paper disabled:pointer-events-none disabled:opacity-25"
        ><span aria-hidden="true">→</span></button>
      </div>
    </div>

    <div class="swiper reviews__swiper" data-reviews-swiper tabindex="0">
      <div class="swiper-wrapper">
        <?php foreach (data('reviews') as $i => $review): ?>
          <article class="swiper-slide h-auto flex w-[86%] shrink-0 flex-col border border-rule bg-paper p-6 transition-colors duration-500 md:p-8 sm:w-[calc((100%-24px)/2)] lg:w-[calc((100%-48px)/3)] shadow-[-3px_3px_0_0_#dedbd2]">
            <div class="flex items-center justify-between font-mono text-[10px] uppercase tracking-[0.18em] text-mute">
              <span>Отзыв <?= pad2($i + 1) ?></span>
              <span class="text-accent"><?= e($review['code']) ?></span>
            </div>

            <p class="mt-6 text-[16.5px] leading-[1.6] tracking-[-0.01em] md:text-[17.5px]">
              <span class="text-accent">«</span><?= e($review['quote']) ?><span class="text-accent">»</span>
            </p>

            <div class="mt-auto pt-8">
              <div class="h-px w-12 bg-ink"></div>
              <div class="mt-4 font-mono text-[11px] uppercase tracking-[0.16em] text-ink"><?= e($review['name']) ?></div>
              <div class="mt-2 text-[13.5px] leading-snug text-graphite"><?= e($review['role']) ?></div>
              <div class="mt-1 font-mono text-[10px] uppercase leading-relaxed tracking-[0.14em] text-mute">
                <?= e($review['project']) ?> · <?= e($review['year']) ?>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>

      <div class="swiper-pagination reviews__dots"></div>
    </div>
  </div>
</section>
