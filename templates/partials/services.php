<section id="services" class="shell mt-10 pb-20 md:pb-28">
  <?php partial('section-head', ['index' => '01', 'title' => 'Что проектирую', 'note' => 'АР · КР · РД']); ?>

  <div class="mt-10 grid gap-px border border-rule bg-rule md:grid-cols-2 lg:grid-cols-3">
    <?php foreach (data('services') as $i => $service): ?>
      <div class="reveal group bg-paper p-7 transition-colors duration-500 hover:bg-paper-2 md:p-8" style="--d:<?= ($i % 3) * 90 ?>ms">
        <div class="flex items-baseline justify-between">
          <span class="font-mono text-[11px] tracking-[0.18em] text-accent"><?= e($service['code']) ?></span>
          <span class="h-px w-10 bg-rule"></span>
        </div>
        <h3 class="mt-6 text-[19px] font-medium leading-snug tracking-[-0.015em] md:text-[21px]">
          <?= e($service['title']) ?>
        </h3>
        <p class="mt-3 text-[14.5px] leading-relaxed text-graphite"><?= e($service['text']) ?></p>
        <ul class="mt-6 space-y-2.5 border-t border-rule pt-5">
          <?php foreach ($service['items'] as $item): ?>
            <li class="flex gap-3 font-mono text-[11px] uppercase tracking-[0.06em] text-mute">
              <span class="text-accent">—</span>
              <?= e($item) ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endforeach; ?>
  </div>
</section>
