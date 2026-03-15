<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NOM-035 SaaS — Bienestar Laboral para Empresas Mexicanas</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --black:   #0A0A0A;
            --white:   #FFFFFF;
            --red:     #E8180C;
            --red-2:   #FF3B2F;
            --gray:    #F2F2F0;
            --gray-2:  #D4D4D0;
            --muted:   #7A7A72;
            --shadow:  6px 6px 0 var(--black);
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--white);
            color: var(--black);
            overflow-x: hidden;
            cursor: default;
        }

        /* ── MARQUEE TICKER ── */
        .ticker {
            background: var(--red);
            color: var(--white);
            padding: 8px 0;
            overflow: hidden;
            position: relative;
            z-index: 200;
        }
        .ticker-track {
            display: flex;
            gap: 0;
            white-space: nowrap;
            animation: ticker 20s linear infinite;
        }
        .ticker-item {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 13px;
            letter-spacing: 2px;
            padding: 0 32px;
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .ticker-item::after {
            content: '✦';
            font-size: 10px;
        }
        @keyframes ticker {
            from { transform: translateX(0); }
            to   { transform: translateX(-50%); }
        }

        /* ── NAV ── */
        nav {
            position: fixed;
            top: 34px;
            left: 0;
            right: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 48px;
            height: 64px;
            background: rgba(255,255,255,0.94);
            backdrop-filter: blur(16px);
            border-bottom: 2px solid var(--black);
            transition: top 0.3s;
        }
        nav.scrolled { top: 0; }

        .nav-logo {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 22px;
            letter-spacing: 3px;
            color: var(--black);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .nav-logo-dot {
            width: 8px; height: 8px;
            background: var(--red);
            border-radius: 50%;
            display: inline-block;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 36px;
        }
        .nav-links a {
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: color 0.2s;
        }
        .nav-links a:hover { color: var(--black); }

        .nav-actions { display: flex; gap: 12px; align-items: center; }

        .btn-ghost {
            padding: 9px 20px;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            color: var(--black);
            border: 2px solid var(--black);
            transition: all 0.2s;
            letter-spacing: 0.5px;
        }
        .btn-ghost:hover {
            background: var(--black);
            color: var(--white);
        }

        .btn-solid {
            padding: 9px 22px;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            color: var(--white);
            background: var(--red);
            border: 2px solid var(--red);
            transition: all 0.2s;
            letter-spacing: 0.5px;
            box-shadow: var(--shadow);
        }
        .btn-solid:hover {
            background: var(--black);
            border-color: var(--black);
            transform: translate(-2px, -2px);
            box-shadow: 8px 8px 0 var(--red);
        }

        /* ── HERO ── */
        .hero {
            min-height: 100vh;
            padding-top: 98px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            border-bottom: 2px solid var(--black);
            overflow: hidden;
        }

        .hero-left {
            padding: 80px 56px 80px 72px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            border-right: 2px solid var(--black);
            position: relative;
        }

        .hero-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--gray);
            border: 2px solid var(--black);
            padding: 6px 14px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 28px;
            width: fit-content;
        }
        .hero-tag-dot {
            width: 6px; height: 6px;
            background: var(--red);
            border-radius: 50%;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%,100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        .hero-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(64px, 7vw, 96px);
            line-height: 0.92;
            letter-spacing: 2px;
            margin-bottom: 24px;
        }
        .hero-title .red { color: var(--red); }
        .hero-title .outline {
            -webkit-text-stroke: 2px var(--black);
            color: transparent;
        }

        .hero-body {
            font-size: 16px;
            color: var(--muted);
            line-height: 1.7;
            max-width: 480px;
            margin-bottom: 40px;
        }

        .hero-ctas {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
        }

        .btn-hero-primary {
            padding: 16px 36px;
            font-family: 'Bebas Neue', sans-serif;
            font-size: 18px;
            letter-spacing: 2px;
            text-decoration: none;
            color: var(--white);
            background: var(--red);
            border: 2px solid var(--red);
            box-shadow: var(--shadow);
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        .btn-hero-primary:hover {
            background: var(--black);
            border-color: var(--black);
            transform: translate(-3px, -3px);
            box-shadow: 9px 9px 0 var(--red);
        }
        .btn-hero-primary svg { width: 16px; height: 16px; }

        .btn-hero-secondary {
            padding: 16px 36px;
            font-family: 'Bebas Neue', sans-serif;
            font-size: 18px;
            letter-spacing: 2px;
            text-decoration: none;
            color: var(--black);
            background: transparent;
            border: 2px solid var(--black);
            transition: all 0.2s;
        }
        .btn-hero-secondary:hover {
            background: var(--black);
            color: var(--white);
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0 var(--gray-2);
        }

        .hero-trust {
            margin-top: 44px;
            display: flex;
            align-items: center;
            gap: 16px;
            padding-top: 28px;
            border-top: 1px solid var(--gray-2);
        }
        .trust-avatars { display: flex; }
        .trust-avatar {
            width: 32px; height: 32px;
            border-radius: 50%;
            border: 2px solid var(--white);
            margin-left: -8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 800;
            color: white;
        }
        .trust-avatar:first-child { margin-left: 0; }
        .trust-text { font-size: 12px; color: var(--muted); line-height: 1.4; }
        .trust-text strong { color: var(--black); display: block; }

        /* Hero right — visual panel */
        .hero-right {
            background: var(--black);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            overflow: hidden;
        }
        .hero-right::before {
            content: 'NOM\A035';
            white-space: pre;
            position: absolute;
            font-family: 'Bebas Neue', sans-serif;
            font-size: 180px;
            line-height: 0.85;
            color: rgba(255,255,255,0.04);
            pointer-events: none;
            right: -20px;
            bottom: 20px;
            letter-spacing: 4px;
        }

        .hero-card {
            background: var(--white);
            border: 2px solid rgba(255,255,255,0.1);
            width: 100%;
            max-width: 380px;
            padding: 32px;
            position: relative;
            z-index: 1;
            box-shadow: 0 32px 64px rgba(0,0,0,0.4);
        }
        .hc-badge {
            background: var(--red);
            color: white;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 4px 12px;
            display: inline-block;
            margin-bottom: 20px;
        }
        .hc-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 28px;
            letter-spacing: 1px;
            margin-bottom: 20px;
        }

        .hc-ring-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 24px 0;
        }
        .hc-ring {
            width: 120px; height: 120px;
            border-radius: 50%;
            background: conic-gradient(#E8180C 0% 72%, #F2F2F0 72% 100%);
            display: flex; align-items: center; justify-content: center;
            position: relative;
        }
        .hc-ring::before {
            content: '';
            position: absolute;
            width: 84px; height: 84px;
            background: var(--white);
            border-radius: 50%;
        }
        .hc-ring-num {
            position: relative; z-index: 1;
            font-family: 'Bebas Neue', sans-serif;
            font-size: 32px;
            color: var(--black);
        }

        .hc-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 16px;
        }
        .hc-stat {
            background: var(--gray);
            padding: 12px;
        }
        .hc-stat-num {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 26px;
            color: var(--black);
            line-height: 1;
        }
        .hc-stat-label {
            font-size: 10px;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 2px;
        }
        .hc-risk-bars { margin-top: 16px; display: flex; flex-direction: column; gap: 8px; }
        .hc-bar-row { display: flex; align-items: center; gap: 8px; }
        .hc-bar-label { font-size: 10px; color: var(--muted); width: 56px; text-transform: uppercase; letter-spacing: 0.5px; }
        .hc-bar-track { flex: 1; height: 6px; background: var(--gray); }
        .hc-bar-fill   { height: 100%; }
        .bar-verde   { background: #22C55E; }
        .bar-amarillo{ background: #F59E0B; }
        .bar-rojo    { background: #E8180C; }
        .hc-bar-pct { font-size: 10px; font-weight: 700; color: var(--black); width: 28px; text-align: right; }

        /* ── STATS BAND ── */
        .stats-band {
            background: var(--black);
            display: grid;
            grid-template-columns: repeat(4,1fr);
            border-bottom: 2px solid var(--black);
        }
        .stat-block {
            padding: 48px 40px;
            border-right: 1px solid rgba(255,255,255,0.08);
            text-align: center;
            transition: background 0.2s;
        }
        .stat-block:last-child { border-right: none; }
        .stat-block:hover { background: rgba(232,24,12,0.08); }
        .stat-num {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 56px;
            color: var(--red);
            line-height: 1;
            display: block;
        }
        .stat-lbl {
            font-size: 13px;
            color: rgba(255,255,255,0.5);
            margin-top: 6px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* ── HOW IT WORKS ── */
        .how {
            padding: 100px 72px;
            border-bottom: 2px solid var(--black);
        }
        .section-eyebrow {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: var(--red);
            margin-bottom: 12px;
        }
        .section-heading {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(40px, 5vw, 64px);
            letter-spacing: 2px;
            line-height: 0.95;
            margin-bottom: 60px;
        }
        .section-heading .outline {
            -webkit-text-stroke: 2px var(--black);
            color: transparent;
        }

        .steps-grid {
            display: grid;
            grid-template-columns: repeat(4,1fr);
            gap: 0;
        }
        .step-item {
            padding: 40px 32px;
            border-right: 2px solid var(--black);
            border-top: 2px solid var(--black);
            position: relative;
            transition: background 0.2s;
        }
        .step-item:last-child { border-right: none; }
        .step-item:hover { background: var(--gray); }
        .step-num {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 80px;
            color: var(--gray-2);
            line-height: 0.8;
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .step-icon {
            width: 48px; height: 48px;
            background: var(--black);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 20px;
        }
        .step-icon svg { width: 24px; height: 24px; color: white; }
        .step-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 22px;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }
        .step-body {
            font-size: 13px;
            color: var(--muted);
            line-height: 1.6;
        }

        /* ── FEATURES ── */
        .features {
            display: grid;
            grid-template-columns: 1fr 1fr;
            border-bottom: 2px solid var(--black);
        }
        .features-left {
            padding: 100px 72px;
            border-right: 2px solid var(--black);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .features-right {
            display: grid;
            grid-template-columns: 1fr 1fr;
        }
        .feat-cell {
            padding: 44px 36px;
            border-bottom: 2px solid var(--black);
            border-right: 2px solid var(--black);
            transition: background 0.2s;
        }
        .feat-cell:nth-child(even) { border-right: none; }
        .feat-cell:nth-child(3), .feat-cell:nth-child(4) { border-bottom: none; }
        .feat-cell:hover { background: var(--gray); }
        .feat-icon {
            width: 40px; height: 40px;
            border: 2px solid var(--black);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 16px;
        }
        .feat-icon svg { width: 18px; height: 18px; }
        .feat-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 18px;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }
        .feat-body { font-size: 12px; color: var(--muted); line-height: 1.6; }

        /* ── PLANS ── */
        .plans {
            padding: 100px 72px;
            background: var(--gray);
            border-bottom: 2px solid var(--black);
        }
        .plans-grid {
            display: grid;
            grid-template-columns: repeat(3,1fr);
            gap: 0;
            margin-top: 60px;
            border: 2px solid var(--black);
        }
        .plan-col {
            padding: 48px 36px;
            border-right: 2px solid var(--black);
            background: var(--white);
            position: relative;
            transition: all 0.25s;
        }
        .plan-col:last-child { border-right: none; }
        .plan-col:hover { background: var(--black); color: var(--white); }
        .plan-col:hover .plan-price { color: var(--red); }
        .plan-col:hover .plan-feat  { color: rgba(255,255,255,0.6); }
        .plan-col:hover .plan-feat-check { color: #22C55E; }
        .plan-col:hover .plan-cta { background: var(--red); color: var(--white); border-color: var(--red); }

        .plan-featured {
            background: var(--black);
            color: var(--white);
            border-color: var(--black);
        }
        .plan-featured .plan-price { color: var(--red); }
        .plan-featured .plan-feat  { color: rgba(255,255,255,0.55); }
        .plan-featured .plan-feat-check { color: #22C55E; }
        .plan-featured .plan-cta { background: var(--red); color: var(--white); border-color: var(--red); }

        .plan-badge {
            position: absolute; top: -14px; left: 50%; transform: translateX(-50%);
            background: var(--red); color: var(--white);
            font-size: 10px; font-weight: 800; letter-spacing: 2px;
            text-transform: uppercase; padding: 4px 16px; white-space: nowrap;
        }
        .plan-name {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 26px; letter-spacing: 2px;
            margin-bottom: 4px;
        }
        .plan-tagline { font-size: 12px; color: var(--muted); margin-bottom: 24px; }
        .plan-price {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 48px; letter-spacing: 1px; line-height: 1;
            margin-bottom: 4px;
        }
        .plan-price-period { font-size: 12px; color: var(--muted); margin-bottom: 28px; }
        .plan-feats { list-style: none; display: flex; flex-direction: column; gap: 10px; margin-bottom: 32px; }
        .plan-feat { display: flex; align-items: flex-start; gap: 8px; font-size: 13px; line-height: 1.4; }
        .plan-feat-check { color: #16A34A; font-size: 14px; flex-shrink: 0; margin-top: 1px; }
        .plan-feat-x { color: var(--gray-2); font-size: 14px; flex-shrink: 0; margin-top: 1px; }
        .plan-cta {
            display: block; text-align: center; text-decoration: none;
            padding: 13px 20px;
            font-family: 'Bebas Neue', sans-serif;
            font-size: 16px; letter-spacing: 2px;
            border: 2px solid var(--black);
            color: var(--black);
            background: transparent;
            transition: all 0.2s;
        }
        .plan-cta:hover {
            background: var(--red) !important;
            color: var(--white) !important;
            border-color: var(--red) !important;
        }
        .plan-trial { text-align: center; font-size: 11px; color: var(--muted); margin-top: 12px; }

        /* ── BIG CTA ── */
        .big-cta {
            padding: 120px 72px;
            background: var(--red);
            display: grid;
            grid-template-columns: 1fr auto;
            align-items: center;
            gap: 40px;
            border-bottom: 2px solid var(--black);
        }
        .big-cta-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(48px, 6vw, 80px);
            line-height: 0.92;
            letter-spacing: 2px;
            color: var(--white);
        }
        .big-cta-title .outline-white {
            -webkit-text-stroke: 2px white;
            color: transparent;
        }
        .big-cta-right { flex-shrink: 0; display: flex; flex-direction: column; gap: 12px; align-items: flex-end; }
        .btn-big-white {
            padding: 18px 44px;
            font-family: 'Bebas Neue', sans-serif;
            font-size: 20px; letter-spacing: 3px;
            text-decoration: none;
            color: var(--red);
            background: var(--white);
            border: 2px solid var(--white);
            box-shadow: 6px 6px 0 rgba(0,0,0,0.2);
            transition: all 0.2s;
            white-space: nowrap;
        }
        .btn-big-white:hover {
            background: var(--black);
            color: var(--white);
            border-color: var(--black);
            transform: translate(-3px,-3px);
            box-shadow: 9px 9px 0 rgba(0,0,0,0.3);
        }
        .btn-big-ghost {
            padding: 14px 44px;
            font-family: 'Bebas Neue', sans-serif;
            font-size: 16px; letter-spacing: 3px;
            text-decoration: none;
            color: var(--white);
            background: transparent;
            border: 2px solid rgba(255,255,255,0.5);
            transition: all 0.2s;
            white-space: nowrap;
            text-align: center;
        }
        .btn-big-ghost:hover {
            border-color: var(--white);
            background: rgba(255,255,255,0.1);
        }
        .big-cta-note { font-size: 12px; color: rgba(255,255,255,0.65); text-align: right; }

        /* ── FOOTER ── */
        footer {
            background: var(--black);
            padding: 60px 72px 32px;
        }
        .footer-top {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr 1fr;
            gap: 48px;
            padding-bottom: 48px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .footer-brand {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 24px;
            letter-spacing: 3px;
            color: var(--white);
            margin-bottom: 12px;
        }
        .footer-desc { font-size: 13px; color: rgba(255,255,255,0.4); line-height: 1.6; }
        .footer-col-title {
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: rgba(255,255,255,0.3);
            margin-bottom: 16px;
        }
        .footer-col a {
            display: block;
            font-size: 13px;
            color: rgba(255,255,255,0.55);
            text-decoration: none;
            margin-bottom: 10px;
            transition: color 0.2s;
        }
        .footer-col a:hover { color: var(--red); }
        .footer-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 24px;
        }
        .footer-copy { font-size: 12px; color: rgba(255,255,255,0.3); }
        .footer-stps {
            font-size: 11px;
            color: rgba(255,255,255,0.3);
            text-align: right;
            line-height: 1.4;
        }

        /* ── SCROLL ANIM ── */
        .reveal {
            opacity: 0;
            transform: translateY(32px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        .reveal.visible { opacity: 1; transform: none; }
        .reveal-delay-1 { transition-delay: 0.1s; }
        .reveal-delay-2 { transition-delay: 0.2s; }
        .reveal-delay-3 { transition-delay: 0.3s; }

        /* ── RESPONSIVE ── */
        @media (max-width: 1024px) {
            .hero { grid-template-columns: 1fr; }
            .hero-right { display: none; }
            .hero-left { padding: 60px 32px; }
            nav { padding: 0 24px; }
            .nav-links { display: none; }
            .stats-band { grid-template-columns: repeat(2,1fr); }
            .how { padding: 60px 32px; }
            .steps-grid { grid-template-columns: 1fr 1fr; }
            .features { grid-template-columns: 1fr; }
            .features-left { padding: 60px 32px; border-right: none; border-bottom: 2px solid var(--black); }
            .plans { padding: 60px 32px; }
            .plans-grid { grid-template-columns: 1fr; border: none; gap: 2px; }
            .plan-col { border-right: none; border: 2px solid var(--black); }
            .big-cta { padding: 80px 32px; grid-template-columns: 1fr; }
            .big-cta-right { align-items: flex-start; }
            .big-cta-note { text-align: left; }
            footer { padding: 48px 32px 24px; }
            .footer-top { grid-template-columns: 1fr 1fr; gap: 32px; }
        }
        @media (max-width: 640px) {
            .steps-grid { grid-template-columns: 1fr; }
            .features-right { grid-template-columns: 1fr; }
            .feat-cell { border-right: none; }
            .stats-band { grid-template-columns: 1fr; }
            .footer-top { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <!-- Ticker -->
    <div class="ticker">
        <div class="ticker-track">
            @foreach(range(0, 1) as $i)
            <span class="ticker-item">NOM-035-STPS-2018</span>
            <span class="ticker-item">Cumplimiento normativo</span>
            <span class="ticker-item">Bienestar laboral</span>
            <span class="ticker-item">Factores de riesgo psicosocial</span>
            <span class="ticker-item">Evaluación certificada</span>
            <span class="ticker-item">30 días gratis</span>
            <span class="ticker-item">+500 empresas confían en nosotros</span>
            @endforeach
        </div>
    </div>

    <!-- Nav -->
    <nav id="main-nav">
        <a href="/" class="nav-logo">
            <span class="nav-logo-dot"></span>
            NOM-035 SaaS
        </a>
        <ul class="nav-links">
            <li><a href="#como-funciona">Cómo funciona</a></li>
            <li><a href="#funcionalidades">Funcionalidades</a></li>
            <li><a href="#precios">Precios</a></li>
        </ul>
        <div class="nav-actions">
            <a href="{{ route('login') }}" class="btn-ghost">Iniciar sesión</a>
            <a href="{{ route('register') }}" class="btn-solid">Comenzar gratis →</a>
        </div>
    </nav>

    <!-- ══ HERO ══ -->
    <section class="hero">
        <div class="hero-left">
            <div class="hero-tag">
                <span class="hero-tag-dot"></span>
                NOM-035-STPS-2018 — Plataforma Certificada
            </div>
            <h1 class="hero-title">
                CUMPLE<br>
                <span class="red">NOM</span><br>
                <span class="outline">035</span>
            </h1>
            <p class="hero-body">
                La plataforma SaaS más completa de México para gestionar, evaluar y reportar los
                factores de riesgo psicosocial de tus empleados. Evita multas. Protege a tu equipo.
            </p>
            <div class="hero-ctas">
                <a href="{{ route('register') }}" class="btn-hero-primary">
                    Comenzar 30 días gratis
                    <svg viewBox="0 0 16 16" fill="none"><path d="M3 8h10M8 3l5 5-5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
                <a href="#como-funciona" class="btn-hero-secondary">Ver cómo funciona</a>
            </div>
            <div class="hero-trust">
                <div class="trust-avatars">
                    <div class="trust-avatar" style="background:#E8180C;">GA</div>
                    <div class="trust-avatar" style="background:#1a1a1a;">CB</div>
                    <div class="trust-avatar" style="background:#374151;">TS</div>
                    <div class="trust-avatar" style="background:#7C3AED;">CV</div>
                </div>
                <div class="trust-text">
                    <strong>+500 empresas activas</strong>
                    ya cumplen con la NOM-035 con nosotros
                </div>
            </div>
        </div>
        <div class="hero-right">
            <div class="hero-card">
                <div class="hc-badge">Dashboard en vivo</div>
                <div class="hc-title">Cumplimiento NOM-035</div>
                <div class="hc-ring-wrap">
                    <div class="hc-ring">
                        <div class="hc-ring-num">72%</div>
                    </div>
                </div>
                <div class="hc-stats">
                    <div class="hc-stat">
                        <div class="hc-stat-num">124</div>
                        <div class="hc-stat-label">Empleados</div>
                    </div>
                    <div class="hc-stat">
                        <div class="hc-stat-num">89</div>
                        <div class="hc-stat-label">Evaluados</div>
                    </div>
                </div>
                <div class="hc-risk-bars">
                    <div class="hc-bar-row">
                        <span class="hc-bar-label">Bajo</span>
                        <div class="hc-bar-track"><div class="hc-bar-fill bar-verde" style="width:65%"></div></div>
                        <span class="hc-bar-pct">65%</span>
                    </div>
                    <div class="hc-bar-row">
                        <span class="hc-bar-label">Medio</span>
                        <div class="hc-bar-track"><div class="hc-bar-fill bar-amarillo" style="width:24%"></div></div>
                        <span class="hc-bar-pct">24%</span>
                    </div>
                    <div class="hc-bar-row">
                        <span class="hc-bar-label">Alto</span>
                        <div class="hc-bar-track"><div class="hc-bar-fill bar-rojo" style="width:11%"></div></div>
                        <span class="hc-bar-pct">11%</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ══ STATS ══ -->
    <div class="stats-band">
        <div class="stat-block reveal">
            <span class="stat-num">500+</span>
            <span class="stat-lbl">Empresas activas</span>
        </div>
        <div class="stat-block reveal reveal-delay-1">
            <span class="stat-num">48K+</span>
            <span class="stat-lbl">Evaluaciones realizadas</span>
        </div>
        <div class="stat-block reveal reveal-delay-2">
            <span class="stat-num">100%</span>
            <span class="stat-lbl">Cumplimiento STPS</span>
        </div>
        <div class="stat-block reveal reveal-delay-3">
            <span class="stat-num">30 días</span>
            <span class="stat-lbl">Prueba gratis</span>
        </div>
    </div>

    <!-- ══ CÓMO FUNCIONA ══ -->
    <section id="como-funciona" class="how">
        <p class="section-eyebrow reveal">Proceso simple</p>
        <h2 class="section-heading reveal">
            CUATRO PASOS<br>
            <span class="outline">PARA CUMPLIR</span>
        </h2>
        <div class="steps-grid">
            <div class="step-item reveal">
                <span class="step-num">01</span>
                <div class="step-icon">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M3 19V7l9-4 9 4v12H3z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="M9 19v-7h6v7" stroke="currentColor" stroke-width="1.6"/></svg>
                </div>
                <div class="step-title">Registra tu empresa</div>
                <div class="step-body">Crea tu cuenta en minutos. Elige el plan que mejor se adapte al tamaño de tu empresa y activa tu período de prueba gratuito.</div>
            </div>
            <div class="step-item reveal reveal-delay-1">
                <span class="step-num">02</span>
                <div class="step-icon">
                    <svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="1.6"/><path d="M4 20c0-4.4 3.6-8 8-8s8 3.6 8 8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
                </div>
                <div class="step-title">Agrega tus empleados</div>
                <div class="step-body">Importa o registra a tu equipo organizándolos por áreas. El sistema respeta los límites de tu plan automáticamente.</div>
            </div>
            <div class="step-item reveal reveal-delay-2">
                <span class="step-num">03</span>
                <div class="step-icon">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M9 11l3 3 8-8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><path d="M20 12v7a2 2 0 01-2 2H6a2 2 0 01-2-2V5a2 2 0 012-2h9" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
                </div>
                <div class="step-title">Aplica evaluaciones</div>
                <div class="step-body">Cuestionarios NOM-035 Guía I, II y III. Interfaz limpia pregunta por pregunta. El sistema calcula el nivel de riesgo automáticamente.</div>
            </div>
            <div class="step-item reveal reveal-delay-3">
                <span class="step-num">04</span>
                <div class="step-icon">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="M14 2v6h6M8 13h8M8 17h6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
                </div>
                <div class="step-title">Genera tu reporte PDF</div>
                <div class="step-body">Descarga el reporte oficial NOM-035 listo para presentar ante la STPS en cualquier auditoría laboral. Con firmas incluidas.</div>
            </div>
        </div>
    </section>

    <!-- ══ FUNCIONALIDADES ══ -->
    <section id="funcionalidades" class="features">
        <div class="features-left reveal">
            <p class="section-eyebrow">Plataforma completa</p>
            <h2 class="section-heading">
                TODO LO QUE<br>
                <span class="outline">NECESITAS</span>
            </h2>
            <p style="font-size:15px; color:var(--muted); line-height:1.7; margin-bottom:32px;">
                Una sola plataforma para gestionar el cumplimiento de la NOM-035-STPS-2018 de principio a fin.
                Sin Excel. Sin papeles. Sin complicaciones.
            </p>
            <a href="{{ route('register') }}" class="btn-solid" style="width:fit-content; display:inline-block;">
                Probar gratis 30 días
            </a>
        </div>
        <div class="features-right">
            <div class="feat-cell reveal">
                <div class="feat-icon">
                    <svg viewBox="0 0 18 18" fill="none"><path d="M2 14V6l7-4 7 4v8H2z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M6 14V9h6v5" stroke="currentColor" stroke-width="1.4"/></svg>
                </div>
                <div class="feat-title">Gestión de empleados</div>
                <div class="feat-body">CRUD completo con filtros por área, estado de evaluación y nivel de riesgo.</div>
            </div>
            <div class="feat-cell reveal reveal-delay-1">
                <div class="feat-icon">
                    <svg viewBox="0 0 18 18" fill="none"><circle cx="9" cy="9" r="7" stroke="currentColor" stroke-width="1.4"/><path d="M9 5v5l3 2" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
                </div>
                <div class="feat-title">Evaluaciones NOM-035</div>
                <div class="feat-body">Guías I, II y III. Cálculo automático de riesgo con escala oficial STPS.</div>
            </div>
            <div class="feat-cell reveal reveal-delay-2">
                <div class="feat-icon">
                    <svg viewBox="0 0 18 18" fill="none"><path d="M2 12l4-4 3 3 4-5 3 3" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <div class="feat-title">Dashboard ejecutivo</div>
                <div class="feat-body">Métricas en tiempo real, gráficas de riesgo por área y ranking de cumplimiento.</div>
            </div>
            <div class="feat-cell reveal reveal-delay-3">
                <div class="feat-icon">
                    <svg viewBox="0 0 18 18" fill="none"><path d="M4 2h7l5 5v9a1 1 0 01-1 1H4a1 1 0 01-1-1V3a1 1 0 011-1z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M11 2v5h5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/><path d="M5 10h8M5 13h6" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
                </div>
                <div class="feat-title">Reportes PDF oficiales</div>
                <div class="feat-body">Documentos listos para auditoría STPS con distribución de riesgo y recomendaciones.</div>
            </div>
        </div>
    </section>

    <!-- ══ PRECIOS ══ -->
    <section id="precios" class="plans">
        <p class="section-eyebrow reveal">Sin sorpresas</p>
        <h2 class="section-heading reveal">
            PLANES Y<br>
            <span class="outline">PRECIOS</span>
        </h2>
        <div class="plans-grid">
            <!-- Básico -->
            <div class="plan-col reveal">
                <div class="plan-name">Básico</div>
                <div class="plan-tagline">Para empresas pequeñas</div>
                <div class="plan-price">$499</div>
                <div class="plan-price-period">MXN por mes · IVA incluido</div>
                <ul class="plan-feats">
                    <li class="plan-feat"><span class="plan-feat-check">✓</span> Hasta 25 empleados</li>
                    <li class="plan-feat"><span class="plan-feat-check">✓</span> Guías I, II y III</li>
                    <li class="plan-feat"><span class="plan-feat-check">✓</span> Evaluaciones ilimitadas</li>
                    <li class="plan-feat"><span class="plan-feat-check">✓</span> 3 reportes PDF por mes</li>
                    <li class="plan-feat"><span class="plan-feat-check">✓</span> Dashboard básico</li>
                    <li class="plan-feat"><span class="plan-feat-x">✗</span> <span style="opacity:.4">Reportes ilimitados</span></li>
                    <li class="plan-feat"><span class="plan-feat-x">✗</span> <span style="opacity:.4">Acceso API</span></li>
                </ul>
                <a href="{{ route('register') }}" class="plan-cta">Comenzar gratis</a>
                <div class="plan-trial">30 días gratis · Sin tarjeta</div>
            </div>
            <!-- Profesional -->
            <div class="plan-col plan-featured reveal reveal-delay-1">
                <div class="plan-badge">Más popular</div>
                <div class="plan-name">Profesional</div>
                <div class="plan-tagline" style="color:rgba(255,255,255,0.45)">Para empresas medianas</div>
                <div class="plan-price">$1,299</div>
                <div class="plan-price-period" style="color:rgba(255,255,255,0.4)">MXN por mes · IVA incluido</div>
                <ul class="plan-feats">
                    <li class="plan-feat"><span class="plan-feat-check">✓</span> Hasta 100 empleados</li>
                    <li class="plan-feat"><span class="plan-feat-check">✓</span> Guías I, II y III</li>
                    <li class="plan-feat"><span class="plan-feat-check">✓</span> Evaluaciones ilimitadas</li>
                    <li class="plan-feat"><span class="plan-feat-check">✓</span> Reportes PDF ilimitados</li>
                    <li class="plan-feat"><span class="plan-feat-check">✓</span> Dashboard avanzado</li>
                    <li class="plan-feat"><span class="plan-feat-check">✓</span> Resultados por área</li>
                    <li class="plan-feat"><span class="plan-feat-x">✗</span> <span style="opacity:.4">Acceso API</span></li>
                </ul>
                <a href="{{ route('register') }}" class="plan-cta">Comenzar gratis</a>
                <div class="plan-trial" style="color:rgba(255,255,255,0.35)">30 días gratis · Sin tarjeta</div>
            </div>
            <!-- Empresarial -->
            <div class="plan-col reveal reveal-delay-2">
                <div class="plan-name">Empresarial</div>
                <div class="plan-tagline">Para grandes empresas</div>
                <div class="plan-price">$2,999</div>
                <div class="plan-price-period">MXN por mes · IVA incluido</div>
                <ul class="plan-feats">
                    <li class="plan-feat"><span class="plan-feat-check">✓</span> Hasta 500 empleados</li>
                    <li class="plan-feat"><span class="plan-feat-check">✓</span> Guías I, II y III</li>
                    <li class="plan-feat"><span class="plan-feat-check">✓</span> Evaluaciones ilimitadas</li>
                    <li class="plan-feat"><span class="plan-feat-check">✓</span> Reportes PDF ilimitados</li>
                    <li class="plan-feat"><span class="plan-feat-check">✓</span> Dashboard ejecutivo</li>
                    <li class="plan-feat"><span class="plan-feat-check">✓</span> Acceso API REST</li>
                    <li class="plan-feat"><span class="plan-feat-check">✓</span> Gestor dedicado</li>
                </ul>
                <a href="{{ route('register') }}" class="plan-cta">Comenzar gratis</a>
                <div class="plan-trial">30 días gratis · Sin tarjeta</div>
            </div>
        </div>
    </section>

    <!-- ══ BIG CTA ══ -->
    <section class="big-cta">
        <div class="big-cta-title reveal">
            ¿LISTO PARA<br>
            <span class="outline-white">CUMPLIR LA</span><br>
            NORMA?
        </div>
        <div class="big-cta-right reveal reveal-delay-1">
            <a href="{{ route('register') }}" class="btn-big-white">Crear cuenta gratis →</a>
            <a href="{{ route('login') }}" class="btn-big-ghost">Ya tengo cuenta</a>
            <div class="big-cta-note">30 días gratis · Sin tarjeta · Cancela cuando quieras</div>
        </div>
    </section>

    <!-- ══ FOOTER ══ -->
    <footer>
        <div class="footer-top">
            <div>
                <div class="footer-brand">NOM-035 SaaS</div>
                <div class="footer-desc">
                    La plataforma SaaS líder en México para el cumplimiento de la
                    NOM-035-STPS-2018. Protege a tu equipo y evita multas.
                </div>
            </div>
            <div class="footer-col">
                <div class="footer-col-title">Plataforma</div>
                <a href="#como-funciona">Cómo funciona</a>
                <a href="#funcionalidades">Funcionalidades</a>
                <a href="#precios">Precios</a>
                <a href="{{ route('register') }}">Registrarse</a>
            </div>
            <div class="footer-col">
                <div class="footer-col-title">Cuenta</div>
                <a href="{{ route('login') }}">Iniciar sesión</a>
                <a href="{{ route('register') }}">Crear cuenta</a>
                <a href="#">Recuperar contraseña</a>
            </div>
            <div class="footer-col">
                <div class="footer-col-title">Legal</div>
                <a href="#">Términos de servicio</a>
                <a href="#">Aviso de privacidad</a>
                <a href="#">NOM-035-STPS-2018</a>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="footer-copy">© 2025 NOM-035 SaaS. Todos los derechos reservados.</div>
            <div class="footer-stps">NOM-035-STPS-2018<br>Cumplimiento normativo STPS México</div>
        </div>
    </footer>

    <script>
        // Nav scroll
        const nav = document.getElementById('main-nav');
        window.addEventListener('scroll', () => {
            nav.classList.toggle('scrolled', window.scrollY > 40);
        });

        // Reveal on scroll
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.classList.add('visible');
                    observer.unobserve(e.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(a => {
            a.addEventListener('click', e => {
                const target = document.querySelector(a.getAttribute('href'));
                if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth' }); }
            });
        });
    </script>
</body>
</html>