<section id="process" class="border-y border-ink/15 bg-paper-2/40">
  <div class="shell py-20 md:py-28">
    <?php partial('section-head', ['index' => '03', 'title' => 'Как идёт проект', 'note' => '5 стадий']); ?>

    <div class="mt-12 grid gap-10 md:grid-cols-2 lg:grid-cols-5 lg:gap-6">
      <?php foreach (data('steps') as $i => $step): ?>
        <div class="reveal relative" style="--d:<?= $i * 110 ?>ms">
          <div class="flex items-center gap-3">
            <span class="flex h-9 w-9 shrink-0 items-center justify-center border border-ink font-mono text-[11px] tracking-[0.08em]">
              <?= e($step['n']) ?>
            </span>
            <span class="h-px flex-1 bg-rule"></span>
          </div>
          <h3 class="mt-5 text-[17px] font-medium tracking-[-0.01em]"><?= e($step['title']) ?></h3>
          <p class="mt-2 text-[14px] leading-relaxed text-graphite"><?= e($step['text']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
