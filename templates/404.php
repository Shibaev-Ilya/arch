<?php

$title = 'Страница не найдена — arch.local';
http_response_code(404);

?>
<div class="shell flex min-h-[72vh] flex-col items-start justify-center py-32">
  <span class="font-mono text-[11px] uppercase tracking-[0.24em] text-accent">Ошибка 404</span>
  <h1 class="mt-6 text-[clamp(2rem,5vw,3.6rem)] font-medium leading-[1.05] tracking-[-0.04em]">
    Такой страницы<br>на плане нет
  </h1>
  <p class="mt-5 max-w-md text-[15.5px] leading-relaxed text-graphite">
    Проверьте адрес или вернитесь на главную — там портфолио, услуги,
    этапы работы и контакты.
  </p>
  <a
    href="/"
    class="mt-8 inline-flex h-12 items-center border border-ink bg-ink px-7 font-mono text-[11px] uppercase tracking-[0.18em] text-paper transition-colors hover:bg-transparent hover:text-ink"
  >На главную</a>
</div>
