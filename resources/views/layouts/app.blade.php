<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Hidroponik - SIKECE</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- External Libraries (Dipertahankan Sepenuhnya) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Styles UI Enhancement -->
    <style>
        :root {
            --primary-green: #10b981;
            --secondary-green: #059669;
            --accent-blue: #0284c7;
            --accent-purple: #8b5cf6;
            --light-green: #d1fae5;
            --dark-green: #065f46;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --white: #ffffff;
        }

        * {
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 50%, #f8fafc 100%);
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: var(--gray-800);
        }

        /* Glassmorphism Card Styling */
        .glass-card {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05), 0 4px 12px -2px rgba(0, 0, 0, 0.02);
            border-radius: 20px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 35px -10px rgba(16, 185, 129, 0.12), 0 8px 20px -6px rgba(0, 0, 0, 0.04);
        }

        /* Status Indicators */
        .status-indicator {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 8px 18px;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 0.875rem;
            color: white;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .status-indicator.on {
            background: linear-gradient(135deg, #10b981, #059669);
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
        }

        .status-indicator.off {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            box-shadow: 0 4px 14px rgba(239, 68, 68, 0.35);
        }

        /* Metric Cards */
        .metric-card {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(226, 232, 240, 0.8);
            position: relative;
            overflow: hidden;
        }

        .metric-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-green), var(--accent-blue));
        }

        .metric-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 25px -5px rgba(0, 0, 0, 0.08);
        }

        .chart-container {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
            padding: 24px;
            border: 1px solid rgba(226, 232, 240, 0.8);
        }

        /* Modernized Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            color: white;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.875rem;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.35);
        }

        .header-gradient {
            background: linear-gradient(135deg, var(--primary-green), var(--accent-blue), var(--accent-purple));
        }

        .form-input {
            transition: all 0.3s ease;
            border: 1px solid var(--gray-300);
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 0.875rem;
            background: rgba(255, 255, 255, 0.9);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-green);
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15);
            background: var(--white);
        }

        .form-label {
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
            display: block;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--gray-800);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .icon-wrapper {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary-green), var(--accent-blue));
            color: white;
            box-shadow: 0 4px 10px rgba(16, 185, 129, 0.2);
        }

        .status-card {
            background: var(--white);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            border: 1px solid var(--gray-200);
            transition: all 0.3s ease;
        }

        .status-card:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
        }

        /* Animations */
        .thinking-dots, .thinking {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            font-weight: bold;
            color: var(--gray-700);
        }

        .thinking-dots span, .thinking span {
            width: 5px;
            height: 5px;
            background: var(--gray-600);
            border-radius: 50%;
            animation: thinkingAnim 1.4s infinite ease-in-out;
        }

        .thinking-dots span:nth-child(2), .thinking span:nth-child(2) {
            animation-delay: 0.2s;
        }

        .thinking-dots span:nth-child(3), .thinking span:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes thinkingAnim {
            0%, 80%, 100% {
                opacity: 0.2;
                transform: scale(0.8);
            }
            40% {
                opacity: 1;
                transform: scale(1.2);
            }
        }

        .fan-icon {
            width: 32px;
            height: 32px;
            border: 3px solid rgba(16, 185, 129, 0.2);
            border-top: 3px solid var(--primary-green);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin: auto;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        @keyframes pulseIcon {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.15); opacity: 0.8; }
        }

        .label-icon {
            color: var(--primary-green);
            animation: pulseIcon 2s infinite ease-in-out;
            margin-right: 6px;
        }

        @keyframes slideUpFade {
            0% {
                transform: translateY(20px);
                opacity: 0;
            }
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes pulseLock {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.08); }
        }

        .lock-overlay {
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            transition: all 0.3s ease;
            border-radius: 16px;
            animation: slideUpFade 0.4s ease-out forwards;
            cursor: pointer;
        }

        .lock-overlay:hover {
            background: rgba(15, 23, 42, 0.75);
        }

        .lock-icon {
            animation: pulseLock 1.5s infinite ease-in-out;
        }

        .login-form {
            animation: slideUpFade 0.4s ease-out forwards;
        }
        
        /* Dashboard Hero & Stats Styling */
        .dashboard-hero {
            background: linear-gradient(135deg, #047857 0%, #059669 45%, #0ea5e9 100%);
            box-shadow: 0 20px 40px -10px rgba(5, 150, 105, 0.25);
            border-radius: 24px;
        }

        .hero-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            padding: .75rem 1.25rem;
            border-radius: 12px;
            font-weight: 700;
            transition: all .2s ease;
            text-decoration: none;
        }

        .hero-orb {
            position: absolute;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.08);
            pointer-events: none;
        }
        .orb-1 { width: 240px; height: 240px; right: -80px; top: -100px; }
        .orb-2 { width: 180px; height: 180px; right: 180px; bottom: -120px; }

        .modern-stat {
            position: relative;
            background: #fff;
            border: 1px solid var(--gray-200);
            border-radius: 1.25rem;
            padding: 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
            transition: all 0.3s ease;
        }
        .modern-stat:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.06);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex: none;
        }
        .stat-label {
            font-size: .75rem;
            color: var(--gray-600);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
        }
        .stat-value {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--gray-800);
            line-height: 1.2;
        }
        .stat-value small {
            font-size: .75rem;
            color: #94a3b8;
            font-weight: 700;
        }
        .stat-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-left: auto;
            align-self: flex-start;
        }

        .disease-highlight {
            background: #fff;
            border: 1px solid var(--gray-200);
            border-radius: 1.25rem;
            padding: 1.35rem;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
        }

        .mini-stat {
            background: var(--gray-50);
            border-radius: 12px;
            padding: .75rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .5rem;
            border: 1px solid var(--gray-200);
        }
        .mini-stat span { font-size: .8rem; color: var(--gray-600); font-weight: 500; }
        .mini-stat b { font-size: 1.1rem; color: var(--gray-800); font-weight: 700; }

        @media (max-width: 768px) {
            .glass-card {
                margin: 0.5rem;
                padding: 1.25rem;
            }
            .metric-card {
                margin: 0.25rem;
            }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col antialiased">

    <!-- Header / Navbar -->
    @include('layouts.partials.navbar')

    <!-- Main Content -->
    <main class="container mx-auto px-4 sm:px-6 py-8 pt-28 sm:pt-32 flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('layouts.partials.footer')

    <!-- Custom Scripts Stack -->
    @stack('scripts')
</body>
</html>