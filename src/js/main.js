import "../styles/tailwind.css";
import "../styles/main.scss";

import { initHeader } from "./modules/header.js";
import { initReveal } from "./modules/reveal.js";
import { initCounters } from "./modules/counter.js";
import { initLightbox } from "./modules/lightbox.js";
import { initReviews } from "./modules/reviews.js";
import { initForm } from "./modules/form.js";

initHeader();
initReveal();
initCounters();
initLightbox();
initReviews();
initForm();
