<section class="shell py-16 md:py-17">
  <div class="grid grid-cols-2 gap-2 lg:grid-cols-4">
    <?php foreach (data('stats') as $stat): ?>
      <div class="reveal bg-paper px-5 py-7 md:px-7 md:py-9 border border-rule shadow-[-3px_3px_0_0_#dedbd2]" style="--d:90ms">
        <div class="text-[clamp(1.5rem,4.2vw,3.2rem)] font-medium leading-none tracking-[-0.04em] tabular-nums">
          <span data-counter="<?= (int) $stat['value'] ?>"><?= str_replace(' ', "\u{00A0}", number_format((int) $stat['value'], 0, '', ' ')) ?></span><?= e($stat['suffix']) ?>
        </div>
        <div class="mt-4 font-mono text-[10px] uppercase leading-relaxed tracking-[0.16em] text-mute">
          <?= e($stat['label']) ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>
