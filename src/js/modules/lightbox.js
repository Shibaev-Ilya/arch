import Swiper from "swiper/core";
import { Navigation } from "swiper/modules";

const pad = (n) => String(n).padStart(2, "0");

export function initLightbox() {
  const triggers = [...document.querySelectorAll("[data-lightbox-open]")];
  const boxes = new Map(
    [...document.querySelectorAll("[data-lightbox]")].map((box) => [
      box.dataset.lightbox,
      box,
    ]),
  );
  if (!triggers.length || !boxes.size) return;

  const instances = new Map();
  let current = null;

  const create = (box) => {
    const counter = box.querySelector("[data-lightbox-counter]");
    const thumbs = [...box.querySelectorAll("[data-lightbox-thumb]")];
    const total = box.querySelectorAll(".swiper-slide").length;

    const sync = (swiper) => {
      const index = swiper.activeIndex;
      if (counter) counter.textContent = `${pad(index + 1)} / ${pad(total)}`;
      thumbs.forEach((thumb, i) =>
        thumb.setAttribute("aria-current", String(i === index)),
      );
    };

    const swiper = new Swiper(box.querySelector("[data-lightbox-swiper]"), {
      modules: [Navigation],
      slidesPerView: 1,
      speed: 450,
      navigation: {
        prevEl: box.querySelector("[data-lightbox-prev]"),
        nextEl: box.querySelector("[data-lightbox-next]"),
      },
      on: {
        init: (s) => sync(s),
        slideChange: (s) => sync(s),
      },
    });

    thumbs.forEach((thumb, i) => {
      thumb.addEventListener("click", () => swiper.slideTo(i));
    });

    box.querySelector(".lightbox__drawing")?.classList.add("is-in");

    return swiper;
  };

  const open = (uid) => {
    const box = boxes.get(uid);
    if (!box) return;

    box.hidden = false;
    box.setAttribute("aria-hidden", "false");
    document.body.classList.add("lightbox-open");

    if (!instances.has(uid)) {
      instances.set(uid, create(box));
    } else {
      instances.get(uid).update();
    }

    current = { box, uid, lastFocus: document.activeElement };
    box.querySelector("[data-lightbox-close]")?.focus();
  };

  const close = () => {
    if (!current) return;
    const { box, lastFocus } = current;

    box.hidden = true;
    box.setAttribute("aria-hidden", "true");
    document.body.classList.remove("lightbox-open");
    current = null;

    if (lastFocus && typeof lastFocus.focus === "function") {
      lastFocus.focus();
    }
  };

  triggers.forEach((trigger) => {
    trigger.addEventListener("click", () =>
      open(trigger.dataset.lightboxOpen),
    );
  });

  document.addEventListener("click", (event) => {
    if (event.target.closest?.("[data-lightbox-close]")) {
      close();
    } else if (event.target.classList?.contains("lightbox__backdrop")) {
      close();
    }
  });

  document.addEventListener("keydown", (event) => {
    if (!current) return;
    const swiper = instances.get(current.uid);
    if (event.key === "Escape") close();
    if (event.key === "ArrowLeft") swiper?.slidePrev();
    if (event.key === "ArrowRight") swiper?.slideNext();
  });
}
