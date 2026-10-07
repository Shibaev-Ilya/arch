<?php
/** @var array $project */
/** @var string $uid */

$cover = $project['images'][0];
$total = count($project['images']) + 1;
?>
<article class="group h-full border border-rule bg-paper p-3 transition-colors duration-500 shadow-[-3px_3px_0_0_#dedbd2]">
  <button
    type="button"
    data-lightbox-open="<?= e($uid) ?>"
    aria-label="Открыть галерею проекта <?= e($project['name']) ?>"
    class="relative block w-full cursor-zoom-in overflow-hidden border border-rule/70 bg-paper-2 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent"
  >
    <img
      src="<?= e($cover['src']) ?>"
      alt="<?= e($cover['alt']) ?>"
      width="1200"
      height="750"
      loading="lazy"
      class="aspect-[16/10] w-full object-cover transition-transform duration-700 ease-out group-hover:scale-[1.04]"
    />
    <span class="pointer-events-none absolute right-3 top-3 bg-paper/90 px-2 py-1 font-mono text-[10px] tracking-[0.14em] text-ink">
      <?= e($project['code']) ?>
    </span>
    <span class="pointer-events-none absolute bottom-3 left-3 flex items-center gap-2 border border-ink/25 bg-paper/90 px-2.5 py-1.5 font-mono text-[10px] uppercase tracking-[0.16em] text-ink">
      <span aria-hidden="true">⛶</span>
      <?= pad2($total) ?> фото
    </span>
  </button>

  <div class="px-2 pb-1 pt-5">
    <div class="flex items-center justify-between font-mono text-[10px] uppercase tracking-[0.16em] text-mute">
      <span><?= e($project['type']) ?></span>
      <span><?= e($project['year']) ?></span>
    </div>
    <h3 class="mt-3 text-[18px] font-medium leading-snug tracking-[-0.015em]"><?= e($project['name']) ?></h3>
    <p class="mt-3 text-[14px] leading-relaxed text-graphite"><?= e($project['description']) ?></p>
    <div class="mt-4 flex flex-wrap items-baseline gap-x-5 gap-y-1 border-t border-rule pt-4 font-mono text-[11px] text-graphite">
      <span><?= e($project['area']) ?></span>
      <span class="text-mute"><?= e($project['system']) ?></span>
    </div>
  </div>

  <?php partial('lightbox', ['project' => $project, 'uid' => $uid]); ?>
</article>
