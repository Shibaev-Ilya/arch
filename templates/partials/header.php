<?php

$links = [
    ['href' => '#services', 'label' => 'Услуги'],
    ['href' => '#projects', 'label' => 'Проекты'],
    ['href' => '#process', 'label' => 'Этапы'],
    ['href' => '#about', 'label' => 'Обо мне'],
    ['href' => '#contact', 'label' => 'Контакты'],
];
?>
<header data-header class="fixed inset-x-0 top-0 z-50 transition-colors duration-500">
  <span class="header__progress" aria-hidden="true"></span>

  <div class="shell flex h-[70px] items-center justify-between gap-6">
    <a class="logo" href="#top" aria-label="Начало страницы">
      <span class="logo-mark">MN</span>
      <span class="logo-text">MARAKTAEVA NATALIA</span>
    </a>

    <nav class="hidden items-center gap-8 lg:flex">
      <?php foreach ($links as $link): ?>
        <a
          href="<?= e($link['href']) ?>"
          class="link-underline font-mono text-[11px] uppercase tracking-[0.2em] text-graphite hover:text-ink"
        ><?= e($link['label']) ?></a>
      <?php endforeach; ?>
    </nav>

    <div class="flex items-center gap-3">
      <a
        href="#contact"
        class="hidden h-10 items-center border border-ink px-5 font-mono text-[11px] uppercase tracking-[0.18em] transition-colors hover:bg-ink hover:text-paper sm:inline-flex"
      >Обсудить проект</a>
      <button
        type="button"
        aria-label="Меню"
        aria-expanded="false"
        data-burger
        class="header__burger flex h-10 w-10 flex-col items-center justify-center gap-[5px] border border-ink/30 lg:hidden"
      >
        <span class="header__burger-line block h-px w-4 bg-ink"></span>
        <span class="header__burger-line block h-px w-4 bg-ink"></span>
      </button>
    </div>
  </div>

  <div class="header__menu lg:hidden" data-menu>
    <nav class="shell flex flex-col py-3">
      <?php foreach ($links as $link): ?>
        <a
          href="<?= e($link['href']) ?>"
          data-menu-link
          class="border-b border-rule/60 py-4 font-mono text-[12px] uppercase tracking-[0.2em] text-graphite last:border-b-0"
        ><?= e($link['label']) ?></a>
      <?php endforeach; ?>
    </nav>
  </div>
</header>
