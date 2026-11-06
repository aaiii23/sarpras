<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Registrasi Akun - SARPRAS SEKOLAH</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --bg-wine-deep: #16020b;
            --bg-wine-card: #240513;
            --wine-base: #5a1428;
            --wine-bright: #9d2b4b;
            --wine-accent: #b83256;
            --wine-rose: #f472b6;
            --wine-soft: #fed7e2;
            --text-light: #ffffff;
            --text-sub: #d1c0c7;
            --glass-bg: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.12);
            --input-bg: rgba(255, 255, 255, 0.07);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        /* Slightly brighter, elegant official wine background */
        body {
            background: radial-gradient(circle at 24% 24%, #5b1935 0%, #3e1024 50%, #230614 100%);
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 20px;
            color: var(--text-light);
            position: relative;
            overflow-x: hidden;
        }

        /* Ambient Glowing Aura Orbs */
        .ambient-soft {
            position: absolute;
            border-radius: 50%;
            filter: blur(130px);
            pointer-events: none;
            opacity: 0.45;
            z-index: 0;
        }

        .ambient-1 {
            width: 580px;
            height: 580px;
            background: #a82e4e;
            top: -140px;
            left: -120px;
            animation: auraPulse 8s ease-in-out infinite alternate;
        }

        .ambient-2 {
            width: 500px;
            height: 500px;
            background: #7e1d38;
            bottom: -100px;
            right: -80px;
            animation: auraPulse 10s ease-in-out infinite alternate-reverse;
        }

        @keyframes auraPulse {
            0% { transform: scale(1) translate(0, 0); opacity: 0.38; }
            50% { transform: scale(1.12) translate(25px, 20px); opacity: 0.52; }
            100% { transform: scale(1) translate(0, 0); opacity: 0.38; }
        }

        /* ============================================================
           MAIN TABLET BEZEL CONTAINER (Fluid & Responsive Grid)
           ============================================================ */
        .auth-container {
            width: 100%;
            max-width: 1060px;
            min-height: 640px;
            background: linear-gradient(150deg, #280616 0%, #1a030e 55%, #0f0108 100%);
            border-radius: 28px;
            box-shadow: 0 40px 100px -20px rgba(0, 0, 0, 0.88), 0 0 0 1px rgba(255, 255, 255, 0.1);
            display: grid;
            grid-template-columns: 1fr 1.18fr;
            overflow: hidden;
            position: relative;
            z-index: 1;
            margin: auto;
        }

        /* Abstract Floating Geometric Pills */
        .bg-geo-shape {
            position: absolute;
            pointer-events: none;
            z-index: 1;
        }

        .geo-pill-1 {
            width: 260px;
            height: 48px;
            border-radius: 24px;
            background: linear-gradient(90deg, rgba(244, 114, 182, 0.15), rgba(157, 43, 75, 0.05));
            border: 1px solid rgba(255, 255, 255, 0.06);
            top: 40px;
            left: 280px;
            transform: rotate(-35deg);
        }

        .geo-pill-2 {
            width: 200px;
            height: 38px;
            border-radius: 19px;
            background: linear-gradient(90deg, rgba(184, 50, 86, 0.14), rgba(244, 114, 182, 0.05));
            bottom: 60px;
            right: 20px;
            transform: rotate(-35deg);
        }

        /* LEFT SIDE: "Join Us!" & RPL Developer Tech Workspace */
        .auth-showcase {
            padding: 44px 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            z-index: 2;
        }

        .showcase-header {
            position: relative;
        }

        .brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            border-radius: 9999px;
            font-size: 11.5px;
            font-weight: 700;
            color: #fed7e2;
            letter-spacing: 0.4px;
            margin-bottom: 20px;
            max-width: 100%;
        }

        .brand-badge i {
            color: var(--wine-rose);
            font-size: 13px;
            flex-shrink: 0;
        }

        .welcome-title {
            font-size: 38px;
            font-weight: 800;
            line-height: 1.15;
            color: #ffffff;
            letter-spacing: -0.8px;
            margin-bottom: 12px;
            word-break: break-word;
        }

        .welcome-title span.dot {
            color: var(--wine-rose);
        }

        .welcome-accent-bar {
            width: 44px;
            height: 4px;
            border-radius: 9999px;
            background: linear-gradient(90deg, var(--wine-rose), var(--wine-accent));
            margin-bottom: 16px;
        }

        .welcome-desc {
            font-size: 13.5px;
            color: var(--text-sub);
            font-weight: 400;
            line-height: 1.6;
            max-width: 420px;
        }

        .workspace-art-area {
            position: relative;
            width: 100%;
            height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 16px 0 8px 0;
        }

        .workspace-svg {
            width: 100%;
            height: 100%;
            max-width: 420px;
            overflow: visible;
            filter: drop-shadow(0 14px 28px rgba(0, 0, 0, 0.45));
        }

        .showcase-features {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            padding-top: 18px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 11.5px;
            font-weight: 600;
            color: #e5d7dd;
            white-space: nowrap;
        }

        .feature-item i {
            color: var(--wine-rose);
            font-size: 14px;
            flex-shrink: 0;
        }

        /* RIGHT SIDE: Frosted Glass Form Card */
        .auth-form-pane {
            padding: 34px 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 2;
        }

        .glass-form-card {
            width: 100%;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 22px;
            padding: 32px 28px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.42), inset 0 1px 1px rgba(255, 255, 255, 0.15);
        }

        .form-header {
            margin-bottom: 18px;
        }

        .form-header h3 {
            font-size: 25px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.4px;
            display: inline-block;
            position: relative;
            margin-bottom: 4px;
        }

        .form-header h3 span.underline-accent {
            position: relative;
            display: inline-block;
        }

        .form-header h3 span.underline-accent::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -3px;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, var(--wine-rose), var(--wine-accent));
            border-radius: 9999px;
        }

        .form-header p {
            font-size: 12.5px;
            color: var(--text-sub);
            margin-top: 4px;
            line-height: 1.45;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.16);
            color: #fca5a5;
            border: 1px solid rgba(239, 68, 68, 0.3);
            padding: 10px 13px;
            border-radius: 10px;
            font-size: 12.5px;
            margin-bottom: 16px;
            word-break: break-word;
        }

        .form-group {
            margin-bottom: 14px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #e5d7dd;
            margin-bottom: 5px;
            letter-spacing: 0.2px;
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 9999px;
            font-size: 13px;
            color: #ffffff;
            background: rgba(255, 255, 255, 0.07);
            transition: all 0.2s ease;
        }

        .form-control option {
            background-color: #240714;
            color: #ffffff;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.35);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--wine-rose);
            background: rgba(255, 255, 255, 0.11);
            box-shadow: 0 0 0 3px rgba(244, 114, 182, 0.25);
        }

        .btn-submit {
            width: 100%;
            padding: 12.5px;
            background: linear-gradient(90deg, #a82e4e 0%, #7e1d38 50%, #581125 100%);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 9999px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 6px 20px rgba(126, 29, 56, 0.45);
            margin-top: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-submit:hover {
            background: linear-gradient(90deg, #be385a 0%, #942544 50%, #68152e 100%);
            transform: translateY(-1px);
            box-shadow: 0 8px 26px rgba(168, 46, 78, 0.58);
        }

        .footer-links {
            text-align: center;
            margin-top: 16px;
            font-size: 12.5px;
            color: var(--text-sub);
        }

        .footer-links a {
            color: var(--wine-rose);
            font-weight: 700;
            text-decoration: none;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }

        /* ============================================================
           COMPREHENSIVE RESPONSIVE BREAKPOINTS (ALL SCREEN SIZES)
           ============================================================ */

        /* 1. Large Laptops / Small Desktops (993px - 1140px) */
        @media (max-width: 1140px) {
            .auth-container {
                max-width: 960px;
                min-height: 600px;
            }
            .auth-showcase {
                padding: 38px 32px;
            }
            .welcome-title {
                font-size: 34px;
            }
            .workspace-art-area {
                height: 200px;
            }
            .auth-form-pane {
                padding: 30px 24px;
            }
            .glass-form-card {
                padding: 30px 24px;
            }
        }

        /* 2. Tablets in Portrait & Medium Screens (768px - 992px) */
        @media (max-width: 992px) {
            body {
                padding: 24px 16px;
            }
            .auth-container {
                grid-template-columns: 1fr;
                max-width: 540px;
                min-height: unset;
                border-radius: 24px;
            }
            .auth-showcase {
                padding: 34px 28px 20px 28px;
                border-right: none;
                border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            }
            .welcome-title {
                font-size: 30px;
            }
            .welcome-desc {
                max-width: 100%;
                font-size: 13px;
            }
            .workspace-art-area {
                height: 160px;
                margin: 12px 0 6px 0;
            }
            .workspace-svg {
                max-width: 340px;
            }
            .showcase-features {
                gap: 10px;
                padding-top: 14px;
            }
            .auth-form-pane {
                padding: 24px 28px 34px 28px;
            }
            .glass-form-card {
                padding: 28px 24px;
            }
        }

        /* 3. Standard Mobile Phones (481px - 767px) */
        @media (max-width: 767px) {
            body {
                padding: 18px 12px;
            }
            .auth-container {
                max-width: 480px;
                border-radius: 22px;
            }
            .auth-showcase {
                padding: 26px 20px 16px 20px;
            }
            .brand-badge {
                font-size: 11px;
                padding: 5px 12px;
                margin-bottom: 14px;
            }
            .welcome-title {
                font-size: 26px;
                margin-bottom: 8px;
            }
            .welcome-accent-bar {
                width: 38px;
                height: 3px;
                margin-bottom: 12px;
            }
            .welcome-desc {
                font-size: 12.5px;
                line-height: 1.5;
            }
            .workspace-art-area {
                height: 130px;
                margin: 8px 0 4px 0;
            }
            .workspace-svg {
                max-width: 280px;
            }
            .showcase-features {
                gap: 8px;
                padding-top: 12px;
            }
            .feature-item {
                font-size: 11px;
                gap: 6px;
            }
            .auth-form-pane {
                padding: 12px 20px 26px 20px;
            }
            .glass-form-card {
                padding: 24px 18px;
                border-radius: 18px;
            }
            .form-header {
                margin-bottom: 14px;
            }
            .form-header h3 {
                font-size: 22px;
            }
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
            .btn-submit {
                padding: 12px;
                font-size: 13.5px;
            }
        }

        /* 4. Small Mobile Phones (<= 480px) */
        @media (max-width: 480px) {
            body {
                padding: 12px 8px;
            }
            .auth-container {
                max-width: 100%;
                border-radius: 20px;
            }
            .auth-showcase {
                padding: 20px 16px 14px 16px;
            }
            .brand-badge {
                font-size: 10px;
                padding: 4px 10px;
                margin-bottom: 10px;
                letter-spacing: 0.2px;
            }
            .welcome-title {
                font-size: 23px;
                margin-bottom: 6px;
            }
            .welcome-accent-bar {
                width: 32px;
                height: 3px;
                margin-bottom: 8px;
            }
            .welcome-desc {
                font-size: 12px;
                line-height: 1.45;
            }
            .workspace-art-area {
                height: 115px;
                margin: 6px 0;
            }
            .workspace-svg {
                max-width: 240px;
            }
            .showcase-features {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 6px 12px;
                padding-top: 10px;
            }
            .feature-item {
                font-size: 10.5px;
            }
            .auth-form-pane {
                padding: 8px 14px 20px 14px;
            }
            .glass-form-card {
                padding: 20px 15px;
                border-radius: 16px;
            }
            .form-header {
                margin-bottom: 12px;
            }
            .form-header h3 {
                font-size: 20px;
            }
            .form-header p {
                font-size: 11.5px;
            }
            .form-group {
                margin-bottom: 11px;
            }
            .form-label {
                font-size: 11.5px;
                margin-bottom: 4px;
            }
            .form-control {
                font-size: 12.5px;
                padding: 9px 12px;
            }
            .btn-submit {
                padding: 11px;
                font-size: 13px;
            }
            .footer-links {
                margin-top: 12px;
                font-size: 11.5px;
            }
        }

        /* 5. Compact Screens (<= 340px) */
        @media (max-width: 340px) {
            .workspace-art-area {
                height: 90px;
            }
            .showcase-features {
                display: none;
            }
        }

        /* 6. Short Viewports / Mobile Landscape (height <= 660px) */
        @media (max-height: 660px) {
            body {
                padding: 16px 12px;
                align-items: flex-start;
            }
            .auth-container {
                min-height: unset;
                margin-top: auto;
                margin-bottom: auto;
            }
            .workspace-art-area {
                height: 120px;
                margin: 6px 0;
            }
            .auth-showcase {
                padding: 22px 24px 14px 24px;
            }
            .auth-form-pane {
                padding: 16px 20px;
            }
            .glass-form-card {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

    <!-- Ambient Glowing Aura Orbs -->
    <div class="ambient-soft ambient-1"></div>
    <div class="ambient-soft ambient-2"></div>

    <div class="auth-container">
        <!-- Abstract Floating Geometric Pills -->
        <div class="bg-geo-shape geo-pill-1"></div>
        <div class="bg-geo-shape geo-pill-2"></div>

        <!-- LEFT SIDE: "Join Us!" & RPL Developer Tech Workspace -->
        <div class="auth-showcase">
            <div class="showcase-header">
                <div class="brand-badge">
                    <i class="bi bi-person-plus-fill"></i> REGISTRASI AKUN • SMK RPL
                </div>

                <h1 class="welcome-title">Join Us<span class="dot">!</span></h1>
                <div class="welcome-accent-bar"></div>
                <p class="welcome-desc">
                    Daftarkan akun resmi siswa atau guru untuk mengakses layanan pelaporan sarana, notifikasi tindak lanjut, dan transparansi inventaris sekolah.
                </p>
            </div>

            <div class="workspace-art-area">
                <svg class="workspace-svg" viewBox="0 0 440 230" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="flowLineGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#f472b6" stop-opacity="0.32"/>
                            <stop offset="100%" stop-color="#8b5cf6" stop-opacity="0.08"/>
                        </linearGradient>
                        <linearGradient id="deskGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#4a1525"/>
                            <stop offset="60%" stop-color="#240714"/>
                            <stop offset="100%" stop-color="#14020a"/>
                        </linearGradient>
                        <linearGradient id="deskEdge" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%" stop-color="#be123c" stop-opacity="0.7"/>
                            <stop offset="50%" stop-color="#f472b6" stop-opacity="0.9"/>
                            <stop offset="100%" stop-color="#7e1d38" stop-opacity="0.5"/>
                        </linearGradient>
                        <linearGradient id="screenBg" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#14020a"/>
                            <stop offset="100%" stop-color="#2c0817"/>
                        </linearGradient>
                        <linearGradient id="lampCone" x1="50%" y1="0%" x2="50%" y2="100%">
                            <stop offset="0%" stop-color="#fed7aa" stop-opacity="0.3"/>
                            <stop offset="100%" stop-color="#fed7aa" stop-opacity="0"/>
                        </linearGradient>
                        <linearGradient id="succulentGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                            <stop offset="0%" stop-color="#c084fc"/>
                            <stop offset="50%" stop-color="#818cf8"/>
                            <stop offset="100%" stop-color="#3b82f6"/>
                        </linearGradient>
                    </defs>

                    <path d="M 30 60 C 120 20, 200 110, 320 50 S 410 100, 430 70" stroke="url(#flowLineGrad)" stroke-width="2.5" stroke-linecap="round" fill="none"/>

                    <ellipse cx="220" cy="205" rx="160" ry="15" fill="#080004" opacity="0.6"/>
                    <line x1="85" y1="172" x2="75" y2="205" stroke="#3d0c1e" stroke-width="3.5" stroke-linecap="round"/>
                    <line x1="355" y1="172" x2="365" y2="205" stroke="#3d0c1e" stroke-width="3.5" stroke-linecap="round"/>
                    <polygon points="60,158 380,158 358,176 82,176" fill="url(#deskGrad)" stroke="rgba(255,255,255,0.1)" stroke-width="1"/>
                    <line x1="82" y1="176" x2="358" y2="176" stroke="url(#deskEdge)" stroke-width="2.5"/>

                    <path d="M 342 158 L 328 95 L 305 105" stroke="#71203b" stroke-width="2.5" stroke-linecap="round" fill="none"/>
                    <path d="M 295 100 L 315 110 L 309 120 L 289 110 Z" fill="#9f1239"/>
                    <polygon points="299,115 215,168 335,168" fill="url(#lampCone)"/>

                    <polygon points="168,161 272,161 282,167 158,167" fill="#2d0818" stroke="rgba(255,255,255,0.18)" stroke-width="1"/>
                    <rect x="206" y="163" width="28" height="2.5" rx="1.2" fill="#f472b6" opacity="0.6"/>
                    <rect x="173" y="90" width="94" height="68" rx="6" fill="url(#screenBg)" stroke="#58162c" stroke-width="1.8"/>
                    <rect x="177" y="94" width="86" height="60" rx="4" fill="#150209"/>
                    <circle cx="184" cy="100" r="1.8" fill="#ef4444"/>
                    <circle cx="189" cy="100" r="1.8" fill="#f59e0b"/>
                    <circle cx="194" cy="100" r="1.8" fill="#10b981"/>
                    <rect x="180" y="106" width="15" height="44" rx="2" fill="rgba(255,255,255,0.06)"/>
                    <rect x="183" y="110" width="9" height="2.5" rx="1.2" fill="#f472b6"/>
                    <rect x="183" y="115" width="9" height="1.8" rx="0.9" fill="rgba(255,255,255,0.3)"/>
                    <rect x="183" y="119" width="9" height="1.8" rx="0.9" fill="rgba(255,255,255,0.3)"/>
                    <rect x="199" y="106" width="60" height="24" rx="3" fill="#2d0818" stroke="rgba(244,114,182,0.35)" stroke-width="0.8"/>
                    <circle cx="205" cy="113" r="2.5" fill="#10b981"/>
                    <rect x="210" y="111" width="28" height="2.5" rx="1.2" fill="#ffffff" opacity="0.9"/>
                    <rect x="210" y="116" width="42" height="1.8" rx="0.9" fill="#f472b6" opacity="0.8"/>
                    <rect x="210" y="121" width="18" height="3.5" rx="1.7" fill="#be123c"/>
                    <rect x="199" y="134" width="24" height="2" rx="1" fill="#38bdf8" opacity="0.85"/>
                    <rect x="199" y="139" width="38" height="2" rx="1" fill="#fbbf24" opacity="0.85"/>
                    <rect x="199" y="144" width="30" height="2" rx="1" fill="#a78bfa" opacity="0.85"/>

                    <polygon points="118,152 138,152 134,167 122,167" fill="#3a0c1e" stroke="#7c2242" stroke-width="1"/>
                    <ellipse cx="128" cy="152" rx="10" ry="2.5" fill="#230412"/>
                    <path d="M 128 151 C 120 140, 118 126, 125 119 C 129 128, 128 143, 128 151 Z" fill="url(#succulentGrad)"/>
                    <path d="M 128 151 C 135 141, 140 128, 134 121 C 130 128, 129 143, 128 151 Z" fill="url(#succulentGrad)" opacity="0.9"/>
                    <path d="M 128 151 C 125 136, 128 118, 130 110 C 133 119, 132 138, 128 151 Z" fill="#c084fc"/>

                    <rect x="146" y="155" width="9" height="11" rx="2" fill="#d9cac0"/>
                    <path d="M 155 157 C 158 157, 158 162, 155 162" stroke="#d9cac0" stroke-width="1.2" fill="none"/>
                    <path d="M 149 151 C 148 147, 150 144, 149 141" stroke="rgba(255,255,255,0.45)" stroke-width="0.9" stroke-linecap="round" fill="none"/>
                    <path d="M 152 150 C 153 146, 151 143, 152 140" stroke="rgba(255,255,255,0.45)" stroke-width="0.9" stroke-linecap="round" fill="none"/>
                </svg>
            </div>

            <div class="showcase-features">
                <div class="feature-item">
                    <i class="bi bi-patch-check-fill"></i>
                    <span>Tervalidasi Tepat</span>
                </div>
                <div class="feature-item">
                    <i class="bi bi-lightning-charge-fill"></i>
                    <span>Respon Efisien</span>
                </div>
                <div class="feature-item">
                    <i class="bi bi-shield-lock-fill"></i>
                    <span>Akses Fleksibel</span>
                </div>
            </div>
        </div>

        <!-- RIGHT SIDE: Register Form Card -->
        <div class="auth-form-pane">
            <div class="glass-form-card">
                <div class="form-header">
                    <h3>Sign <span class="underline-accent">up</span></h3>
                    <p>Isi data diri Anda untuk mendapatkan akses pelaporan sarana</p>
                </div>

                @if($errors->any())
                    <div class="alert-error">
                        <ul style="padding-left: 18px; margin: 0;">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register.post') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label class="form-label" for="role">Daftar Sebagai Peran</label>
                        <select name="role" id="role" class="form-control" onchange="handleRoleChange(this.value)" style="cursor: pointer; font-weight: 600;">
                            <option value="siswa" {{ old('role', 'siswa') === 'siswa' ? 'selected' : '' }}>Siswa</option>
                            <option value="guru" {{ old('role', 'guru') === 'guru' ? 'selected' : '' }}>Guru</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" id="nameLabel" for="name">Nama Lengkap Siswa</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Contoh: Budi Santoso" value="{{ old('name') }}" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" id="nisLabel" for="nis">Nomor Induk Siswa (NIS) <span style="color: #f87171;">*</span></label>
                            <input type="text" name="nis" id="nis" class="form-control" placeholder="Contoh: 12345678 (NIS Siswa)" value="{{ old('nis') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="phone">No. WhatsApp / HP</label>
                            <input type="text" name="phone" id="phone" class="form-control" placeholder="08xxxxxxxxxx" value="{{ old('phone') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">Alamat Email</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="nama@gmail.com" pattern="[^@\s]+@gmail\.com" title="Gunakan alamat @gmail.com" value="{{ old('email') }}" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="password">Kata Sandi</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Min. 6 karakter" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="password_confirmation">Konfirmasi Sandi</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Ulangi sandi" required>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">
                        Daftar Sekarang <i class="bi bi-arrow-right"></i>
                    </button>
                </form>

                <div class="footer-links">
                    Sudah memiliki akun? <a href="{{ route('login') }}">Masuk di sini &rarr;</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function handleRoleChange(role) {
            const nisLabel = document.getElementById('nisLabel');
            const nisInput = document.getElementById('nis');
            const nameLabel = document.getElementById('nameLabel');

            if (role === 'guru') {
                nisLabel.innerHTML = 'NIP (Nomor Induk Pegawai) <small style="color: #cbd5e1; font-weight: normal;">(Opsional)</small>';
                nisInput.placeholder = 'Contoh: 198501012010011001 (NIP Guru)';
                nisInput.required = false;
                nameLabel.textContent = 'Nama Lengkap Guru';
            } else {
                nisLabel.innerHTML = 'Nomor Induk Siswa (NIS) <span style="color: #f87171;">*</span>';
                nisInput.placeholder = 'Contoh: 12345678 (NIS Siswa)';
                nisInput.required = true;
                nameLabel.textContent = 'Nama Lengkap Siswa';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role');
            if (roleSelect) {
                handleRoleChange(roleSelect.value);
            }
        });
    </script>
</body>
</html>