<section id="about" class="shell py-20 md:py-28">
  <?php partial('section-head', ['index' => '04', 'title' => 'Кто делает чертежи', 'note' => 'TM']); ?>

  <div class="mt-10 grid gap-12 lg:grid-cols-12 lg:gap-14">
    <div class="lg:col-span-7">
      <div class="reveal">
        <p class="text-[clamp(1.1rem,2vw,1.5rem)] leading-[1.5] tracking-[-0.015em]">
          Проектирую здания с 2012 года: начинал конструктором в
          проектном институте, сейчас веду собственное бюро. Путь от
          расчётов перекрытий до полного цикла — архитектура,
          конструктив, документация, сопровождение стройки.
        </p>
      </div>
      <div class="reveal" style="--d:130ms">
        <p class="mt-6 text-[15.5px] leading-relaxed text-graphite">
          Мой принцип прост: чертёж существует не для экспертизы, а для
          строителя. Поэтому в документации нет случайных линий — каждый
          узел продуман, каждый размер проверен, а спорные места решены на
          бумаге, а не на площадке.
        </p>
      </div>
      <div class="reveal" style="--d:240ms">
        <p class="mt-5 text-[15.5px] leading-relaxed text-graphite">
          Работаю с частными заказчиками, девелоперами и подрядчиками.
          Беру проект целиком или подключаюсь на отдельной стадии —
          например, когда нужен конструктив к чужому архитектурному
          разделу.
        </p>
      </div>
      <div class="reveal" style="--d:340ms">
        <div class="mt-9 flex items-center gap-4">
          <span class="h-px w-14 bg-ink"></span>
          <span class="font-mono text-[11px] uppercase tracking-[0.16em] text-ink">
            Наталья Марактаева · архитектор-конструктор
          </span>
        </div>
      </div>
    </div>

    <div class="lg:col-span-5">
      <div class="reveal reveal--right" style="--d:160ms">
        <div class="border border-rule bg-paper/60 shadow-[-3px_3px_0_0_#dedbd2]">
          <div class="border-b border-rule bg-paper-2/70 px-5 py-3 font-mono text-[10px] uppercase tracking-[0.18em] text-mute">
            Технический лист · данные
          </div>
          <dl>
            <?php foreach (data('specs') as [$key, $value]): ?>
              <div class="grid grid-cols-[92px_1fr] border-b border-rule last:border-b-0 md:grid-cols-[110px_1fr]">
                <dt class="border-r border-rule bg-paper-2/50 px-4 py-3 font-mono text-[10px] uppercase tracking-[0.12em] text-mute">
                  <?= e($key) ?>
                </dt>
                <dd class="px-4 py-3 text-[13.5px] leading-snug text-graphite"><?= e($value) ?></dd>
              </div>
            <?php endforeach; ?>
          </dl>
        </div>
      </div>
    </div>
  </div>
</section>
