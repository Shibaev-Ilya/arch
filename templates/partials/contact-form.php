<?php

$sent = isset($_GET['sent']);
$error = trim((string) ($_GET['error'] ?? ''));
$field = 'w-full border border-rule bg-paper px-4 py-3 text-[15px] text-ink outline-none transition-colors placeholder:text-mute focus:border-ink';
?>
<div
  class="flex h-full min-h-[340px] flex-col items-start justify-center border border-ink bg-paper-2/60 p-8 md:p-10"
  data-form-success
  <?= $sent ? '' : 'hidden' ?>
>
  <span class="font-mono text-[11px] uppercase tracking-[0.24em] text-accent">Заявка принята</span>
  <p class="mt-5 text-[22px] leading-snug tracking-tight md:text-[26px]">
    Спасибо. Свяжусь с вами в течение рабочего дня, чтобы уточнить
    исходные данные проекта.
  </p>
  <button
    type="button"
    data-form-reset
    class="mt-8 font-mono text-[11px] uppercase tracking-[0.2em] text-graphite underline underline-offset-4 hover:text-ink"
  >Отправить ещё одну заявку</button>
</div>

<form
  data-form
  action="/index.php?action=form"
  method="post"
  class="border border-rule bg-paper-2/50 p-6 md:p-8"
  <?= $sent ? 'hidden' : '' ?>
>
  <div class="grid gap-4">
    <label class="block">
      <span class="mb-2 block font-mono text-[10px] uppercase tracking-[0.2em] text-mute">01 · Имя</span>
      <input required name="name" autocomplete="name" placeholder="Как к вам обращаться" class="<?= e($field) ?>" />
    </label>
    <label class="block">
      <span class="mb-2 block font-mono text-[10px] uppercase tracking-[0.2em] text-mute">02 · Телефон или e-mail</span>
      <input required name="contact" placeholder="+7 (___) ___-__-__" class="<?= e($field) ?>" />
    </label>
    <label class="block">
      <span class="mb-2 block font-mono text-[10px] uppercase tracking-[0.2em] text-mute">03 · Задача</span>
      <textarea name="task" rows="4" placeholder="Тип объекта, площадь, стадия, сроки" class="<?= e($field) ?> resize-none"></textarea>
    </label>
    <input type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true" class="hidden" />
  </div>

  <p role="alert" data-form-error class="mt-4 border border-ink px-4 py-3 font-mono text-[11px] leading-relaxed uppercase tracking-[0.16em] text-ink" <?= $error !== '' ? '' : 'hidden' ?>>
    <?= e($error) ?>
  </p>

  <button
    type="submit"
    data-form-submit
    class="mt-6 cursor-pointer flex h-12 w-full items-center justify-center border border-ink bg-ink px-6 font-mono text-[11px] uppercase tracking-[0.2em] text-paper transition-colors hover:bg-transparent hover:text-ink disabled:cursor-wait disabled:opacity-60"
  >Отправить заявку</button>

  <p class="mt-4 font-mono text-[10px] leading-relaxed text-mute">
    Отвечаю в течение рабочего дня. NDA — по запросу.
  </p>
</form>
