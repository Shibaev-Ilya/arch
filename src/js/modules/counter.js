const formatter = new Intl.NumberFormat("ru-RU");

const reduced = () =>
  window.matchMedia("(prefers-reduced-motion: reduce)").matches;

export function initCounters() {
  const nodes = [...document.querySelectorAll("[data-counter]")];
  if (!nodes.length || reduced() || !("IntersectionObserver" in window)) {
    return;
  }

  const animate = (node) => {
    const target = Number(node.dataset.counter || 0);
    if (!Number.isFinite(target)) return;

    const duration = 1400;
    const start = performance.now();

    const tick = (now) => {
      const t = Math.min((now - start) / duration, 1);
      const eased = 1 - Math.pow(1 - t, 3);
      node.textContent = formatter.format(Math.round(target * eased));
      if (t < 1) requestAnimationFrame(tick);
    };

    requestAnimationFrame(tick);
  };

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        animate(entry.target);
        observer.unobserve(entry.target);
      });
    },
    { threshold: 0.4 },
  );

  nodes.forEach((node) => observer.observe(node));
}
