export function initHeader() {
  const header = document.querySelector("[data-header]");
  if (!header) return;

  const progress = header.querySelector(".header__progress");
  const burger = header.querySelector("[data-burger]");
  const menu = header.querySelector("[data-menu]");

  const onScroll = () => {
    const y = window.scrollY;
    header.classList.toggle("is-scrolled", y > 8);

    if (progress) {
      const max = document.documentElement.scrollHeight - window.innerHeight;
      const ratio = max > 0 ? Math.min(y / max, 1) : 0;
      progress.style.transform = `scaleX(${ratio})`;
    }
  };

  onScroll();
  window.addEventListener("scroll", onScroll, { passive: true });
  window.addEventListener("resize", onScroll);

  if (burger && menu) {
    const setOpen = (open) => {
      burger.classList.toggle("is-open", open);
      menu.classList.toggle("is-open", open);
      burger.setAttribute("aria-expanded", String(open));
    };

    burger.addEventListener("click", () => {
      setOpen(!menu.classList.contains("is-open"));
    });

    menu.querySelectorAll("[data-menu-link]").forEach((link) => {
      link.addEventListener("click", () => setOpen(false));
    });

    document.addEventListener("keydown", (event) => {
      if (event.key === "Escape") setOpen(false);
    });

    window.addEventListener("resize", () => {
      if (window.innerWidth >= 1024) setOpen(false);
    });
  }
}
