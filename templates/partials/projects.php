<section id="projects" class="shell pb-20 md:pb-28">
  <?php partial('section-head', ['index' => '02', 'title' => 'Избранные работы', 'note' => '2022 — 2025']); ?>

  <div class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3 lg:gap-8">
    <?php foreach (data('projects') as $i => $project): ?>
      <div class="reveal" style="--d:<?= ($i % 3) * 110 ?>ms">
        <?php partial('project-card', ['project' => $project, 'uid' => 'p' . $i]); ?>
      </div>
    <?php endforeach; ?>
  </div>
</section>
