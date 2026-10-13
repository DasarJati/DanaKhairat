<!DOCTYPE html>
<html lang="ms">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DanaKhairat</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <script>document.documentElement.classList.add('js')</script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mona+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* ───────── Tokens ───────── */
        :root {
            --ink: #0f0f0f;
            --ink-2: #1a1a1a;
            --paper: #f2f2f2;
            --mute: #737373;
            --mute-on-dark: #b5b5b5;
            --line: rgba(15, 15, 15, .12);
            --line-on-dark: rgba(242, 242, 242, .14);
            --pad: clamp(20px, 6.3vw, 80px);
            --section: clamp(72px, 9.5vw, 120px);
            --ease: cubic-bezier(.16, 1, .3, 1);
            --ease-io: cubic-bezier(.65, 0, .35, 1);
        }

        html { scroll-behavior: smooth; }

        body.lp {
            margin: 0;
            background: var(--ink);
            color: var(--ink);
            font-family: 'Mona Sans', 'Instrument Sans', ui-sans-serif, system-ui, -apple-system, 'Segoe UI', sans-serif;
            font-size: 16px;
            line-height: 1.4;
            -webkit-font-smoothing: antialiased;
            overflow-x: clip;
        }

        /* Reset — :where() keeps specificity at zero so component rules always win */
        :where(.lp) *, :where(.lp) *::before, :where(.lp) *::after { box-sizing: border-box; }
        :where(.lp) :where(h1, h2, h3, h4, p, ul) { margin: 0; padding: 0; }
        :where(.lp) ul { list-style: none; }
        :where(.lp) a { color: inherit; text-decoration: none; }
        :where(.lp) img { display: block; max-width: 100%; }
        :where(.lp) button { font: inherit; color: inherit; background: none; border: 0; padding: 0; cursor: pointer; }

        /* ───────── Type ───────── */
        .lp-h1 { font-size: clamp(36px, 5.2vw, 60px); font-weight: 500; line-height: 1.1; letter-spacing: -.04em; }
        .lp-h2 { font-size: clamp(26px, 3.4vw, 40px); font-weight: 500; line-height: 1.1; letter-spacing: -.04em; }
        .lp-h3 { font-size: clamp(24px, 2.6vw, 32px); font-weight: 500; line-height: 1.2; letter-spacing: -.04em; }
        .lp-h4 { font-size: 24px; font-weight: 500; line-height: 1.2; letter-spacing: -.04em; }
        .lp-body { color: var(--mute); }
        .lp-dark .lp-body { color: var(--mute-on-dark); }

        /* ───────── Section shells ───────── */
        .lp-main { position: relative; z-index: 1; background: var(--ink); }
        .lp-main > * + * { margin-top: -1px; } /* no sub-pixel seams showing the sticky hero */
        .lp-section { padding: var(--section) var(--pad); }
        .lp-light { background: var(--paper); color: var(--ink); }
        .lp-dark { background: var(--ink); color: var(--paper); }
        .lp-wrap { max-width: 1440px; margin: 0 auto; }

        /* ───────── Label with pulsing dot ───────── */
        .lp-label { display: inline-flex; align-items: center; gap: 10px; padding: 4px; font-size: 14px; color: var(--mute); }
        .lp-dark .lp-label { color: #8a8a8a; }
        .lp-dot { position: relative; width: 10px; height: 10px; border-radius: 50%; background: currentColor; color: #000; flex: none; }
        .lp-dark .lp-dot { color: var(--paper); }
        .lp-dot::before {
            content: ''; position: absolute; inset: 0; border-radius: 50%; background: currentColor; opacity: .6;
            animation: lp-pulse 1.8s ease-out infinite;
        }
        @keyframes lp-pulse { from { transform: scale(1); opacity: .6; } to { transform: scale(2.4); opacity: 0; } }

        /* ───────── Text roll (hover) ───────── */
        .lp-roll { position: relative; display: inline-block; overflow: hidden; vertical-align: top; }
        .lp-roll > span { display: block; transition: transform .5s var(--ease-io); }
        .lp-roll > span::after { content: attr(data-text); position: absolute; left: 0; top: 100%; white-space: nowrap; }
        a:hover .lp-roll > span, button:hover .lp-roll > span, .lp-card:hover .lp-roll > span { transform: translateY(-100%); }

        /* ───────── Underlined arrow link ───────── */
        .lp-link { display: inline-flex; align-items: center; gap: 8px; padding: 2px 0; font-weight: 500; border-bottom: 1px solid currentColor; }
        .lp-link svg { width: 14px; height: 14px; transition: transform .5s var(--ease-io); }
        .lp-link:hover svg { transform: translate(3px, -3px); }

        /* ───────── Scroll reveal ───────── */
        .js [data-reveal] {
            opacity: 0; transform: translateY(80px);
            transition: opacity 1.1s var(--ease), transform 1.1s var(--ease);
            transition-delay: var(--d, 0s);
        }
        .js [data-reveal].is-in { opacity: 1; transform: none; }

        /* ───────── Nav ───────── */
        .lp-nav {
            position: fixed; inset: 0 0 auto 0; z-index: 50;
            display: grid; grid-template-columns: 1fr auto 1fr; align-items: center;
            padding: 20px var(--pad); color: var(--paper);
            transition: color .4s ease, opacity .9s var(--ease), transform .9s var(--ease);
        }
        .js .lp-nav { opacity: 0; transform: translateY(-24px); }
        .js .is-ready .lp-nav { opacity: 1; transform: none; }
        .lp-nav.is-light { color: var(--ink); }
        .lp-nav.is-open { color: var(--paper); }

        .lp-logo { position: relative; display: block; height: 40px; width: 126px; }
        .lp-logo img { position: absolute; inset: 0; height: 100%; width: auto; transition: opacity .4s ease; }
        .lp-logo .lp-logo-color { opacity: 0; }
        .lp-nav.is-light:not(.is-open) .lp-logo .lp-logo-color { opacity: 1; }
        .lp-nav.is-light:not(.is-open) .lp-logo .lp-logo-white { opacity: 0; }

        .lp-nav-links { display: flex; gap: 24px; }
        .lp-nav-links a { opacity: .75; transition: opacity .3s ease; }
        .lp-nav-links a:hover { opacity: 1; }
        .lp-nav-cta { display: flex; justify-content: flex-end; align-items: center; gap: 24px; }

        .lp-burger { display: none; width: 40px; height: 40px; position: relative; justify-self: end; }
        .lp-burger span { position: absolute; left: 8px; right: 8px; height: 1.5px; background: currentColor; transition: transform .5s var(--ease), top .5s var(--ease); }
        .lp-burger span:nth-child(1) { top: 15px; }
        .lp-burger span:nth-child(2) { top: 24px; }
        .is-open .lp-burger span:nth-child(1) { top: 19.5px; transform: rotate(45deg); }
        .is-open .lp-burger span:nth-child(2) { top: 19.5px; transform: rotate(-45deg); }

        .lp-menu {
            position: fixed; inset: 0; z-index: 40; background: var(--ink); color: var(--paper);
            display: flex; flex-direction: column; justify-content: flex-end; gap: 40px;
            padding: 120px var(--pad) 40px;
            clip-path: inset(0 0 100% 0); visibility: hidden;
            transition: clip-path .8s var(--ease), visibility 0s .8s;
        }
        .lp-menu.is-open { clip-path: inset(0); visibility: visible; transition: clip-path .8s var(--ease); }
        .lp-menu ul a { display: block; overflow: hidden; font-size: clamp(36px, 11vw, 60px); font-weight: 500; letter-spacing: -.04em; line-height: 1.15; }
        .lp-menu ul a span { display: block; transform: translateY(110%); transition: transform .9s var(--ease); transition-delay: calc(var(--i) * 60ms + 150ms); }
        .lp-menu.is-open ul a span { transform: none; }
        .lp-menu-foot { display: flex; justify-content: space-between; align-items: center; padding-top: 24px; border-top: 1px solid var(--line-on-dark); }

        /* ───────── Hero ───────── */
        .lp-hero {
            position: sticky; top: 0; z-index: 0; height: 100vh; height: 100svh; min-height: 560px;
            overflow: clip; color: var(--paper); background: var(--ink);
            display: flex; flex-direction: column; justify-content: flex-end;
            padding: 0 var(--pad) clamp(20px, 3vw, 40px);
        }
        .lp-hero-bg { position: absolute; inset: 0; }
        .lp-hero-bg img {
            width: 100%; height: 100%; object-fit: cover;
            transform: scale(calc(1.02 + var(--hp, 0) * .1)); transform-origin: 50% 60%;
            transition: opacity 1.6s ease;
        }
        .js .lp-hero-bg img { opacity: 0; }
        .js .is-ready .lp-hero-bg img { opacity: 1; }
        .lp-hero-bg::after {
            content: ''; position: absolute; inset: 0;
            background:
                linear-gradient(to top, rgba(15, 15, 15, .92) 0%, rgba(15, 15, 15, .55) 45%, rgba(15, 15, 15, .6) 100%),
                rgba(15, 15, 15, calc(var(--hp, 0) * .6));
        }
        .lp-hero-copy { position: relative; display: flex; flex-direction: column; align-items: flex-start; gap: 32px; margin-bottom: 24px; }
        .lp-hero-copy p { max-width: 310px; color: rgba(242, 242, 242, .85); }
        .js .lp-hero-copy > * { opacity: 0; transform: translateY(40px); transition: opacity 1.1s var(--ease), transform 1.1s var(--ease); }
        .js .lp-hero-copy > :nth-child(2) { transition-delay: .12s; }
        .js .is-ready .lp-hero-copy > * { opacity: 1; transform: none; }

        /* Giant wordmark (hero + footer) */
        .lp-word { position: relative; width: 100%; overflow: hidden; line-height: 1; }
        .lp-fit { display: inline-block; white-space: nowrap; font-size: 15vw; font-weight: 600; letter-spacing: -.06em; line-height: 1.02; }
        .lp-ch { display: inline-block; }
        .js .lp-ch { transform: translateY(105%); transition: transform 1.2s var(--ease); transition-delay: calc(var(--i) * 45ms + 200ms); }
        .js .is-ready .lp-hero .lp-ch, .js .lp-word.is-in .lp-ch { transform: none; }

        /* ───────── About ───────── */
        .lp-about-top { display: flex; justify-content: space-between; align-items: flex-start; gap: 32px; }
        .lp-about-top .lp-h2 { width: 66%; }
        .lp-stats { width: 66%; margin: 56px 0 0 auto; display: flex; justify-content: space-between; gap: 24px; }
        .lp-stat { display: flex; flex-direction: column; align-items: center; gap: 16px; text-align: center; }
        .lp-stat-num { font-size: clamp(52px, 8vw, 100px); font-weight: 500; letter-spacing: -.05em; line-height: 1; display: flex; }
        .lp-stat p { font-size: clamp(15px, 1.6vw, 20px); letter-spacing: -.02em; color: var(--mute); white-space: nowrap; }
        .lp-stat-num { white-space: nowrap; }
        .lp-odo { display: inline-flex; }
        .lp-odo-d { display: block; flex: none; height: 1em; overflow: hidden; }
        .lp-odo-col { display: flex; flex-direction: column; transition: transform 2s var(--ease); transition-delay: calc(var(--i) * 120ms); }
        .lp-odo-col span { display: block; height: 1em; line-height: 1; }
        .is-in .lp-odo-col { transform: translateY(calc(var(--n) * -1em)); }

        /* ───────── Section header ───────── */
        .lp-head { display: flex; flex-direction: column; gap: 8px; margin-bottom: 56px; }
        .lp-head-row { display: flex; justify-content: space-between; align-items: flex-end; gap: 24px; }
        .lp-head-row .lp-body { max-width: 380px; }

        /* ───────── Sahabat grid ───────── */
        .lp-rows { display: flex; flex-direction: column; gap: 80px; }
        .lp-row { display: flex; justify-content: space-between; align-items: center; gap: 24px; }
        .lp-row.is-center { justify-content: center; }
        .lp-card { display: flex; flex-direction: column; gap: 8px; }
        .lp-card.is-tall { width: 40%; }
        .lp-card.is-wide { width: 50%; }
        .lp-card.is-full { width: 83%; }
        .lp-card.is-down { margin-top: 120px; }
        .lp-card.is-up { margin-bottom: 120px; }
        .lp-card-img { overflow: hidden; background: #d9d9d9; }
        .lp-card.is-tall .lp-card-img { aspect-ratio: 442 / 618; }
        .lp-card.is-wide .lp-card-img { aspect-ratio: 552 / 330; }
        .lp-card.is-full .lp-card-img { aspect-ratio: 917 / 474; }
        .lp-card-img img { width: 100%; height: 100%; object-fit: cover; transform: scale(1.02); transition: transform 1.2s var(--ease); }
        .lp-card:hover .lp-card-img img { transform: scale(1.08); }
        .lp-card-meta { display: flex; justify-content: space-between; gap: 16px; font-weight: 500; }
        .lp-card-meta .lp-roll { flex: 1; min-width: 0; }
        .lp-card-meta > span:last-child { color: var(--mute); font-weight: 400; white-space: nowrap; }
        .lp-center { display: flex; justify-content: center; margin-top: 80px; }

        /* ───────── Why us (sticky stack) ───────── */
        .lp-why { display: flex; justify-content: space-between; align-items: flex-start; gap: 32px; }
        .lp-why-aside { position: sticky; top: 160px; width: 40%; display: flex; flex-direction: column; gap: 8px; padding: 16px 16px 16px 0; }
        .lp-why-aside .lp-body { margin-top: 16px; max-width: 340px; }
        .lp-why-list { width: 57%; display: flex; flex-direction: column; gap: 200px; }
        .lp-feat {
            position: sticky; top: 160px; background: var(--ink);
            display: grid; grid-template-columns: 1fr 1fr; gap: 16px; padding: 16px; min-height: 366px;
        }
        .lp-feat-text { display: flex; flex-direction: column; justify-content: space-between; gap: 32px; }
        .lp-feat-text > div { display: flex; flex-direction: column; gap: 16px; }
        .lp-feat-text > div > span { margin-bottom: 8px; }
        .lp-feat-art { background: var(--ink-2); display: grid; place-items: center; min-height: 240px; overflow: hidden; }
        .lp-feat-art i { font-size: clamp(64px, 8vw, 104px); transition: transform 1s var(--ease); }
        .lp-feat:hover .lp-feat-art i { transform: scale(1.12) rotate(-4deg); }

        /* ───────── Process grid ───────── */
        .lp-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; }
        .lp-tile { height: 320px; overflow: hidden; }
        .lp-tile.is-text {
            background: var(--ink-2); padding: 16px; display: flex; flex-direction: column; justify-content: space-between;
            transition: background-color .5s ease, color .5s ease;
        }
        .lp-tile.is-text > div { display: flex; flex-direction: column; gap: 16px; }
        .lp-tile.is-text p { color: var(--mute-on-dark); transition: color .5s ease; }
        .lp-tile.is-text:hover { background: var(--paper); color: var(--ink); }
        .lp-tile.is-text:hover p { color: var(--mute); }
        .lp-tile.is-img { background: var(--ink-2); }
        .lp-tile.is-img img { width: 100%; height: 100%; object-fit: cover; transition: transform 1.2s var(--ease); }
        .lp-tile.is-img:hover img { transform: scale(1.06); }

        /* ───────── FAQ ───────── */
        .lp-faq { display: flex; justify-content: space-between; align-items: flex-start; gap: 40px; }
        .lp-faq-aside { width: 40%; display: flex; flex-direction: column; gap: 8px; }
        .lp-faq-aside .lp-body { margin-top: 16px; max-width: 320px; }
        .lp-faq-list { width: 51%; display: flex; flex-direction: column; }
        .lp-faq-item { border-bottom: 1px solid var(--line); }
        .lp-faq-q { width: 100%; display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; padding: 24px 12px; text-align: left; font-size: 20px; font-weight: 500; letter-spacing: -.02em; line-height: 1.2; }
        .lp-faq-q svg { width: 24px; height: 24px; flex: none; transition: transform .6s var(--ease); }
        .lp-faq-item.is-open .lp-faq-q svg { transform: rotate(45deg); }
        .lp-faq-a { display: grid; grid-template-rows: 0fr; transition: grid-template-rows .6s var(--ease); }
        .lp-faq-item.is-open .lp-faq-a { grid-template-rows: 1fr; }
        .lp-faq-a > div { overflow: hidden; }
        .lp-faq-a p { padding: 0 52px 24px 12px; color: var(--mute); }

        /* ───────── CTA ───────── */
        .lp-cta { position: relative; overflow: clip; color: var(--paper); padding: var(--pad); min-height: min(640px, 90vh); display: grid; place-items: center; text-align: center; background: var(--ink); }
        .lp-cta-bg { position: absolute; inset: -12% 0; }
        .lp-cta-bg img { width: 100%; height: 100%; object-fit: cover; transform: translateY(calc(var(--cp, 0) * 10%)); }
        .lp-cta-bg::after { content: ''; position: absolute; inset: 0; background: rgba(15, 15, 15, .55); }
        .lp-cta-inner { position: relative; display: flex; flex-direction: column; align-items: center; gap: 24px; }
        .lp-cta h2 { font-size: clamp(40px, 6.6vw, 76px); font-weight: 500; letter-spacing: -.04em; line-height: 1; max-width: 12ch; }
        .lp-cta p { font-size: clamp(16px, 1.7vw, 20px); letter-spacing: -.02em; color: var(--mute-on-dark); }
        .lp-cta .lp-link { margin-top: 24px; }

        /* ───────── Footer ───────── */
        .lp-footer { padding: 80px var(--pad) 0; }
        .lp-foot-top { display: flex; justify-content: space-between; align-items: flex-start; gap: 48px; }
        .lp-foot-top > p { max-width: 380px; font-size: clamp(20px, 2.2vw, 28px); font-weight: 500; letter-spacing: -.03em; line-height: 1.2; }
        .lp-foot-cols { display: flex; gap: clamp(32px, 6vw, 80px); }
        .lp-foot-cols ul { display: flex; flex-direction: column; gap: 8px; }
        .lp-foot-cols li { color: var(--mute-on-dark); max-width: 260px; }
        .lp-foot-cols a { transition: color .3s ease; }
        .lp-foot-cols a:hover { color: var(--paper); }
        .lp-foot-bottom { display: flex; justify-content: space-between; gap: 16px; margin: 80px 0; color: var(--mute-on-dark); font-size: 14px; }
        .lp-footer .lp-word { padding-bottom: .04em; }

        /* ───────── Responsive ───────── */
        @media (max-width: 1023px) {
            .lp-nav { grid-template-columns: 1fr auto; }
            .lp-nav-links, .lp-nav-cta { display: none; }
            .lp-burger { display: block; }
            .lp-about-top { flex-direction: column; }
            .lp-about-top .lp-h2, .lp-stats { width: 100%; }
            .lp-why, .lp-faq { flex-direction: column; }
            .lp-why-aside { position: static; width: 100%; padding: 0; margin-bottom: 24px; }
            .lp-why-list { width: 100%; gap: 120px; }
            .lp-feat { top: 96px; }
            .lp-faq-aside, .lp-faq-list { width: 100%; }
            .lp-grid { grid-template-columns: repeat(2, 1fr); }
            .lp-foot-top { flex-direction: column; }
        }

        @media (max-width: 767px) {
            /* Portrait crop: keep the imam and coffin (right side of the photo) in view */
            .lp-hero-bg img { object-position: 76% 50%; transform-origin: 76% 60%; }
            .lp-head { margin-bottom: 40px; }
            .lp-head-row { flex-direction: column; align-items: flex-start; }
            .lp-stats { flex-direction: column; align-items: flex-start; gap: 32px; }
            .lp-stat { align-items: flex-start; text-align: left; gap: 8px; }
            .lp-rows { gap: 40px; }
            .lp-row { flex-direction: column; gap: 40px; }
            .lp-card.is-tall, .lp-card.is-wide, .lp-card.is-full { width: 100%; }
            .lp-card.is-down, .lp-card.is-up { margin: 0; }
            .lp-card.is-tall .lp-card-img { aspect-ratio: 4 / 5; }
            .lp-card.is-wide .lp-card-img, .lp-card.is-full .lp-card-img { aspect-ratio: 4 / 3; }
            .lp-center { margin-top: 56px; }
            .lp-why-list { gap: 16px; }
            .lp-feat { position: static; grid-template-columns: 1fr; min-height: 0; padding: 24px 0; border-top: 1px solid var(--line-on-dark); }
            .lp-feat-art { min-height: 180px; order: -1; }
            .lp-grid { grid-template-columns: 1fr; }
            .lp-tile { height: 260px; }
            .lp-tile.is-img { height: 200px; }
            .lp-faq-q { padding: 20px 0; font-size: 18px; }
            .lp-faq-a p { padding: 0 40px 20px 0; }
            .lp-foot-cols { flex-direction: column; }
            .lp-foot-bottom { flex-direction: column; margin: 56px 0; }
        }

        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            .lp *, .lp *::before, .lp *::after { animation: none !important; transition-duration: .01ms !important; transition-delay: 0s !important; }
            .js [data-reveal], .js .lp-ch, .js .lp-hero-copy > *, .js .lp-nav { opacity: 1; transform: none; }
            .lp-hero-bg img, .lp-cta-bg img { transform: none; }
        }
    </style>
</head>

@php
    $arrow = '<svg viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M2.5 11.5l9-9M4.5 2.5h7v7"/></svg>';

    $navLinks = [
        ['label' => 'Utama', 'href' => '#hero'],
        ['label' => 'Tentang Kami', 'href' => '#us'],
        ['label' => 'Sahabat', 'href' => '#sahabat'],
        ['label' => 'Status Sahabat', 'href' => '/checkstatus'],
        ['label' => 'Hubungi', 'href' => '#footer'],
    ];

    $stats = [
        ['value' => '7', 'suffix' => '+', 'label' => 'Sahabat Kariah'],
        ['value' => '7800', 'suffix' => '+', 'label' => 'Ahli Berdaftar'],
        ['value' => '100', 'suffix' => '%', 'label' => 'Urusan Digital'],
    ];

    // Setiap baris: [bentuk kad, kad...]
    $sahabatRows = [
        [
            ['shape' => 'is-tall', 'img' => 'images/putra.jpg', 'name' => 'Masjid Putra', 'place' => 'Putrajaya, Wilayah Persekutuan', 'ahli' => '1200'],
            ['shape' => 'is-wide is-down', 'img' => 'images/tnb.jpg', 'name' => 'Masjid TNB Bangsar', 'place' => 'Bangsar, Kuala Lumpur', 'ahli' => '800'],
        ],
        [
            ['shape' => 'is-full', 'img' => 'images/sendayan.jpg', 'name' => 'Masjid Sri Sendayan', 'place' => 'Seremban, Negeri Sembilan', 'ahli' => '3000'],
        ],
        [
            ['shape' => 'is-wide is-up', 'img' => 'images/kelantan.jpeg', 'name' => 'Masjid Andalusia', 'place' => 'Kota Bharu, Kelantan', 'ahli' => '2000'],
            ['shape' => 'is-tall', 'img' => 'images/pinang.jpeg', 'name' => 'Surau Al-Muttaqin', 'place' => 'Lilitan Sg Ara, Penang', 'ahli' => '200'],
        ],
        [
            ['shape' => 'is-wide', 'img' => 'images/nur.jpg', 'name' => 'Surau Nur Ramadhan', 'place' => 'Kajang, Sg Merab', 'ahli' => '450'],
            ['shape' => 'is-wide', 'img' => 'images/shahalam.jpg', 'name' => 'Surau Al-Mawaddah', 'place' => 'Seksyen 7, Shah Alam', 'ahli' => '150'],
        ],
    ];

    $steps = [
        ['title' => 'Daftar Ahli Digital', 'description' => 'Isi maklumat diri secara online dengan selamat dan pantas tanpa perlu borang fizikal.'],
        ['title' => 'Proses Pembayaran', 'description' => 'Lengkapkan bayaran yuran, dan keahlian anda akan aktif dalam masa yang singkat.'],
        ['title' => 'Lindungan Aktif', 'description' => 'Perlindungan khairat automatik aktif sejurus selepas pembayaran disahkan.'],
        ['title' => 'Pengurusan Kematian', 'description' => 'Pengurusan kematian yang lebih cepat, sistematik dan telus apabila diperlukan.'],
    ];

    // Susunan papan dam: teks / gambar berselang-seli
    $tiles = [
        ['step' => 0], ['img' => 'images/jubin-1-daftar.jpg'], ['step' => 1], ['img' => 'images/jubin-2-bayaran.jpg'],
        ['img' => 'images/jubin-3-lindungan.jpg'], ['step' => 2], ['img' => 'images/jubin-4-pengurusan-kematian.jpg'], ['step' => 3],
    ];

    $faqs = [
        ['q' => 'Bagaimana cara mendaftar sebagai ahli khairat?', 'a' => 'Klik "Daftar Akaun", pilih jenis pendaftaran dan isi maklumat diri anda secara dalam talian. Permohonan akan disemak oleh AJK masjid atau surau sebelum akaun diaktifkan.'],
        ['q' => 'Bagaimana masjid atau surau kami boleh menyertai DanaKhairat?', 'a' => 'AJK boleh mendaftar institusi melalui "Daftar Akaun", kemudian menyemak status permohonan di halaman Status Sahabat.'],
        ['q' => 'Bagaimana bayaran yuran dibuat?', 'a' => 'Yuran dibayar secara dalam talian melalui portal, dan setiap transaksi direkodkan supaya mudah disemak semula.'],
        ['q' => 'Bolehkah saya mendaftarkan tanggungan?', 'a' => 'Boleh. Selepas log masuk, tambah ahli keluarga sebagai tanggungan terus daripada akaun anda.'],
        ['q' => 'Bagaimana tuntutan khairat dibuat?', 'a' => 'Tuntutan dihantar melalui akaun ahli dan diproses oleh AJK masjid. Status tuntutan boleh disemak di dalam portal.'],
    ];
@endphp

<body class="lp">

    {{-- Nav --}}
    <header class="lp-nav" id="lp-nav">
        <a href="/" class="lp-logo" aria-label="DanaKhairat">
            <img class="lp-logo-white" src="{{ asset('images/logoswhite.png') }}" alt="DanaKhairat">
            <img class="lp-logo-color" src="{{ asset('images/logos.png') }}" alt="" aria-hidden="true">
        </a>

        <nav class="lp-nav-links" aria-label="Utama">
            @foreach ($navLinks as $link)
                <a href="{{ $link['href'] }}"><span class="lp-roll"><span data-text="{{ $link['label'] }}">{{ $link['label'] }}</span></span></a>
            @endforeach
        </nav>

        <div class="lp-nav-cta">
            <a href="/login"><span class="lp-roll"><span data-text="Log Masuk">Log Masuk</span></span></a>
            <a href="/pilihan" class="lp-link"><span class="lp-roll"><span data-text="Daftar Akaun">Daftar Akaun</span></span>{!! $arrow !!}</a>
        </div>

        <button class="lp-burger" id="lp-burger" aria-label="Menu" aria-expanded="false" aria-controls="lp-menu">
            <span></span><span></span>
        </button>
    </header>

    {{-- Mobile menu --}}
    <div class="lp-menu" id="lp-menu">
        <ul>
            @foreach ($navLinks as $i => $link)
                <li><a href="{{ $link['href'] }}"><span style="--i: {{ $i }}">{{ $link['label'] }}</span></a></li>
            @endforeach
        </ul>
        <div class="lp-menu-foot">
            <a href="/login">Log Masuk</a>
            <a href="/pilihan" class="lp-link">Daftar Akaun {!! $arrow !!}</a>
        </div>
    </div>

    {{-- Hero --}}
    <section id="hero" class="lp-hero" data-theme="dark">
        <div class="lp-hero-bg">
            <img src="{{ asset('images/hero-b.jpg') }}" alt="">
        </div>

        <div class="lp-hero-copy">
            <p>Satu komuniti. Satu tujuan. Saling membantu ketika waktu memerlukan — selamat datang ke Portal Rasmi Khairat Kematian.</p>
            <a href="#us" class="lp-link"><span class="lp-roll"><span data-text="Ketahui Lebih Lanjut">Ketahui Lebih Lanjut</span></span>{!! $arrow !!}</a>
        </div>

        <div class="lp-word" aria-hidden="true"><span class="lp-fit" data-split>DanaKhairat®</span></div>
        <h1 class="sr-only" style="position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0)">DanaKhairat</h1>
    </section>

    <main class="lp-main">

        {{-- Tentang Kami --}}
        <section id="us" class="lp-section lp-light" data-theme="light">
            <div class="lp-wrap" data-reveal>
                <div class="lp-about-top">
                    <span class="lp-label"><i class="lp-dot"></i>Tentang Kami</span>
                    <h2 class="lp-h2">DanaKhairat ialah platform digital khairat kematian yang menghubungkan masjid, surau dan ahli kariah — pendaftaran, yuran dan tuntutan diurus dengan mudah, pantas dan telus.</h2>
                </div>
                <div class="lp-stats" data-count>
                    @foreach ($stats as $stat)
                        <div class="lp-stat">
                            <div class="lp-stat-num"><span class="lp-odo" data-value="{{ $stat['value'] }}"></span><span>{{ $stat['suffix'] }}</span></div>
                            <p>{{ $stat['label'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Sahabat Kariah --}}
        <section id="sahabat" class="lp-section lp-light" data-theme="light">
            <div class="lp-wrap">
                <div class="lp-head" data-reveal>
                    <span class="lp-label"><i class="lp-dot"></i>Sahabat</span>
                    <div class="lp-head-row">
                        <h2 class="lp-h1">Senarai Sahabat Kariah</h2>
                        <p class="lp-body">Masjid dan surau yang telah bersama membina jaringan sokongan sesama ahli.</p>
                    </div>
                </div>

                <div class="lp-rows">
                    @foreach ($sahabatRows as $row)
                        <div class="lp-row {{ count($row) === 1 ? 'is-center' : '' }}">
                            @foreach ($row as $i => $card)
                                <article class="lp-card {{ $card['shape'] }}" data-reveal style="--d: {{ $i * 0.12 }}s">
                                    <div class="lp-card-img">
                                        <img src="{{ asset($card['img']) }}" alt="{{ $card['name'] }}" loading="lazy">
                                    </div>
                                    <div class="lp-card-meta">
                                        <span class="lp-roll"><span data-text="{{ $card['place'] }}">{{ $card['name'] }}</span></span>
                                        <span>{{ $card['ahli'] }} Ahli</span>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @endforeach
                </div>

                <div class="lp-center" data-reveal>
                    <a href="/checkstatus" class="lp-link"><span class="lp-roll"><span data-text="Semak Status Sahabat">Semak Status Sahabat</span></span>{!! $arrow !!}</a>
                </div>
            </div>
        </section>

        {{-- Mengapa Kami --}}
        @include('partials.whyus')

        {{-- Proses --}}
        <section id="proses" class="lp-section lp-dark" data-theme="dark">
            <div class="lp-wrap">
                <div class="lp-head" data-reveal>
                    <span class="lp-label"><i class="lp-dot"></i>Proses</span>
                    <h2 class="lp-h1">Bagaimana DanaKhairat Membantu</h2>
                    <div class="lp-head-row" style="margin-top: 16px">
                        <p class="lp-body">Transformasi urusan khairat tradisional kepada digital dengan proses yang lebih mudah, pantas dan telus.</p>
                        <a href="/pilihan" class="lp-link"><span class="lp-roll"><span data-text="Daftar Akaun">Daftar Akaun</span></span>{!! $arrow !!}</a>
                    </div>
                </div>

                <div class="lp-grid">
                    @foreach ($tiles as $i => $tile)
                        @if (isset($tile['step']))
                            <div class="lp-tile is-text" data-reveal style="--d: {{ ($i % 4) * 0.08 }}s">
                                <span>/0{{ $tile['step'] + 1 }}</span>
                                <div>
                                    <h3 class="lp-h4">{{ $steps[$tile['step']]['title'] }}</h3>
                                    <p>{{ $steps[$tile['step']]['description'] }}</p>
                                </div>
                            </div>
                        @else
                            <div class="lp-tile is-img" data-reveal style="--d: {{ ($i % 4) * 0.08 }}s">
                                <img src="{{ asset($tile['img']) }}" alt="" loading="lazy">
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </section>

        {{-- FAQ --}}
        <section id="faq" class="lp-section lp-light" data-theme="light">
            <div class="lp-wrap lp-faq" data-reveal>
                <div class="lp-faq-aside">
                    <span class="lp-label"><i class="lp-dot"></i>Soalan Lazim</span>
                    <h2 class="lp-h1">Soalan Anda, Dijawab</h2>
                    <p class="lp-body">Jawapan ringkas kepada soalan yang paling kerap ditanya tentang DanaKhairat.</p>
                </div>

                <ul class="lp-faq-list">
                    @foreach ($faqs as $i => $faq)
                        <li class="lp-faq-item {{ $i === 0 ? 'is-open' : '' }}">
                            <button class="lp-faq-q" aria-expanded="{{ $i === 0 ? 'true' : 'false' }}">
                                <span>{{ $faq['q'] }}</span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                            </button>
                            <div class="lp-faq-a"><div><p>{{ $faq['a'] }}</p></div></div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>

        {{-- CTA --}}
        <section class="lp-cta" data-theme="dark" id="lp-cta">
            <div class="lp-cta-bg"><img src="{{ asset('images/cta.jpg') }}" alt="" loading="lazy"></div>
            <div class="lp-cta-inner" data-reveal>
                <h2>Sedia untuk Mulakan?</h2>
                <p>Daftar sekarang dan rasai kelainan urusan khairat digital</p>
                <a href="/pilihan" class="lp-link"><span class="lp-roll"><span data-text="Daftar Sekarang">Daftar Sekarang</span></span>{!! $arrow !!}</a>
            </div>
        </section>

        {{-- Footer --}}
        <footer id="footer" class="lp-footer lp-dark" data-theme="dark">
            <div class="lp-wrap">
                <div class="lp-foot-top" data-reveal>
                    <p>Platform digital khairat yang memudahkan pengurusan dana kematian dengan efisien, selamat dan telus.</p>

                    <div class="lp-foot-cols">
                        <ul>
                            @foreach ($navLinks as $link)
                                <li><a href="{{ $link['href'] }}">{{ $link['label'] }}</a></li>
                            @endforeach
                        </ul>
                        <ul>
                            <li><a href="/login">Log Masuk</a></li>
                            <li><a href="/pilihan">Daftar Akaun</a></li>
                        </ul>
                        <ul>
                            <li><a href="mailto:helpdesk@danakhairat.my">helpdesk@danakhairat.my</a></li>
                            <li><a href="tel:+60389275213">+03-8927 5213</a></li>
                            <li>Plaza Paragon Point, A-07-1, Seksyen 9, 43650 Bandar Baru Bangi, Selangor</li>
                        </ul>
                    </div>
                </div>

                <div class="lp-foot-bottom">
                    <span>&copy; {{ date('Y') }} DanaKhairat. Hak Cipta Terpelihara.</span>
                    <span>Web Ini Di Sediakan Oleh DasarJati Sdn. Bhd.</span>
                </div>

                <div class="lp-word" data-reveal-word aria-hidden="true"><span class="lp-fit" data-split>DanaKhairat®</span></div>
            </div>
        </footer>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const root = document.documentElement;
            const nav = document.getElementById('lp-nav');
            const burger = document.getElementById('lp-burger');
            const menu = document.getElementById('lp-menu');
            const cta = document.getElementById('lp-cta');

            // Split wordmark into letters for the rise-in animation
            document.querySelectorAll('[data-split]').forEach(el => {
                const chars = [...el.textContent];
                el.textContent = '';
                chars.forEach((ch, i) => {
                    const span = document.createElement('span');
                    span.className = 'lp-ch';
                    span.style.setProperty('--i', i);
                    span.textContent = ch;
                    el.appendChild(span);
                });
            });

            // Fit wordmark to the container width
            function fitWords() {
                document.querySelectorAll('.lp-fit').forEach(el => {
                    el.style.fontSize = '100px';
                    const ratio = el.parentElement.clientWidth / el.getBoundingClientRect().width;
                    el.style.fontSize = (100 * ratio) + 'px';
                });
            }
            fitWords();
            if (document.fonts && document.fonts.ready) document.fonts.ready.then(fitWords);
            window.addEventListener('resize', fitWords);

            // Odometer counters
            document.querySelectorAll('.lp-odo').forEach(el => {
                [...el.dataset.value].forEach((digit, i) => {
                    const wrap = document.createElement('span');
                    wrap.className = 'lp-odo-d';
                    const col = document.createElement('span');
                    col.className = 'lp-odo-col';
                    col.style.setProperty('--n', digit);
                    col.style.setProperty('--i', i);
                    for (let n = 0; n <= 9; n++) {
                        const s = document.createElement('span');
                        s.textContent = n;
                        col.appendChild(s);
                    }
                    wrap.appendChild(col);
                    el.appendChild(wrap);
                });
            });

            // Scroll reveal
            const targets = document.querySelectorAll('[data-reveal], [data-reveal-word], [data-count]');
            if ('IntersectionObserver' in window) {
                const io = new IntersectionObserver(entries => {
                    entries.forEach(entry => {
                        if (!entry.isIntersecting) return;
                        entry.target.classList.add('is-in');
                        io.unobserve(entry.target);
                    });
                }, { threshold: 0.08, rootMargin: '0px 0px -6% 0px' });
                targets.forEach(el => io.observe(el));
            } else {
                targets.forEach(el => el.classList.add('is-in'));
            }

            // Scroll-linked: hero dim/zoom, CTA parallax, nav colour per section
            const themed = [...document.querySelectorAll('[data-theme]')];
            let ticking = false;

            function onScroll() {
                ticking = false;
                const vh = window.innerHeight;
                root.style.setProperty('--hp', Math.min(1, window.scrollY / vh).toFixed(3));

                const cr = cta.getBoundingClientRect();
                if (cr.top < vh && cr.bottom > 0) {
                    const p = (vh - cr.top) / (vh + cr.height) - 0.5;
                    cta.style.setProperty('--cp', p.toFixed(3));
                }

                const probe = nav.offsetHeight / 2;
                let theme = 'dark';
                themed.forEach(sec => {
                    const r = sec.getBoundingClientRect();
                    if (r.top <= probe && r.bottom > probe) theme = sec.dataset.theme;
                });
                nav.classList.toggle('is-light', theme === 'light');
            }

            window.addEventListener('scroll', () => {
                if (!ticking) { ticking = true; requestAnimationFrame(onScroll); }
            }, { passive: true });
            window.addEventListener('resize', onScroll);
            onScroll();

            // Mobile menu
            function setMenu(open) {
                menu.classList.toggle('is-open', open);
                nav.classList.toggle('is-open', open);
                burger.setAttribute('aria-expanded', open);
                document.body.style.overflow = open ? 'hidden' : '';
            }
            burger.addEventListener('click', () => setMenu(!menu.classList.contains('is-open')));
            menu.querySelectorAll('a').forEach(a => a.addEventListener('click', () => setMenu(false)));
            window.addEventListener('resize', () => { if (window.innerWidth >= 1024) setMenu(false); });

            // FAQ accordion
            document.querySelectorAll('.lp-faq-item').forEach(item => {
                const btn = item.querySelector('.lp-faq-q');
                btn.addEventListener('click', () => {
                    const open = !item.classList.contains('is-open');
                    document.querySelectorAll('.lp-faq-item.is-open').forEach(other => {
                        other.classList.remove('is-open');
                        other.querySelector('.lp-faq-q').setAttribute('aria-expanded', 'false');
                    });
                    item.classList.toggle('is-open', open);
                    btn.setAttribute('aria-expanded', open);
                });
            });

            // Kick off the intro animation
            setTimeout(() => document.body.classList.add('is-ready'), 60);
        });
    </script>
</body>

</html>
