<svg viewBox="0 0 660 500" fill="none" class="h-auto w-full" role="img" aria-label="Фасад частного дома с размерами и отметками">
  <defs>
    <pattern id="hero-hatch" width="7" height="7" patternUnits="userSpaceOnUse" patternTransform="rotate(45)">
      <line x1="0" y1="0" x2="0" y2="7" stroke="var(--color-ink)" stroke-width="1.1" opacity="0.4"/>
    </pattern>
    <pattern id="hero-soil" width="18" height="18" patternUnits="userSpaceOnUse" patternTransform="rotate(45)">
      <line x1="0" y1="0" x2="0" y2="11" stroke="var(--color-mute)" stroke-width="1"/>
    </pattern>
  </defs>

  <!-- ground -->
  <path d="M20 410 H640" class="draw" pathLength="1" style="--d:0ms; stroke:var(--color-ink); stroke-width:2"/>
  <rect x="20" y="412" width="620" height="15" fill="url(#hero-soil)" class="fade-part" style="--d:1500ms"/>

  <!-- roof fill -->
  <path d="M92 256 L330 118 L568 256 Z" fill="url(#hero-hatch)" class="fade-part" style="--d:950ms"/>

  <!-- house -->
  <path d="M120 410 V250 H540 V410" class="draw" pathLength="1" style="--d:250ms; stroke:var(--color-ink); stroke-width:2"/>
  <path d="M92 256 H568" class="draw" pathLength="1" style="--d:560ms; stroke:var(--color-ink); stroke-width:2"/>
  <path d="M92 256 L330 118 L568 256" class="draw" pathLength="1" style="--d:430ms; stroke:var(--color-ink); stroke-width:2"/>
  <path d="M474 200 V140 H504 V217" class="draw" pathLength="1" style="--d:780ms; stroke:var(--color-ink); stroke-width:1.6"/>

  <!-- openings -->
  <path d="M170 290 h90 v80 h-90 z" class="draw" pathLength="1" style="--d:1000ms; stroke:var(--color-ink); stroke-width:1.5"/>
  <path d="M215 290 v80" class="draw" pathLength="1" style="--d:1160ms; stroke:var(--color-ink); stroke-width:1"/>
  <path d="M400 290 h90 v80 h-90 z" class="draw" pathLength="1" style="--d:1060ms; stroke:var(--color-ink); stroke-width:1.5"/>
  <path d="M445 290 v80" class="draw" pathLength="1" style="--d:1220ms; stroke:var(--color-ink); stroke-width:1"/>
  <path d="M300 330 h70 v80 h-70 z" class="draw" pathLength="1" style="--d:1110ms; stroke:var(--color-ink); stroke-width:1.5"/>

  <!-- dimensions -->
  <g class="fade-part" style="--d:1320ms; stroke:var(--color-mute); stroke-width:0.9; fill:none">
    <path d="M92 250 V84" stroke-dasharray="4 4"/>
    <path d="M568 250 V84" stroke-dasharray="4 4"/>
    <path d="M92 96 H568" stroke="var(--color-accent)"/>
    <path d="M85 103 L99 89" stroke="var(--color-accent)"/>
    <path d="M561 103 L575 89" stroke="var(--color-accent)"/>

    <path d="M60 118 V410"/>
    <path d="M53 125 L67 111"/>
    <path d="M53 417 L67 403"/>
    <path d="M60 118 H318" stroke-dasharray="4 4"/>

    <path d="M612 256 V410"/>
    <path d="M605 263 L619 249"/>
    <path d="M605 417 L619 403"/>
    <path d="M568 256 H612" stroke-dasharray="4 4"/>
    <path d="M540 410 H612" stroke-dasharray="4 4"/>
  </g>

  <g class="fade-part" style="--d:1440ms; fill:var(--color-mute); font-size:13">
    <text x="330" y="86" text-anchor="middle" fill="var(--color-accent)">14 700</text>
    <text transform="translate(50 264) rotate(-90)" text-anchor="middle">9 000</text>
    <text transform="translate(604 333) rotate(-90)" text-anchor="middle">4 800</text>
  </g>

  <!-- level marks -->
  <g class="fade-part" style="--d:1560ms; stroke:var(--color-mute); stroke-width:1">
    <path d="M58 250 H100"/>
    <path d="M70 250 L78 238 L86 250 Z" fill="var(--color-ink)" stroke="none"/>
    <path d="M100 412 H140"/>
    <path d="M112 412 L120 400 L128 412 Z" fill="var(--color-ink)" stroke="none"/>
  </g>
  <g class="fade-part" style="--d:1620ms; fill:var(--color-mute); font-size:12">
    <text x="58" y="232">+4.800</text>
    <text x="100" y="432">±0.000</text>
  </g>

  <!-- axes -->
  <g class="fade-part" style="--d:1680ms; stroke:var(--color-mute); stroke-width:1">
    <path d="M92 410 V438" stroke-dasharray="10 4 2 4" fill="none"/>
    <path d="M330 410 V438" stroke-dasharray="10 4 2 4" fill="none"/>
    <path d="M568 410 V438" stroke-dasharray="10 4 2 4" fill="none"/>
    <circle cx="92" cy="450" r="11" fill="none"/>
    <circle cx="330" cy="450" r="11" fill="none"/>
    <circle cx="568" cy="450" r="11" fill="none"/>
  </g>
  <g class="fade-part" style="--d:1760ms; fill:var(--color-mute); font-size:11">
    <text x="92" y="454" text-anchor="middle">1</text>
    <text x="330" y="454" text-anchor="middle">2</text>
    <text x="568" y="454" text-anchor="middle">3</text>
  </g>

  <!-- sheet note -->
  <g class="fade-part" style="--d:1850ms; fill:var(--color-mute); font-size:11">
    <text x="20" y="492">ФАСАД 1-1 · М 1:100</text>
    <text x="640" y="492" text-anchor="end">ИНВ. 2026-014</text>
  </g>
</svg>
