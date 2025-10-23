<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Hidroponik - SIKECE</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-green: #10b981;
            --secondary-green: #059669;
            --accent-blue: #3b82f6;
            --accent-purple: #8b5cf6;
            --light-green: #d1fae5;
            --dark-green: #065f46;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --white: #ffffff;
        }

        * {
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 50%, #f9fafb 100%);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: var(--gray-800);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 
                0 4px 6px -1px rgba(0, 0, 0, 0.1),
                0 2px 4px -1px rgba(0, 0, 0, 0.06),
                0 0 0 1px rgba(0, 0, 0, 0.05);
            border-radius: 16px;
            transition: all 0.3s ease;
        }

        .glass-card:hover {
            transform: translateY(-2px);
            box-shadow: 
                0 20px 25px -5px rgba(0, 0, 0, 0.1),
                0 10px 10px -5px rgba(0, 0, 0, 0.04),
                0 0 0 1px rgba(0, 0, 0, 0.05);
        }

        .status-indicator {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 8px 16px;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 0.875rem;
            color: white;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .status-indicator.on {
            background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        }

        .status-indicator.off {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
        }

        .metric-card {
            background: var(--white);
            border-radius: 16px;
            box-shadow: 
                0 1px 3px 0 rgba(0, 0, 0, 0.1),
                0 1px 2px 0 rgba(0, 0, 0, 0.06);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid var(--gray-200);
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
            box-shadow: 
                0 10px 15px -3px rgba(0, 0, 0, 0.1),
                0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .chart-container {
            background: var(--white);
            border-radius: 16px;
            box-shadow: 
                0 1px 3px 0 rgba(0, 0, 0, 0.1),
                0 1px 2px 0 rgba(0, 0, 0, 0.06);
            padding: 24px;
            border: 1px solid var(--gray-200);
        }

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
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }

        .header-gradient {
            background: linear-gradient(135deg, var(--primary-green), var(--accent-blue), var(--accent-purple));
        }

        .form-input {
            transition: all 0.3s ease;
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 0.875rem;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(4px);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-green);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
            background: rgba(255, 255, 255, 0.95);
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
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-green), var(--accent-blue));
            color: white;
        }

        .status-card {
            background: var(--white);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            border: 1px solid var(--gray-200);
            transition: all 0.3s ease;
        }

        .status-card:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .thinking-dots {
            display: inline-flex;
            gap: 2px;
        }

        .thinking-dots span {
            width: 4px;
            height: 4px;
            background: var(--gray-600);
            border-radius: 50%;
            animation: thinking 1.4s infinite ease-in-out;
        }

        .thinking-dots span:nth-child(2) {
            animation-delay: 0.2s;
        }

        .thinking-dots span:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes thinking {
            0%, 80%, 100% {
                opacity: 0;
                transform: scale(0.8);
            }
            40% {
                opacity: 1;
                transform: scale(1);
            }
        }

        .lock-overlay {
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
            transition: all 0.3s ease;
            border-radius: 16px;
        }

        .lock-overlay:hover {
            background: rgba(0, 0, 0, 0.7);
        }

        @media (max-width: 768px) {
            .glass-card {
                margin: 0.5rem;
                padding: 1.5rem;
            }
            
            .metric-card {
                margin: 0.5rem;
            }
        }
        /* Animasi kipas dengan CSS murni */
        .fan-icon {
            width: 32px;
            height: 32px;
            border: 4px solid #fff;
            border-top: 4px solid #444;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: auto;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        @keyframes blink {
    0%, 80%, 100% { opacity: 0; }
    40% { opacity: 1; }
}

/* Container teks */
.thinking {
    display: inline-flex;
    align-items: center;
    font-weight: bold;
    color: #374151; /* Tailwind gray-700 */
}

/* Setiap titik */
.thinking span {
    animation: blink 1.4s infinite;
}

/* Delay untuk titik kedua dan ketiga */
.thinking span:nth-child(2) {
    animation-delay: 0.2s;
}
.thinking span:nth-child(3) {
    animation-delay: 0.4s;
}
/* Animasi berputar pelan */
@keyframes rotateIcon {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Animasi berdenyut */
@keyframes pulseIcon {
  0%, 100% { transform: scale(1); opacity: 1; }
  50% { transform: scale(1.15); opacity: 0.8; }
}

/* Style icon dalam label */
.label-icon {
  color: #16a34a; /* hijau */
  animation: pulseIcon 2s infinite ease-in-out;
  margin-right: 6px;
}

@keyframes slideUpFade {
    0% {
        transform: translateY(30px);
        opacity: 0;
    }
    100% {
        transform: translateY(0);
        opacity: 1;
    }
}
@keyframes pulseLock {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

.lock-overlay {
    animation: slideUpFade 0.4s ease-out forwards;
    cursor: pointer;
    transition: background-color 0.2s ease;
}
.lock-overlay:hover {
    background-color: rgba(0, 0, 0, 0.5);
}
.lock-icon {
    animation: pulseLock 1.5s infinite ease-in-out;
}

/* Login form animasi */
.login-form {
    animation: slideUpFade 0.4s ease-out forwards;
}
    </style>
</head>
<body class="min-h-screen">
    <!-- Header -->
    @include('layouts.partials.navbar')
    <!-- Main Content -->
    <main class="container mx-auto px-6 py-8 pt-32">
        @yield('content')
    </main>

    @include('layouts.partials.footer')

    @stack('scripts')
</body>
</html>
