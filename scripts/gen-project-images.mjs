import { mkdirSync, writeFileSync } from "node:fs";
import { dirname, join } from "node:path";
import { fileURLToPath } from "node:url";

const ROOT = join(dirname(fileURLToPath(import.meta.url)), "..");
const OUT = join(ROOT, "projects");

const INK = "#14171c";
const GRAPHITE = "#474c54";
const MUTE = "#8a8f97";
const RULE = "#dedbd2";
const PAPER = "#f5f3ef";
const PAPER2 = "#e9e6de";
const ACCENT = "#1d47cf";

const W = 1200;
const H = 750;
const BASE = 694;

const PROJECTS = [
  {
    slug: "granit",
    code: "ЧД-04/25",
    name: "Коттедж «Гранит»",
    shape: "house",
    frameTitles: ["ЭКСТЕРЬЕР · ЮЖНЫЙ ФАСАД", "ФАСАД · ФРАГМЕНТ 1:50", "ГЕНПЛАН УЧАСТКА"],
    note: "МОНОЛИТ · ГАЗОБЕТОН",
    area: "340 м²",
  },
  {
    slug: "severny",
    code: "МК-12/24",
    name: "ЖК «Северный»",
    shape: "block",
    frameTitles: ["ЭКСТЕРЬЕР · ВИД С ЮГА", "ФАСАД · ФРАГМЕНТ 1:50", "СХЕМА КВАРТАЛА"],
    note: "МОНОЛИТНЫЙ КАРКАС",
    area: "12 400 м²",
  },
  {
    slug: "meridian",
    code: "БЦ-03/24",
    name: "БЦ «Меридиан»",
    shape: "office",
    frameTitles: ["ЭКСТЕРЬЕР · ГЛАВНЫЙ ВХОД", "ФАСАД · ФРАГМЕНТ 1:50", "СХЕМА УЧАСТКА"],
    note: "КАРКАС · КЕРАМОГРАНИТ",
    area: "8 900 м²",
  },
  {
    slug: "warehouse",
    code: "ПР-07/23",
    name: "Реконструкция склада",
    shape: "warehouse",
    frameTitles: ["ЭКСТЕРЬЕР · АНГАРЫ", "ФАСАД · ФРАГМЕНТ 1:50", "ПЛАН ТЕРРИТОРИИ"],
    note: "МЕТАЛЛОКАРКАС",
    area: "3 200 м²",
  },
  {
    slug: "sosna",
    code: "ЧД-21/23",
    name: "Дом «Сосна»",
    shape: "timber",
    frameTitles: ["ЭКСТЕРЬЕР · ВИД С ЗАПАДА", "ФАСАД · ФРАГМЕНТ 1:50", "ГЕНПЛАН УЧАСТКА"],
    note: "КЛЕЁНЫЙ БРУС",
    area: "210 м²",
  },
  {
    slug: "school",
    code: "ШК-09/22",
    name: "Школа на 1100 мест",
    shape: "school",
    frameTitles: ["ЭКСТЕРЬЕР · ГЛАВНЫЙ ФАСАД", "ФАСАД · ФРАГМЕНТ 1:50", "ГЕНПЛАН ТЕРРИТОРИИ"],
    note: "КАРКАС · КИРПИЧ",
    area: "9 600 м²",
  },
];

const frame = ({ project, index, body }) => `<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" width="${W}" height="${H}" viewBox="0 0 ${W} ${H}" font-family="ui-monospace, Menlo, Consolas, monospace">
  <defs>
    <linearGradient id="sky" x1="0" y1="0" x2="0" y2="1">
      <stop offset="0" stop-color="${PAPER}"/>
      <stop offset="1" stop-color="${PAPER2}"/>
    </linearGradient>
    <pattern id="grid" width="100" height="100" patternUnits="userSpaceOnUse">
      <path d="M100 0H0V100" fill="none" stroke="${RULE}" stroke-width="1"/>
    </pattern>
  </defs>
  <rect width="${W}" height="${H}" fill="${PAPER}"/>
  <rect width="${W}" height="${BASE}" fill="url(#grid)" opacity="0.55"/>
  <text x="24" y="42" fill="${MUTE}" font-size="13" letter-spacing="3">КАДР 0${index + 1} · ${project.frameTitles[index]}</text>
  <text x="1176" y="42" fill="${ACCENT}" font-size="13" letter-spacing="3" text-anchor="end">${project.code}</text>
  ${body}
  <rect x="0" y="${BASE}" width="${W}" height="${H - BASE}" fill="${PAPER2}"/>
  <line x1="0" y1="${BASE}" x2="${W}" y2="${BASE}" stroke="${INK}" stroke-width="1.5"/>
  <text x="24" y="${BASE + 24}" fill="${INK}" font-size="13" letter-spacing="2">${project.name}</text>
  <text x="24" y="${BASE + 44}" fill="${MUTE}" font-size="11" letter-spacing="2">${project.note} · ${project.area}</text>
  <text x="1176" y="${BASE + 24}" fill="${GRAPHITE}" font-size="12" letter-spacing="2" text-anchor="end">БЮРО «ОСЬ» · ${project.code}</text>
  <text x="1176" y="${BASE + 44}" fill="${MUTE}" font-size="11" letter-spacing="2" text-anchor="end">ЛИСТ 0${index + 1} · М 1:100</text>
</svg>
`;

const windowCell = (x, y, w, h, opts = {}) => {
  const { mullion = false, pane = PAPER2 } = opts;
  const parts = [
    `<rect x="${x}" y="${y}" width="${w}" height="${h}" fill="${pane}" stroke="${INK}" stroke-width="1.4"/>`,
    `<line x1="${x}" y1="${y + h / 2}" x2="${x + w}" y2="${y + h / 2}" stroke="${INK}" stroke-width="0.9" opacity="0.55"/>`,
  ];
  if (mullion) {
    parts.push(`<line x1="${x + w / 2}" y1="${y}" x2="${x + w / 2}" y2="${y + h}" stroke="${ACCENT}" stroke-width="1.1"/>`);
  }
  return parts.join("");
};

const tree = (x, y, r) =>
  `<g stroke="${INK}" fill="none" stroke-width="1.3">
    <line x1="${x}" y1="${y}" x2="${x}" y2="${y + r * 1.5}"/>
    <circle cx="${x}" cy="${y - r * 0.4}" r="${r}" fill="${PAPER2}"/>
    <circle cx="${x - r * 0.5}" cy="${y + r * 0.3}" r="${r * 0.62}" fill="${PAPER2}"/>
    <circle cx="${x + r * 0.5}" cy="${y + r * 0.35}" r="${r * 0.58}" fill="${PAPER2}"/>
  </g>`;

const dimH = (x1, x2, y, label) =>
  `<g stroke="${GRAPHITE}" stroke-width="1" fill="${GRAPHITE}" font-size="12" letter-spacing="1">
    <line x1="${x1}" y1="${y - 7}" x2="${x1}" y2="${y + 7}"/>
    <line x1="${x2}" y1="${y - 7}" x2="${x2}" y2="${y + 7}"/>
    <line x1="${x1}" y1="${y}" x2="${x2}" y2="${y}"/>
    <path d="M${x1} ${y}l9 -4v8z" fill="${GRAPHITE}" stroke="none"/>
    <path d="M${x2} ${y}l-9 -4v8z" fill="${GRAPHITE}" stroke="none"/>
    <rect x="${(x1 + x2) / 2 - 34}" y="${y - 11}" width="68" height="22" fill="${PAPER}" stroke="none"/>
    <text x="${(x1 + x2) / 2}" y="${y + 5}" text-anchor="middle" stroke="none">${label}</text>
  </g>`;

const dimV = (y1, y2, x, label) =>
  `<g stroke="${GRAPHITE}" stroke-width="1" fill="${GRAPHITE}" font-size="12" letter-spacing="1">
    <line x1="${x - 7}" y1="${y1}" x2="${x + 7}" y2="${y1}"/>
    <line x1="${x - 7}" y1="${y2}" x2="${x + 7}" y2="${y2}"/>
    <line x1="${x}" y1="${y1}" x2="${x}" y2="${y2}"/>
    <path d="M${x} ${y1}l-4 9h8z" fill="${GRAPHITE}" stroke="none"/>
    <path d="M${x} ${y2}l-4 -9h8z" fill="${GRAPHITE}" stroke="none"/>
    <rect x="${x - 26}" y="${(y1 + y2) / 2 - 11}" width="52" height="22" fill="${PAPER}" stroke="none"/>
    <text x="${x}" y="${(y1 + y2) / 2 + 5}" text-anchor="middle" stroke="none">${label}</text>
  </g>`;

const leader = (x1, y1, x2, y2, label) =>
  `<g stroke="${ACCENT}" stroke-width="1" fill="${ACCENT}" font-size="12" letter-spacing="1">
    <line x1="${x1}" y1="${y1}" x2="${x2}" y2="${y2}"/>
    <circle cx="${x1}" cy="${y1}" r="3" fill="${PAPER}" stroke="${ACCENT}"/>
    <rect x="${x2 + 6}" y="${y2 - 13}" width="${label.length * 7.6 + 14}" height="24" fill="${PAPER}" stroke="${ACCENT}" stroke-width="1"/>
    <text x="${x2 + 13}" y="${y2 + 4}" stroke="none">${label}</text>
  </g>`;

const sky = () =>
  `<rect x="0" y="0" width="${W}" height="560" fill="url(#sky)"/>
   <circle cx="1010" cy="132" r="46" fill="none" stroke="${ACCENT}" stroke-width="1.4" stroke-dasharray="5 7"/>
   <text x="1010" y="200" fill="${MUTE}" font-size="11" letter-spacing="2" text-anchor="middle">INSOLATION</text>`;

const ground = (y) =>
  `<rect x="0" y="${y}" width="${W}" height="${BASE - y}" fill="${PAPER2}"/>
   <line x1="0" y1="${y}" x2="${W}" y2="${y}" stroke="${INK}" stroke-width="1.6"/>
   <g stroke="${RULE}" stroke-width="1.2">
     ${Array.from({ length: 40 }, (_, i) => `<line x1="${i * 32}" y1="${y}" x2="${i * 32 - 14}" y2="${y + 18}"/>`).join("")}
   </g>`;

const exterior = {
  house: () => {
    const g = 560;
    return `${sky()}
      <g fill="none" stroke="${INK}" stroke-width="1.8">
        <path d="M340 ${g}V410l140 -92 280 92v150" fill="${PAPER}"/>
        <path d="M340 410l140 -92 280 92" fill="${PAPER2}"/>
        <path d="M300 418l180 -118 360 118" stroke-width="2.2"/>
        <rect x="392" y="452" width="74" height="66"/>
        <rect x="500" y="452" width="74" height="66"/>
        <rect x="636" y="452" width="74" height="66"/>
        <rect x="744" y="452" width="74" height="66"/>
        <rect x="576" y="472" width="58" height="88" fill="${PAPER2}"/>
        <path d="M700 340v-46h34v60"/>
      </g>
      ${ground(g)}
      ${tree(210, 520, 44)}
      ${tree(1010, 524, 38)}
      ${dimH(340, 760, 632, "14,60 м")}
      ${leader(600, 318, 878, 300, "КРОВЛЯ 30°")}
      ${leader(530, 470, 246, 430, "ОКНО ПАНОРАМНОЕ")}`;
  },
  block: () => {
    const g = 560;
    let windows = "";
    for (let f = 0; f < 9; f += 1) {
      for (let c = 0; c < 7; c += 1) {
        const x = 330 + c * 80;
        const y = 262 + f * 32;
        if (y > 478 && (c === 2 || c === 4)) continue;
        windows += windowCell(x, y, 56, 22, { mullion: (f + c) % 5 === 0 });
      }
    }
    let slabs = "";
    for (let f = 0; f <= 9; f += 1) {
      slabs += `<line x1="316" y1="${252 + f * 32}" x2="884" y2="${252 + f * 32}" stroke="${INK}" stroke-width="1"/>`;
    }
    return `${sky()}
      <g fill="none" stroke="${INK}" stroke-width="1.8">
        <rect x="316" y="252" width="568" height="308" fill="${PAPER}"/>
        <path d="M316 252h568l-40 -34H356z" fill="${PAPER2}"/>
        <rect x="470" y="502" width="94" height="58" fill="${PAPER2}"/>
        <rect x="626" y="502" width="94" height="58" fill="${PAPER2}"/>
      </g>
      ${windows}
      ${slabs}
      ${ground(g)}
      ${tree(200, 524, 40)}
      ${tree(1024, 528, 36)}
      ${dimV(252, 560, 936, "31,20 м")}
      ${dimH(316, 884, 626, "24,00 м")}
      ${leader(500, 512, 176, 470, "ВХОДНАЯ ГРУППА")}`;
  },
  office: () => {
    const g = 560;
    let grid = "";
    for (let r = 0; r < 6; r += 1) {
      for (let c = 0; c < 8; c += 1) {
        grid += windowCell(336 + c * 66, 306 + r * 42, 54, 34, { mullion: c % 2 === 0 });
      }
    }
    return `${sky()}
      <g fill="none" stroke="${INK}" stroke-width="1.8">
        <rect x="324" y="288" width="552" height="272" fill="${PAPER}"/>
        <path d="M324 288h552l-28 -30H352z" fill="${PAPER2}"/>
        <rect x="540" y="486" width="120" height="74" fill="${PAPER2}"/>
        <path d="M520 486h160v-16H520z" fill="${PAPER}"/>
      </g>
      ${grid}
      ${ground(g)}
      ${tree(214, 526, 38)}
      ${tree(1016, 528, 42)}
      ${dimH(324, 876, 626, "36,00 м")}
      ${dimV(288, 560, 268, "22,80 м")}
      ${leader(600, 470, 900, 430, "ВУТР. АТРИУМ")}`;
  },
  warehouse: () => {
    const g = 560;
    return `${sky()}
      <g fill="none" stroke="${INK}" stroke-width="1.8">
        <path d="M280 ${g}V442l176 -78 340 78v118" fill="${PAPER}"/>
        <path d="M280 442l176 -78 340 78" fill="${PAPER2}"/>
        <path d="M240 450l216 -96 420 96" stroke-width="2.2"/>
        <rect x="330" y="486" width="120" height="74" fill="${PAPER2}"/>
        <rect x="486" y="486" width="120" height="74" fill="${PAPER2}"/>
        <rect x="642" y="486" width="120" height="74" fill="${PAPER2}"/>
        <rect x="812" y="506" width="94" height="54" fill="${PAPER}"/>
        <path d="M812 506l47 -30 47 30" fill="${PAPER2}"/>
      </g>
      <g stroke="${INK}" stroke-width="1" opacity="0.5">
        ${Array.from({ length: 9 }, (_, i) => `<line x1="${300 + i * 92}" y1="${442 + i * 3}" x2="${300 + i * 92}" y2="560"/>`).join("")}
      </g>
      ${ground(g)}
      ${tree(1080, 530, 34)}
      ${dimH(280, 796, 632, "42,00 м")}
      ${leader(700, 400, 940, 350, "АНГАР 1 — СКЛАД")}
      ${leader(860, 500, 176, 470, "ТЕХ. БЛОК")}`;
  },
  timber: () => {
    const g = 560;
    let boards = "";
    for (let i = 0; i < 8; i += 1) {
      boards += `<line x1="356" y1="${436 + i * 16}" x2="846" y2="${436 + i * 16}" stroke="${INK}" stroke-width="0.8" opacity="0.5"/>`;
    }
    return `${sky()}
      <g fill="none" stroke="${INK}" stroke-width="1.8">
        <path d="M356 ${g}V436h490v124" fill="${PAPER}"/>
        <path d="M318 444l232 -130 300 130" stroke-width="2.2" fill="${PAPER2}"/>
        <path d="M550 314l0 -40 60 0 0 58" fill="${PAPER}"/>
        <rect x="396" y="466" width="72" height="52"/>
        <rect x="546" y="466" width="72" height="52"/>
        <rect x="736" y="466" width="72" height="52"/>
        <rect x="652" y="482" width="56" height="78" fill="${PAPER2}"/>
      </g>
      ${boards}
      ${ground(g)}
      ${tree(216, 520, 48)}
      ${tree(1006, 524, 52)}
      ${dimH(356, 846, 632, "16,80 м")}
      ${leader(600, 340, 902, 300, "ФРЕНЧИ ФАЛЬЦ")}`;
  },
  school: () => {
    const g = 560;
    let ribbon = "";
    for (let f = 0; f < 2; f += 1) {
      ribbon += `<rect x="250" y="${368 + f * 92}" width="700" height="58" fill="${PAPER2}" stroke="${INK}" stroke-width="1.4"/>`;
      for (let c = 0; c < 14; c += 1) {
        ribbon += `<line x1="${250 + (c + 1) * 50}" y1="${368 + f * 92}" x2="${250 + (c + 1) * 50}" y2="${426 + f * 92}" stroke="${INK}" stroke-width="0.9" opacity="0.6"/>`;
      }
    }
    return `${sky()}
      <g fill="none" stroke="${INK}" stroke-width="1.8">
        <rect x="238" y="336" width="724" height="224" fill="${PAPER}"/>
        <path d="M238 336h724l-34 -34H272z" fill="${PAPER2}"/>
        <rect x="546" y="474" width="108" height="86" fill="${PAPER}"/>
        <path d="M524 474h152v-18H524z" fill="${PAPER2}"/>
        <rect x="1010" y="366" width="4" height="194"/>
        <path d="M1014 372l52 14-52 14z" fill="${ACCENT}" stroke="${ACCENT}"/>
      </g>
      ${ribbon}
      ${ground(g)}
      ${tree(150, 524, 36)}
      ${tree(1090, 528, 40)}
      ${dimH(238, 962, 632, "60,00 м")}
      ${dimV(336, 560, 190, "9,60 м")}
      ${leader(600, 496, 176, 460, "ЦЕНТРАЛЬНЫЙ ВХОД")}`;
  },
};

const axes = (cols, x0, step) => {
  let out = "";
  cols.forEach((n, i) => {
    const x = x0 + i * step;
    out += `<g stroke="${ACCENT}" stroke-width="1" stroke-dasharray="10 7" opacity="0.85">
      <line x1="${x}" y1="96" x2="${x}" y2="${BASE - 96}"/>
    </g>
    <g fill="${PAPER}" stroke="${ACCENT}" stroke-width="1.4">
      <circle cx="${x}" cy="88" r="15"/>
    </g>
    <text x="${x}" y="93" fill="${ACCENT}" font-size="13" text-anchor="middle">${n}</text>`;
  });
  return out;
};

const facade = {
  house: () => `${axes(["1", "2", "3"], 300, 240)}
    <g fill="none" stroke="${INK}" stroke-width="2">
      <rect x="230" y="150" width="740" height="470" fill="${PAPER}"/>
      <line x1="230" y1="380" x2="970" y2="380"/>
      <path d="M196 150l404 -96 404 96" stroke-width="2.6" fill="${PAPER2}"/>
    </g>
    ${windowCell(292, 210, 132, 118)}
    ${windowCell(534, 210, 132, 118)}
    ${windowCell(776, 210, 132, 118)}
    ${windowCell(292, 424, 132, 118)}
    ${windowCell(534, 424, 132, 118)}
    ${windowCell(776, 424, 132, 118)}
    <g fill="none" stroke="${INK}" stroke-width="2">
      <rect x="548" y="530" width="104" height="90" fill="${PAPER2}"/>
      <line x1="600" y1="530" x2="600" y2="620"/>
    </g>
    <line x1="140" y1="620" x2="1060" y2="620" stroke="${INK}" stroke-width="2.4"/>
    ${dimV(150, 380, 168, "3,00 м")}
    ${dimV(380, 620, 168, "3,00 м")}
    ${dimH(292, 424, 664, "1,50 м")}
    ${leader(358, 240, 878, 118, "ОКНО ДУБЛЬ-ЛИТ")}
    ${leader(600, 560, 176, 470, "ВХОДНАЯ ДВЕРЬ")}`,

  block: () => `${axes(["1", "2", "3", "4"], 260, 230)}
    <g fill="none" stroke="${INK}" stroke-width="2">
      <rect x="180" y="130" width="840" height="490" fill="${PAPER}"/>
      <path d="M180 130h840l-40 -40H220z" fill="${PAPER2}"/>
    </g>
    ${Array.from({ length: 5 }, (_, r) =>
      Array.from({ length: 7 }, (_, c) => windowCell(214 + c * 116, 176 + r * 88, 86, 58, { mullion: (r + c) % 3 === 0 })).join(""),
    ).join("")}
    <g fill="none" stroke="${INK}" stroke-width="1.6">
      ${Array.from({ length: 6 }, (_, r) => `<line x1="180" y1="${166 + r * 88}" x2="1020" y2="${166 + r * 88}"/>`).join("")}
    </g>
    <line x1="130" y1="620" x2="1070" y2="620" stroke="${INK}" stroke-width="2.4"/>
    ${dimV(130, 620, 116, "16,50 м")}
    ${dimH(214, 300, 664, "2,70 м")}
    ${leader(600, 146, 880, 74, "ПАРАПЕТ 1,20")}`,

  office: () => `${axes(["1", "2", "3", "4", "5"], 240, 190)}
    <g fill="none" stroke="${INK}" stroke-width="2">
      <rect x="170" y="140" width="860" height="480" fill="${PAPER}"/>
    </g>
    ${Array.from({ length: 5 }, (_, r) =>
      Array.from({ length: 9 }, (_, c) => windowCell(190 + c * 94, 164 + r * 88, 74, 64, { mullion: true })).join(""),
    ).join("")}
    <g fill="none" stroke="${INK}" stroke-width="1.6">
      ${Array.from({ length: 6 }, (_, r) => `<line x1="170" y1="${156 + r * 88}" x2="1030" y2="${156 + r * 88}"/>`).join("")}
    </g>
    <g fill="none" stroke="${INK}" stroke-width="2">
      <rect x="470" y="518" width="180" height="102" fill="${PAPER2}"/>
      <path d="M440 518h240v-20H440z" fill="${PAPER}"/>
    </g>
    <line x1="120" y1="620" x2="1080" y2="620" stroke="${INK}" stroke-width="2.4"/>
    ${dimV(140, 620, 108, "21,60 м")}
    ${dimH(190, 264, 664, "1,80 м")}
    ${leader(660, 250, 880, 116, "ВЕНТ. ФАСАД")}`,

  warehouse: () => `${axes(["1", "2", "3", "4", "5", "6"], 240, 150)}
    <g fill="none" stroke="${INK}" stroke-width="2">
      <rect x="170" y="250" width="860" height="370" fill="${PAPER}"/>
      <path d="M140 256l460 -130 460 130" stroke-width="2.6" fill="${PAPER2}"/>
      <path d="M600 126v394" stroke-width="1.4" opacity="0.6"/>
    </g>
    ${Array.from({ length: 6 }, (_, c) =>
      `<g fill="none" stroke="${INK}" stroke-width="1.8">
        <rect x="${200 + c * 150}" y="404" width="110" height="216" fill="${PAPER2}"/>
        ${Array.from({ length: 6 }, (_, i) => `<line x1="${200 + c * 150}" y1="${404 + i * 36}" x2="${310 + c * 150}" y2="${404 + i * 36}" stroke-width="0.9"/>`).join("")}
      </g>`,
    ).join("")}
    <line x1="120" y1="620" x2="1080" y2="620" stroke="${INK}" stroke-width="2.4"/>
    ${dimV(250, 620, 108, "6,00 м")}
    ${dimH(200, 310, 664, "4,00 м")}
    ${leader(760, 300, 890, 150, "ВОРОТА 4,0 × 4,5")}`,

  timber: () => `${axes(["1", "2", "3"], 300, 250)}
    <g fill="none" stroke="${INK}" stroke-width="2">
      <rect x="240" y="230" width="700" height="390" fill="${PAPER}"/>
      <path d="M200 236l380 -128 380 128" stroke-width="2.6" fill="${PAPER2}"/>
      <path d="M580 108V66h70v58" fill="${PAPER}"/>
    </g>
    <g stroke="${INK}" stroke-width="0.9" opacity="0.55">
      ${Array.from({ length: 24 }, (_, i) => `<line x1="240" y1="${236 + i * 16}" x2="940" y2="${236 + i * 16}"/>`).join("")}
    </g>
    ${windowCell(300, 290, 122, 104)}
    ${windowCell(538, 290, 122, 104)}
    ${windowCell(776, 290, 122, 104)}
    ${windowCell(300, 470, 122, 104)}
    ${windowCell(776, 470, 122, 104)}
    <g fill="none" stroke="${INK}" stroke-width="2">
      <rect x="546" y="470" width="108" height="150" fill="${PAPER2}"/>
      <line x1="600" y1="470" x2="600" y2="620"/>
    </g>
    <line x1="150" y1="620" x2="1050" y2="620" stroke="${INK}" stroke-width="2.4"/>
    ${dimV(230, 620, 178, "3,30 м")}
    ${dimH(300, 422, 664, "1,30 м")}
    ${leader(600, 150, 878, 96, "БРУС 200 × 200")}`,

  school: () => `${axes(["1", "2", "3", "4", "5", "6"], 230, 156)}
    <g fill="none" stroke="${INK}" stroke-width="2">
      <rect x="160" y="210" width="880" height="410" fill="${PAPER}"/>
      <path d="M160 210h880l-34 -36H194z" fill="${PAPER2}"/>
      <line x1="160" y1="416" x2="1040" y2="416"/>
    </g>
    ${Array.from({ length: 2 }, (_, r) =>
      Array.from({ length: 10 }, (_, c) => windowCell(184 + c * 86, 244 + r * 166, 66, 118, { mullion: c % 3 === 0 })).join(""),
    ).join("")}
    <g fill="none" stroke="${INK}" stroke-width="2">
      <rect x="480" y="474" width="160" height="146" fill="${PAPER2}"/>
      <path d="M456 474h208v-20H456z" fill="${PAPER}"/>
      <line x1="560" y1="474" x2="560" y2="620"/>
    </g>
    <line x1="110" y1="620" x2="1090" y2="620" stroke="${INK}" stroke-width="2.4"/>
    ${dimV(210, 620, 98, "8,40 м")}
    ${dimH(184, 250, 664, "1,80 м")}
    ${leader(700, 300, 880, 140, "КЛАСС-КОМНАТА 62 м²")}`,
};

const northArrow = (x, y) =>
  `<g stroke="${INK}" fill="${INK}" stroke-width="1.4">
    <circle cx="${x}" cy="${y}" r="28" fill="${PAPER}"/>
    <path d="M${x} ${y - 34}l9 30-9 8-9-8z" fill="${ACCENT}" stroke="${ACCENT}"/>
    <text x="${x}" y="${y + 52}" font-size="13" text-anchor="middle" stroke="none" fill="${INK}">С</text>
  </g>`;

const scaleBar = (x, y) =>
  `<g>
    <rect x="${x}" y="${y}" width="40" height="8" fill="${INK}"/>
    <rect x="${x + 40}" y="${y}" width="40" height="8" fill="${PAPER}" stroke="${INK}" stroke-width="1"/>
    <rect x="${x + 80}" y="${y}" width="40" height="8" fill="${INK}"/>
    <rect x="${x + 120}" y="${y}" width="40" height="8" fill="${PAPER}" stroke="${INK}" stroke-width="1"/>
    <text x="${x}" y="${y + 26}" fill="${MUTE}" font-size="11" letter-spacing="1">0</text>
    <text x="${x + 80}" y="${y + 26}" fill="${MUTE}" font-size="11" letter-spacing="1" text-anchor="middle">20</text>
    <text x="${x + 160}" y="${y + 26}" fill="${MUTE}" font-size="11" letter-spacing="1" text-anchor="middle">40 м</text>
  </g>`;

const plot = (points) =>
  `<polygon points="${points}" fill="none" stroke="${INK}" stroke-width="1.6" stroke-dasharray="14 8"/>`;

const road = (y, label) =>
  `<g>
    <rect x="0" y="${y}" width="${W}" height="86" fill="${PAPER2}"/>
    <line x1="0" y1="${y}" x2="${W}" y2="${y}" stroke="${INK}" stroke-width="1.6"/>
    <line x1="0" y1="${y + 86}" x2="${W}" y2="${y + 86}" stroke="${INK}" stroke-width="1.6"/>
    <line x1="0" y1="${y + 43}" x2="${W}" y2="${y + 43}" stroke="${PAPER}" stroke-width="3" stroke-dasharray="34 26"/>
    <text x="24" y="${y + 74}" fill="${MUTE}" font-size="12" letter-spacing="3">${label}</text>
  </g>`;

const parking = (x, y, count, vertical = false) => {
  let out = `<g stroke="${GRAPHITE}" stroke-width="1.1" fill="none">`;
  for (let i = 0; i < count; i += 1) {
    const px = vertical ? x : x + i * 46;
    const py = vertical ? y + i * 46 : y;
    out += vertical
      ? `<line x1="${px}" y1="${py}" x2="${px + 92}" y2="${py}"/>`
      : `<line x1="${px}" y1="${py}" x2="${px}" y2="${py + 92}"/>`;
  }
  out += vertical ? `<line x1="${x + 92}" y1="${y}" x2="${x + 92}" y2="${y + count * 46}"/>` : `<line x1="${x}" y1="${y + 92}" x2="${x + count * 46}" y2="${y + 92}"/>`;
  out += `</g><text x="${x}" y="${y - 12}" fill="${MUTE}" font-size="11" letter-spacing="2">ПАРКОВКА</text>`;
  return out;
};

const sitePlan = {
  house: () => `${plot("140,120 1060,120 1060,640 140,640")}
    <rect x="420" y="230" width="330" height="230" fill="${PAPER2}" stroke="${INK}" stroke-width="2.2"/>
    <path d="M420 345h330M585 230v230" stroke="${INK}" stroke-width="1.2" opacity="0.7"/>
    <text x="585" y="352" fill="${INK}" font-size="13" text-anchor="middle" letter-spacing="2">ДОМ 340 м²</text>
    <rect x="790" y="440" width="90" height="70" fill="${PAPER}" stroke="${INK}" stroke-width="1.6"/>
    <text x="835" y="480" fill="${MUTE}" font-size="11" text-anchor="middle">ГАРАЖ</text>
    <path d="M750 345h60v96" fill="none" stroke="${INK}" stroke-width="1.4" stroke-dasharray="8 6"/>
    ${road(60, "ПРОЕЗД · ТУПИКОВЫЙ")}
    ${parking(846, 170, 4, true)}
    ${tree(240, 240, 30)}${tree(300, 470, 34)}${tree(220, 560, 28)}${tree(980, 540, 32)}${tree(930, 260, 28)}
    ${northArrow(1120, 180)}
    ${scaleBar(1000, 620)}`,

  block: () => `${plot("80,90 1120,90 1120,650 80,650")}
    <rect x="180" y="170" width="440" height="150" fill="${PAPER2}" stroke="${INK}" stroke-width="2.2"/>
    <rect x="180" y="400" width="440" height="150" fill="${PAPER2}" stroke="${INK}" stroke-width="2.2"/>
    <rect x="740" y="170" width="290" height="380" fill="${PAPER2}" stroke="${INK}" stroke-width="2.2"/>
    <text x="400" y="252" fill="${INK}" font-size="13" text-anchor="middle" letter-spacing="2">КОРПУС 1</text>
    <text x="400" y="482" fill="${INK}" font-size="13" text-anchor="middle" letter-spacing="2">КОРПУС 2</text>
    <text x="885" y="366" fill="${INK}" font-size="13" text-anchor="middle" letter-spacing="2">КОРПУС 3</text>
    <path d="M620 245h120M620 475h120" stroke="${INK}" stroke-width="1.4" stroke-dasharray="9 7"/>
    ${road(600, "УЛ. СТРОИТЕЛЕЙ")}
    ${parking(660, 180, 5, true)}
    ${tree(660, 330, 26)}${tree(660, 450, 26)}${tree(120, 300, 30)}${tree(1080, 600, 28)}
    ${northArrow(70, 160)}
    ${scaleBar(940, 64)}`,

  office: () => `${plot("120,110 1080,110 1080,630 120,630")}
    <rect x="330" y="210" width="520" height="300" fill="${PAPER2}" stroke="${INK}" stroke-width="2.2"/>
    <path d="M330 310h520M590 210v300" stroke="${INK}" stroke-width="1.2" opacity="0.7"/>
    <rect x="530" y="440" width="120" height="70" fill="${PAPER}" stroke="${INK}" stroke-width="1.6"/>
    <text x="590" y="368" fill="${INK}" font-size="13" text-anchor="middle" letter-spacing="2">БЦ · 8 900 м²</text>
    <text x="590" y="482" fill="${MUTE}" font-size="11" text-anchor="middle">ВХОД</text>
    <path d="M590 510v70" stroke="${INK}" stroke-width="1.4" stroke-dasharray="9 7"/>
    ${road(594, "ПРОСПЕКТ · 4 ПОЛОСЫ")}
    ${parking(150, 170, 6)}
    ${tree(220, 350, 30)}${tree(220, 480, 30)}${tree(960, 250, 30)}${tree(960, 400, 32)}${tree(1010, 540, 26)}
    ${northArrow(1140, 170)}
    ${scaleBar(140, 64)}`,

  warehouse: () => `${plot("90,120 1110,120 1110,620 90,620")}
    <rect x="180" y="230" width="470" height="260" fill="${PAPER2}" stroke="${INK}" stroke-width="2.2"/>
    <rect x="700" y="230" width="230" height="260" fill="${PAPER2}" stroke="${INK}" stroke-width="2.2"/>
    <path d="M180 360h470M415 230v260" stroke="${INK}" stroke-width="1.2" opacity="0.7"/>
    <text x="415" y="366" fill="${INK}" font-size="13" text-anchor="middle" letter-spacing="2">АНГАР 1</text>
    <text x="815" y="366" fill="${INK}" font-size="13" text-anchor="middle" letter-spacing="2">АНГАР 2</text>
    <rect x="660" y="230" width="40" height="260" fill="${PAPER}" stroke="${INK}" stroke-width="1.6"/>
    <text x="680" y="366" fill="${MUTE}" font-size="10" text-anchor="middle" transform="rotate(-90 680 366)">ТЕХ. БЛОК</text>
    ${road(560, "ПОДЪЕЗДНАЯ ПЛОЩАДКА")}
    ${parking(180, 530, 6)}
    ${tree(1030, 200, 30)}${tree(1030, 560, 26)}${tree(130, 180, 26)}
    ${northArrow(1140, 180)}
    ${scaleBar(940, 64)}`,

  timber: () => `${plot("160,130 1040,130 1040,630 160,630")}
    <rect x="430" y="250" width="300" height="210" fill="${PAPER2}" stroke="${INK}" stroke-width="2.2"/>
    <path d="M430 355h300M580 250v210" stroke="${INK}" stroke-width="1.2" opacity="0.7"/>
    <text x="580" y="362" fill="${INK}" font-size="13" text-anchor="middle" letter-spacing="2">ДОМ 210 м²</text>
    <path d="M730 355h90v60" fill="none" stroke="${INK}" stroke-width="1.4" stroke-dasharray="8 6"/>
    <rect x="820" y="378" width="86" height="64" fill="${PAPER}" stroke="${INK}" stroke-width="1.6"/>
    <text x="863" y="415" fill="${MUTE}" font-size="11" text-anchor="middle">БАНЯ</text>
    ${road(54, "ЛЕСНАЯ ДОРОГА")}
    ${parking(770, 176, 4, true)}
    ${tree(250, 250, 36)}${tree(300, 470, 40)}${tree(230, 560, 34)}${tree(960, 540, 38)}${tree(950, 240, 34)}${tree(1000, 380, 30)}
    ${northArrow(1120, 180)}
    ${scaleBar(1000, 64)}`,

  school: () => `${plot("70,100 1130,100 1130,640 70,640")}
    <rect x="200" y="210" width="560" height="240" fill="${PAPER2}" stroke="${INK}" stroke-width="2.2"/>
    <path d="M200 330h560M480 210v240" stroke="${INK}" stroke-width="1.2" opacity="0.7"/>
    <text x="480" y="336" fill="${INK}" font-size="13" text-anchor="middle" letter-spacing="2">ШКОЛА · 9 600 м²</text>
    <rect x="800" y="230" width="260" height="200" fill="none" stroke="${INK}" stroke-width="2" stroke-dasharray="12 8"/>
    <text x="930" y="336" fill="${GRAPHITE}" font-size="13" text-anchor="middle" letter-spacing="2">СПОРТПЛОЩАДКА</text>
    <rect x="800" y="470" width="260" height="120" fill="none" stroke="${ACCENT}" stroke-width="1.6" stroke-dasharray="8 7"/>
    <text x="930" y="536" fill="${ACCENT}" font-size="12" text-anchor="middle" letter-spacing="2">БЕГОВАЯ ДОРОЖКА</text>
    <path d="M480 450v80" stroke="${INK}" stroke-width="1.4" stroke-dasharray="9 7"/>
    ${road(566, "УЛ. ПРОСВЕЩЕНИЯ")}
    ${parking(200, 500, 6)}
    ${tree(130, 200, 30)}${tree(130, 340, 30)}${tree(130, 470, 30)}${tree(1090, 160, 26)}${tree(760, 500, 26)}
    ${northArrow(70, 150)}
    ${scaleBar(940, 64)}`,
};

mkdirSync(OUT, { recursive: true });

for (const project of PROJECTS) {
  const dir = join(OUT, project.slug);
  mkdirSync(dir, { recursive: true });
  const builders = [exterior[project.shape], facade[project.shape], sitePlan[project.shape]];
  builders.forEach((build, index) => {
    const svg = frame({ project, index, body: build() });
    writeFileSync(join(dir, `0${index + 1}.svg`), svg, "utf8");
  });
}

console.log(`generated ${PROJECTS.length * 3} images in ${OUT}`);
