<section id="contact" class="border-t border-ink/15 bg-paper-2/40">
  <div class="shell grid gap-12 py-20 md:py-28 lg:grid-cols-12 lg:gap-14">
    <div class="lg:col-span-6">
      <div class="reveal">
        <p class="font-mono text-[11px] uppercase tracking-[0.24em] text-accent">06 · Контакты</p>
        <h2 class="mt-6 text-[clamp(2rem,4.6vw,3.4rem)] font-medium leading-[1.02] tracking-[-0.035em]">
          <span class="line-mask"><span class="line-in" style="--d:60ms">Расскажите</span></span>
          <span class="line-mask"><span class="line-in" style="--d:180ms">об объекте</span></span>
        </h2>
      </div>

      <div class="reveal" style="--d:300ms">
        <p class="mt-6 max-w-md text-[15.5px] leading-relaxed text-graphite">
          Пришлите план участка или эскиз — в ответ дам предварительную
          оценку: состав разделов, сроки и стоимость проектирования.
        </p>
      </div>

      <div class="reveal" style="--d:400ms">
        <ul class="mt-10 border-t border-rule">
          <?php foreach (data('contacts') as $contact): ?>
            <li class="grid grid-cols-[86px_1fr] items-baseline gap-4 border-b border-rule py-4 md:grid-cols-[110px_1fr]">
              <span class="font-mono text-[10px] uppercase tracking-[0.16em] text-mute"><?= e($contact['label']) ?></span>
              <?php if ($contact['href'] !== null): ?>
                <a href="<?= e($contact['href']) ?>" class="link-underline w-fit text-[16px] tracking-[-0.01em] text-ink md:text-[18px]">
                  <?= e($contact['value']) ?>
                </a>
              <?php else: ?>
                <span class="text-[16px] tracking-[-0.01em] text-ink md:text-[18px]"><?= e($contact['value']) ?></span>
              <?php endif; ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>

    <div class="lg:col-span-6">
      <div class="reveal reveal--right" style="--d:180ms">
        <?php partial('contact-form'); ?>
      </div>
    </div>
  </div>
</section>
