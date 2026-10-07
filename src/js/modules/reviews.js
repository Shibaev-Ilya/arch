import Swiper from "swiper/core";
import { Navigation, Pagination } from "swiper/modules";

const pad = (n) => String(n).padStart(2, "0");

export function initReviews() {
  const root = document.querySelector("[data-reviews]");
  if (!root) return;

  const el = root.querySelector("[data-reviews-swiper]");
  const label = root.querySelector("[data-reviews-label]");
  const total = el.querySelectorAll(".swiper-slide").length;

  const sync = (swiper) => {
    if (!label) return;
    label.innerHTML = `${pad(swiper.realIndex + 1)} <span class="text-rule">/</span> ${pad(total)}`;
  };

  new Swiper(el, {
    modules: [Navigation, Pagination],
    slidesPerView: "auto",
    spaceBetween: 24,
    watchOverflow: true,
    navigation: {
      prevEl: root.querySelector("[data-reviews-prev]"),
      nextEl: root.querySelector("[data-reviews-next]"),
    },
    pagination: {
      el: root.querySelector(".reviews__dots"),
      clickable: true,
    },
    on: {
      init: (s) => sync(s),
      slideChange: (s) => sync(s),
    },
  });
}
