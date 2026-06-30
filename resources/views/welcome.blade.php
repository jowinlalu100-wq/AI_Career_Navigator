<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AI Career Navigator</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link
    href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@500;700&display=swap"
    rel="stylesheet">
  <style>
    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    :root {
      --bg: #0A0E1F;
      --bg-card: #111529;
      --indigo: #6C63FF;
      --indigo-soft: #8B85FF;
      --indigo-dim: rgba(108, 99, 255, 0.12);
      --indigo-glow: rgba(108, 99, 255, 0.35);
      --green: #34D399;
      --green-dim: rgba(52, 211, 153, 0.12);
      --text-1: #EDEFF7;
      --text-2: #9CA3C4;
      --text-3: #5E6488;
      --border: rgba(255, 255, 255, 0.07);
      --font-d: 'Space Grotesk', sans-serif;
      --font-b: 'Inter', sans-serif;
      --font-m: 'JetBrains Mono', monospace;
    }

    html {
      scroll-behavior: smooth;
    }

    body {
      background: var(--bg);
      color: var(--text-1);
      font-family: var(--font-b);
      overflow-x: hidden;
    }

    /* ── NAV ── */
    .cnv-pagenav {
    position: fixed;
    top: 50%;
    right: 26px;
    transform: translateY(-50%);
    z-index: 500;
    display: flex;
    flex-direction: column;
    gap: 22px;
    background: transparent;
}
.cnv-pagenav-link {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 10px;
    text-decoration: none;
    padding: 4px 0;
}
.cnv-pagenav-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transition: all .4s cubic-bezier(.16, 1, .3, 1);
    flex-shrink: 0;
}
.cnv-pagenav-label {
    font-family: var(--font-m);
    font-size: .62rem;
    letter-spacing: .16em;
    text-transform: uppercase;
    color: rgba(255, 255, 255, 0.35);
    white-space: nowrap;
    opacity: 0;
    transform: translateX(6px);
    transition: all .4s cubic-bezier(.16, 1, .3, 1);
}
.cnv-pagenav-link:hover .cnv-pagenav-label {
    opacity: .7;
    transform: translateX(0);
}
.cnv-pagenav-link:hover .cnv-pagenav-dot {
    background: rgba(255, 255, 255, 0.6);
}
.cnv-pagenav-link.active .cnv-pagenav-dot {
    width: 11px;
    height: 11px;
    background: #ffffff;
    box-shadow: 0 0 12px 3px rgba(255, 255, 255, 0.55), 0 0 24px 6px rgba(255, 255, 255, 0.25);
}
.cnv-pagenav-link.active .cnv-pagenav-label {
    opacity: 1;
    transform: translateX(0);
    color: #ffffff;
    text-shadow: 0 0 12px rgba(255, 255, 255, 0.4);
}
@media (prefers-reduced-motion: reduce) {
    .cnv-pagenav-dot, .cnv-pagenav-label { transition: none; }
}
@media (max-width: 900px) {
    .cnv-pagenav { display: none; }
}
    /* ── HERO ── */
    .hero {
      position: relative;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
      padding: 60px 24px 80px;
      overflow: hidden;
    }

    .hero-bg-video {
      position: absolute;
      top: 50%;
      left: 50%;
      width: min(1000px, 100vw);
      aspect-ratio: 1 / 1;
      transform: translate(-50%, -40%);
      object-fit: cover;
      z-index: 0;
      opacity: 0.9;
      pointer-events: none;

      -webkit-mask-image: radial-gradient(circle at center, black 38%, transparent 68%);
      mask-image: radial-gradient(circle at center, black 38%, transparent 68%);
    }

    .hero-eyebrow,
    .hero-title,
    .hero-sub,
    .hero-cta {
      position: relative;
      z-index: 1;
    }

    @media (max-width: 600px) {
      .hero-bg-video {
        width: min(560px, 100vw);
        transform: translate(-50%, -36%);
      }
    }

    @media (prefers-reduced-motion: reduce) {
      .hero-bg-video {
        display: none;
      }
    }

    .hero-bg-orb {
      position: absolute;
      border-radius: 50%;
      filter: blur(100px);
      pointer-events: none;
    }

    .orb-1 {
      width: 600px;
      height: 600px;
      background: rgba(108, 99, 255, 0.18);
      top: -200px;
      left: -200px;
    }

    .orb-2 {
      width: 500px;
      height: 500px;
      background: rgba(52, 211, 153, 0.08);
      bottom: -150px;
      right: -150px;
    }

    .hero-eyebrow {
      font-size: 11.5px;
      letter-spacing: 0.18em;
      color: #ffffff;

      font-family: "Inter", system-ui, sans-serif;
      font-weight: 800;
      letter-spacing: 0.18em;

      /* Step 2: Layer tight bright cores with wide ambient pastel-purple shadows */
      text-shadow:
        0 0 6px rgba(255, 255, 255, 0.92),
        0 0 15px rgba(230, 176, 255, 0.85),
        0 0 30px rgba(230, 176, 255, 0.65),
        0 0 60px rgba(230, 176, 255, 0.45),
        0 0 120px rgba(186, 104, 200, 0.3);
      margin-bottom: 22px;
    }

    .hero-title {
      font-family: var(--font-d);
      font-size: clamp(38px, 7vw, 88px);
      font-weight: 700;
      line-height: 1.08;
      letter-spacing: -0.02em;
      color: var(--text-1);
      max-width: 900px;
      position: relative;
      z-index: 1;
    }

    .hero-title em {
      font-style: normal;
      background: linear-gradient(120deg, var(--indigo-soft), var(--green));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }



    .hero-cta {
      display: flex;
      gap: 14px;
      justify-content: center;
      flex-wrap: wrap;
      margin-top: 40px;
      position: relative;
      z-index: 1;
    }

    .btn-primary {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: linear-gradient(135deg, var(--indigo), var(--indigo-soft));
      color: #fff;
      font-family: var(--font-d);
      font-weight: 600;
      font-size: 15px;
      padding: 14px 32px;
      border-radius: 10px;
      text-decoration: none;
      transition: opacity 0.15s, transform 0.12s;
      box-shadow: 0 0 32px rgba(108, 99, 255, 0.3);
    }

    .btn-primary:hover {
      opacity: 0.9;
      transform: translateY(-1px);
    }

    .btn-ghost {
      display: inline-flex;
      align-items: center;
      gap: 8px;

      color: var(--indigo-soft);
      border: 1px solid var(--indigo-glow);
      font-family: var(--font-d);
      font-weight: 600;
      font-size: 15px;
      padding: 14px 32px;
      border-radius: 10px;
      text-decoration: none;
      transition: background 0.15s, transform 0.12s;

      background: rgba(255, 255, 255, 0.03);
      backdrop-filter: blur(4px);
      -webkit-backdrop-filter: blur(4px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 12px;
      box-shadow: 0 4px 30px rgba(0, 0, 0, 0);
    }

    .btn-ghost:hover {
      background: var(--indigo-dim);
      transform: translateY(-1px);
    }

    .hero-scroll-hint {
      position: absolute;
      bottom: 38px;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 8px;
      color: var(--text-3);
      font-family: var(--font-m);
      font-size: 11px;
      letter-spacing: 0.12em;
      animation: float 2.4s ease-in-out infinite;
    }

    .scroll-arrow {
      width: 20px;
      height: 20px;
      border-right: 2px solid var(--text-3);
      border-bottom: 2px solid var(--text-3);
      transform: rotate(45deg);
    }

    @keyframes float {

      0%,
      100% {
        transform: translateX(-50%) translateY(0);
      }

      50% {
        transform: translateX(-50%) translateY(6px);
      }
    }

    /* ── SECTION BASE ── */
    section {
      padding: 120px 24px;
      position: relative;
    }

    .container {
      max-width: 1100px;
      margin: 0 auto;
    }

    .section-eyebrow {
      font-family: var(--font-m);
      font-size: 11.5px;
      font-weight: 700;
      letter-spacing: 0.18em;
      color: var(--indigo-soft);
      margin-bottom: 16px;
    }

    .section-title {
      font-family: var(--font-d);
      font-size: clamp(28px, 4vw, 52px);
      font-weight: 700;
      line-height: 1.14;
      letter-spacing: -0.02em;
      color: var(--text-1);
      max-width: 780px;
    }

    .section-sub {
      font-size: 16px;
      color: var(--text-2);
      max-width: 540px;
      margin-top: 14px;
      line-height: 1.7;
    }

    /* ── DIVIDER ── */
    .divider {
      width: 100%;
      height: 1px;
      background: linear-gradient(90deg, transparent, var(--indigo-glow), transparent);
      margin: 0 auto;
    }

    /* ── INTRO TEXT SECTION ── */
    .intro-text {
      background: var(--bg);
    }

    .intro-text .big-label {
      font-family: var(--font-d);
      font-size: clamp(13px, 2vw, 17px);
      color: var(--text-3);
      font-weight: 500;
      letter-spacing: 0.01em;
      margin-bottom: 28px;
    }

    .intro-text .large-copy {
      font-family: var(--font-d);
      font-size: clamp(20px, 3vw, 32px);
      font-weight: 600;
      line-height: 1.4;
      color: var(--text-1);
      max-width: 820px;
    }

    .intro-text .large-copy span {
      color: var(--indigo-soft);
    }

    /* ── PHASES ── */
    .phases {
      background: var(--bg);
    }

    .phases-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 28px;
      margin-top: 64px;
    }

    @media (max-width: 768px) {
      .phases-grid {
        grid-template-columns: 1fr;
      }
    }

    .phase-card {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: 16px;
      padding: 40px 36px;
      position: relative;
      overflow: hidden;
      transition: border-color 0.2s;
    }

    .phase-card:hover {
      border-color: var(--indigo-glow);
    }

    .phase-card::before {
      content: '';
      position: absolute;
      top: -60px;
      right: -60px;
      width: 200px;
      height: 200px;
      background: radial-gradient(circle, rgba(108, 99, 255, 0.1) 0%, transparent 70%);
      pointer-events: none;
    }

    .phase-num {
      font-family: var(--font-m);
      font-size: 56px;
      font-weight: 700;
      color: var(--indigo-dim);
      line-height: 1;
      margin-bottom: 20px;
      background: linear-gradient(135deg, var(--indigo), transparent);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .phase-title {
      font-family: var(--font-d);
      font-size: 22px;
      font-weight: 700;
      color: var(--text-1);
      margin-bottom: 14px;
    }

    .phase-body {
      font-size: 15px;
      color: var(--text-2);
      line-height: 1.7;
    }

    .phase-tag {
      display: inline-block;
      margin-top: 20px;
      font-family: var(--font-m);
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 0.12em;
      color: var(--green);
      background: var(--green-dim);
      border: 1px solid rgba(52, 211, 153, 0.25);
      padding: 5px 12px;
      border-radius: 20px;
    }

    /* ── FAQ SECTION ── */
    .faq {
      background: var(--bg);
    }

    .faq-list {
      margin-top: 56px;
      display: flex;
      flex-direction: column;
      gap: 0;
    }

    .faq-item {
      border-top: 1px solid var(--border);
    }

    .faq-item:last-child {
      border-bottom: 1px solid var(--border);
    }

    .faq-q {
      width: 100%;
      background: none;
      border: none;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      padding: 26px 0;
      text-align: left;
    }

    .faq-q-text {
      font-family: var(--font-d);
      font-size: 18px;
      font-weight: 600;
      color: var(--text-1);
    }

    .faq-icon {
      flex-shrink: 0;
      width: 28px;
      height: 28px;
      border-radius: 50%;
      background: var(--indigo-dim);
      border: 1px solid var(--indigo-glow);
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background 0.2s, transform 0.3s;
    }

    .faq-icon svg {
      width: 14px;
      height: 14px;
      stroke: var(--indigo-soft);
      transition: transform 0.3s;
    }

    .faq-item.open .faq-icon {
      background: var(--indigo);
    }

    .faq-item.open .faq-icon svg {
      transform: rotate(45deg);
      stroke: #fff;
    }

    .faq-a {
      overflow: hidden;
      max-height: 0;
      transition: max-height 0.4s ease;
    }

    .faq-a-inner {
      padding: 0 0 26px;
      font-size: 15.5px;
      color: var(--text-2);
      line-height: 1.72;
      max-width: 720px;
    }

    /* ── STATS STRIP ── */
    .stats-strip {
      background: var(--bg-card);
      border-top: 1px solid var(--border);
      border-bottom: 1px solid var(--border);
      padding: 70px 24px;
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 2px;
      max-width: 900px;
      margin: 0 auto;
      text-align: center;
    }

    @media (max-width: 600px) {
      .stats-grid {
        grid-template-columns: 1fr;
        gap: 40px;
      }
    }

    .stat-val {
      font-family: var(--font-d);
      font-size: clamp(36px, 5vw, 60px);
      font-weight: 700;
      background: linear-gradient(120deg, var(--indigo-soft), var(--green));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .stat-label {
      font-size: 14px;
      color: var(--text-3);
      margin-top: 6px;
      font-family: var(--font-b);
    }

    /* ── HOW IT WORKS (step list) ── */
    .how {
      background: var(--bg);
    }

    .steps {
      display: flex;
      flex-direction: column;
      gap: 0;
      margin-top: 64px;
      position: relative;
    }

    .steps::before {
      content: '';
      position: absolute;
      left: 27px;
      top: 28px;
      bottom: 28px;
      width: 2px;
      background: linear-gradient(180deg, var(--indigo), transparent);
    }

    @media (max-width: 600px) {
      .steps::before {
        left: 19px;
      }
    }

    .step {
      display: flex;
      gap: 32px;
      padding: 36px 0;
      border-bottom: 1px solid var(--border);
    }

    .step:last-child {
      border-bottom: none;
    }

    .step-num-wrap {
      flex-shrink: 0;
      width: 56px;
      height: 56px;
      border-radius: 50%;
      background: var(--bg-card);
      border: 1px solid var(--indigo-glow);
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: var(--font-m);
      font-size: 14px;
      font-weight: 700;
      color: var(--indigo-soft);
      position: relative;
      z-index: 1;
    }

    @media (max-width: 600px) {
      .step-num-wrap {
        width: 40px;
        height: 40px;
        font-size: 12px;
      }
    }

    .step-content {
      padding-top: 12px;
    }

    .step-title {
      font-family: var(--font-d);
      font-size: 20px;
      font-weight: 700;
      color: var(--text-1);
      margin-bottom: 8px;
    }

    .step-body {
      font-size: 15px;
      color: var(--text-2);
      line-height: 1.7;
      max-width: 600px;
    }

    .step-chips {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      margin-top: 14px;
    }

    .chip {
      font-family: var(--font-m);
      font-size: 11.5px;
      font-weight: 600;
      color: var(--indigo-soft);
      background: var(--indigo-dim);
      border: 1px solid var(--indigo-glow);
      padding: 5px 13px;
      border-radius: 20px;
    }

    /* ── BIG MARQUEE ── */
    .marquee-section {
      background: var(--bg);
      padding: 80px 0;
      overflow: hidden;
    }

    .marquee-track {
      display: flex;
      gap: 40px;
      white-space: nowrap;
      animation: marquee 28s linear infinite;
    }

    .marquee-track:hover {
      animation-play-state: paused;
    }

    .marquee-item {
      font-family: var(--font-d);
      font-size: clamp(28px, 4vw, 48px);
      font-weight: 700;
      color: transparent;
      -webkit-text-stroke: 1px var(--indigo-glow);
      flex-shrink: 0;
    }

    .marquee-item.filled {
      color: var(--text-1);
      -webkit-text-stroke: 0;
    }

    @keyframes marquee {
      from {
        transform: translateX(0);
      }

      to {
        transform: translateX(-50%);
      }
    }

    /* ── FINAL CTA ── */
    .final-cta {
      background: var(--bg);
      text-align: center;
      padding: 140px 24px;
      position: relative;
      overflow: hidden;
    }

    .final-cta::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 700px;
      height: 700px;
      background: radial-gradient(circle, rgba(108, 99, 255, 0.14) 0%, transparent 65%);
      pointer-events: none;
    }

    .final-cta .section-title {
      margin: 0 auto 24px;
      text-align: center;
    }

    .final-cta .section-sub {
      margin: 0 auto 40px;
      text-align: center;
    }

    .final-cta .hero-cta {
      margin-top: 0;
    }

    /* ── FOOTER ── */
    footer {
      border-top: 1px solid var(--border);
      padding: 40px 48px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 20px;
    }

    .footer-copy {
      font-size: 13px;
      color: var(--text-3);
    }

    /* ── SCROLL REVEAL ── */
    .reveal {
      opacity: 0;
      transform: translateY(24px);
      transition: opacity 0.65s ease, transform 0.65s ease;
    }

    .reveal.visible {
      opacity: 1;
      transform: none;
    }

    @media (prefers-reduced-motion: reduce) {
      .reveal {
        opacity: 1;
        transform: none;
        transition: none;
      }

      .marquee-track {
        animation: none;
      }

      @keyframes float {

        0%,
        100% {
          transform: translateX(-50%);
        }
      }
    }
  </style>
</head>

<body>

  <!-- ── NAV ── -->
  <nav class="cnv-pagenav">
    <a href="#home" class="cnv-pagenav-link active" data-target="home">
        <span class="cnv-pagenav-dot"></span>
        <span class="cnv-pagenav-label">Home</span>
    </a>
    <a href="#how" class="cnv-pagenav-link" data-target="how">
        <span class="cnv-pagenav-dot"></span>
        <span class="cnv-pagenav-label">How it Works</span>
    </a>
    <a href="#faq" class="cnv-pagenav-link" data-target="faq">
        <span class="cnv-pagenav-dot"></span>
        <span class="cnv-pagenav-label">FAQ</span>
    </a>
</nav>

  <!-- ── HERO ── -->
  <section class="hero" id="home">
    <video class="hero-bg-video" autoplay muted loop playsinline>
      <source
        src="https://dl.dropboxusercontent.com/s/7hfmpplgj4dxqp1dynv7h/hero-bg.mp4?rlkey=w6un8bm0vm8hxlscbd8rljr1c&st=l98ewe0x&dl=0"
        type="video/mp4">
    </video>

    <div class="hero-bg-orb orb-1"></div>
    <div class="hero-bg-orb orb-2"></div>

    <p class="hero-eyebrow">AI CAREER NAVIGATOR</p>
    <h1 class="hero-title">Your skills already know<br>where you <em>belong.</em></h1>


    <div class="hero-cta">
      <a href="{{ route('register') }}" class="btn-primary">
        Create Account
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
          stroke-linecap="round" stroke-linejoin="round">
          <path d="M5 12h14M12 5l7 7-7 7" />
        </svg>
      </a>
      <a href="{{ route('login') }}" class="btn-ghost">Log In</a>
    </div>

    <div class="hero-scroll-hint">
      <span>Scroll to discover</span>
      <div class="scroll-arrow"></div>
    </div>
  </section>

  <div class="divider"></div>

  <!-- ── INTRO TEXT ── -->
  <section class="intro-text">
    <div class="container">
      <p class="big-label reveal">But what does it actually do?</p>
      <p class="large-copy reveal">
        AI Career Navigator reads your resume, identifies every skill and strength, and scores them against
        <span>hundreds of real career paths</span> — then connects you with live job openings that match who you already
        are.
      </p>
    </div>
  </section>

  <div class="divider"></div>

  <!-- ── STATS ── -->
  <div class="stats-strip">
    <div class="stats-grid">
      <div class="reveal">
        <div class="stat-val">3 sec</div>
        <div class="stat-label">Average resume parse time</div>
      </div>
      <div class="reveal">
        <div class="stat-val">200+</div>
        <div class="stat-label">Career paths matched</div>
      </div>
      <div class="reveal">
        <div class="stat-val">Live</div>
        <div class="stat-label">Job listings, always current</div>
      </div>
    </div>
  </div>

  <!-- ── HOW IT WORKS ── -->
  <section class="how" id="how">
    <div class="container">
      <p class="section-eyebrow reveal">HOW IT WORKS</p>
      <h2 class="section-title reveal">Four steps from resume to real opportunity.</h2>

      <div class="steps">
        <div class="step reveal">
          <div class="step-num-wrap">01</div>
          <div class="step-content">
            <h3 class="step-title">Upload Your Resume</h3>
            <p class="step-body">Drop in your PDF. Our parser reads what matters — experience, tools, projects, and soft
              skills — and discards the noise.</p>
          </div>
        </div>
        <div class="step reveal">
          <div class="step-num-wrap">02</div>
          <div class="step-content">
            <h3 class="step-title">Skills, Surfaced Automatically</h3>
            <p class="step-body">Every technology, framework, and strength is extracted and tagged. No manual input
              required.</p>
            <div class="step-chips">
              <span class="chip">Laravel</span>
              <span class="chip">JavaScript</span>
              <span class="chip">SQL</span>
              <span class="chip">REST APIs</span>
              <span class="chip">Problem Solving</span>
            </div>
          </div>
        </div>
        <div class="step reveal">
          <div class="step-num-wrap">03</div>
          <div class="step-content">
            <h3 class="step-title">Scored Against Real Careers</h3>
            <p class="step-body">Your skill profile is matched and scored against hundreds of career paths. You get a
              clear match percentage and a ranked shortlist — no guessing, no bias.</p>
          </div>
        </div>
        <div class="step reveal">
          <div class="step-num-wrap">04</div>
          <div class="step-content">
            <h3 class="step-title">Walk Into Live Openings</h3>
            <p class="step-body">Live job listings are fetched and filtered to your top career match. Apply directly,
              with confidence your profile already fits.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <div class="divider"></div>

  <!-- ── PHASES ── -->
  <section class="phases">
    <div class="container">
      <p class="section-eyebrow reveal">WHAT'S COMING</p>
      <h2 class="section-title reveal">We're just getting started.</h2>
      <p class="section-sub reveal">The platform is built to grow with you. Here's what's already here — and what's
        next.</p>

      <div class="phases-grid">
        <div class="phase-card reveal">
          <div class="phase-num">01</div>
          <h3 class="phase-title">Resume Intelligence & Career Matching</h3>
          <p class="phase-body">Upload any resume, get a full skill breakdown and a scored match list against real
            career paths. Live job results follow automatically from your top match.</p>
          <span class="phase-tag">LIVE NOW</span>
        </div>
        <div class="phase-card reveal">
          <div class="phase-num">02</div>
          <h3 class="phase-title">Gap Analysis & Learning Paths</h3>
          <p class="phase-body">See exactly which skills you're missing for your dream role — and get curated learning
            paths to close the gap, with progress tracking built in.</p>
          <span class="phase-tag">LIVE NOW</span>
        </div>
        <div class="phase-card reveal">
          <div class="phase-num">03</div>
          <h3 class="phase-title">ATS Scoring & Optimization</h3>
          <p class="phase-body"> Optimize your resume by mirroring exact keywords and specific tools
            from the job description. Eliminate tables, graphics, and text boxes to ensure clean parsing, and track
            progress using a 100-point checklist.</p>
          <span class="phase-tag"
            style="color:var(--indigo-soft);background:var(--indigo-dim);border-color:var(--indigo-glow);">COMING
            SOON</span>
        </div>
      </div>
    </div>
  </section>

  <!-- ── MARQUEE ── -->
  <div class="marquee-section">
    <div class="marquee-track">
      <span class="marquee-item filled">Find your match.</span>
      <span class="marquee-item">•</span>
      <span class="marquee-item">Skill up faster.</span>
      <span class="marquee-item">•</span>
      <span class="marquee-item filled">Apply with confidence.</span>
      <span class="marquee-item">•</span>
      <span class="marquee-item">Know your worth.</span>
      <span class="marquee-item">•</span>
      <!-- duplicate for seamless loop -->
      <span class="marquee-item filled">Find your match.</span>
      <span class="marquee-item">•</span>
      <span class="marquee-item">Skill up faster.</span>
      <span class="marquee-item">•</span>
      <span class="marquee-item filled">Apply with confidence.</span>
      <span class="marquee-item">•</span>
      <span class="marquee-item">Know your worth.</span>
      <span class="marquee-item">•</span>
    </div>
  </div>

  <!-- ── FAQ ── -->
  <section class="faq" id="faq">
    <div class="container">
      <p class="section-eyebrow reveal">QUESTIONS</p>
      <h2 class="section-title reveal">We've got answers.</h2>

      <div class="faq-list">

        <div class="faq-item reveal">
          <button class="faq-q" aria-expanded="false">
            <span class="faq-q-text">How accurate is the career matching?</span>
            <span class="faq-icon"><svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M12 5v14M5 12h14" />
              </svg></span>
          </button>
          <div class="faq-a">
            <p class="faq-a-inner">Our AI model is trained on thousands of real job descriptions and resume profiles.
              Match scores reflect genuine skill overlap — not keyword counting. Most users find their top three results
              closely match where they actually want to work.</p>
          </div>
        </div>

        <div class="faq-item reveal">
          <button class="faq-q" aria-expanded="false">
            <span class="faq-q-text">Is my resume data stored or shared?</span>
            <span class="faq-icon"><svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M12 5v14M5 12h14" />
              </svg></span>
          </button>
          <div class="faq-a">
            <p class="faq-a-inner">Your resume is processed securely and used only to generate your skill profile. We do
              not sell or share your data with third parties. You can delete your data at any time from your account
              settings.</p>
          </div>
        </div>

        <div class="faq-item reveal">
          <button class="faq-q" aria-expanded="false">
            <span class="faq-q-text">What file formats are supported?</span>
            <span class="faq-icon"><svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M12 5v14M5 12h14" />
              </svg></span>
          </button>
          <div class="faq-a">
            <p class="faq-a-inner">We currently support PDF resumes. Word (.docx) and plain text (.txt) support is
              coming in the next update. If your resume is in another format, exporting as PDF from any word processor
              takes under a minute.</p>
          </div>
        </div>

        <div class="faq-item reveal">
          <button class="faq-q" aria-expanded="false">
            <span class="faq-q-text">Are the job listings really live?</span>
            <span class="faq-icon"><svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M12 5v14M5 12h14" />
              </svg></span>
          </button>
          <div class="faq-a">
            <p class="faq-a-inner">Yes. Job results are fetched in real time from live boards each time you view them —
              not pulled from a static database. This means you see current openings, not listings that closed weeks
              ago.</p>
          </div>
        </div>

        <div class="faq-item reveal">
          <button class="faq-q" aria-expanded="false">
            <span class="faq-q-text">Is it free to use?</span>
            <span class="faq-icon"><svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M12 5v14M5 12h14" />
              </svg></span>
          </button>
          <div class="faq-a">
            <p class="faq-a-inner">Creating an account and running your first career match is completely free. Advanced
              features — like detailed gap analysis and learning paths — will be available as part of an upcoming
              premium plan.</p>
          </div>
        </div>

      </div>
    </div>
  </section>

  <div class="divider"></div>

  <!-- ── FINAL CTA ── -->
  <section class="final-cta">
    <p class="section-eyebrow reveal">YOUR TURN</p>
    <h2 class="section-title reveal">Let's find your match.</h2>
    <p class="section-sub reveal">Your resume already tells a story. We just help you read it the right way.</p>
    <div class="hero-cta reveal">
      <a href="{{ route('register') }}" class="btn-primary">
        Create Free Account
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
          stroke-linecap="round" stroke-linejoin="round">
          <path d="M5 12h14M12 5l7 7-7 7" />
        </svg>
      </a>
      <a href="{{ route('login') }}" class="btn-ghost">Log In</a>
    </div>
  </section>

  <!-- ── FOOTER ── -->
  <footer>
    <p class="footer-copy">© 2026 AI Career Navigator. All rights reserved.</p>

  </footer>

  <script>
    // ── Scroll reveal ──
    const reveals = document.querySelectorAll('.reveal');
    const io = new IntersectionObserver((entries) => {
      entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); io.unobserve(e.target); } });
    }, { threshold: 0.12 });
    reveals.forEach(el => io.observe(el));

    // ── FAQ accordion ──
    document.querySelectorAll('.faq-q').forEach(btn => {
      btn.addEventListener('click', () => {
        const item = btn.closest('.faq-item');
        const isOpen = item.classList.contains('open');
        document.querySelectorAll('.faq-item.open').forEach(el => {
          el.classList.remove('open');
          el.querySelector('.faq-a').style.maxHeight = '0';
          el.querySelector('.faq-q').setAttribute('aria-expanded', 'false');
        });
        if (!isOpen) {
          item.classList.add('open');
          const ans = item.querySelector('.faq-a');
          ans.style.maxHeight = ans.scrollHeight + 'px';
          btn.setAttribute('aria-expanded', 'true');
        }
      });
    });
    
    var pagenavLinks = document.querySelectorAll('.cnv-pagenav-link');
    var pagenavSections = ['home', 'how', 'faq']
        .map(function (id) { return document.getElementById(id); })
        .filter(Boolean);

    var pagenavObserver = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                var id = entry.target.id;
                pagenavLinks.forEach(function (link) {
                    link.classList.toggle('active', link.dataset.target === id);
                });
            }
        });
    }, { threshold: 0.4 });

    pagenavSections.forEach(function (section) { pagenavObserver.observe(section); });

  </script>

</body>

</html>