<?php
/** @var string $index */
/** @var string $title */
/** @var string $note */
?>
<div class="reveal">
  <div class="flex items-end justify-between gap-6 border-b border-ink/25 pb-5">
    <div class="flex items-baseline gap-4 md:gap-6">
      <span class="font-mono text-[11px] tracking-[0.22em] text-accent"><?= e($index) ?></span>
      <h2 class="text-[clamp(1.7rem,3.6vw,2.9rem)] font-medium leading-none tracking-[-0.035em]">
        <?= e($title) ?>
      </h2>
    </div>
    <span class="hidden shrink-0 font-mono text-[10px] uppercase tracking-[0.18em] text-mute sm:block">
      <?= e($note) ?>
    </span>
  </div>
</div>
