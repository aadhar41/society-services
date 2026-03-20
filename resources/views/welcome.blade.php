<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SAMS - Society Accounting Management System</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Styles -->
    <style>
        :root {
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --secondary: #10b981;
            --bg-light: #f8fafc;
            --bg-dark: #0f172a;
            --card-light: #ffffff;
            --card-dark: #1e293b;
            --text-light: #334155;
            --text-dark: #cbd5e1;
            --title-light: #0f172a;
            --title-dark: #f8fafc;
            --radius-lg: 1rem;
            --radius-md: 0.75rem;
            --shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-light);
            line-height: 1.6;
            transition: var(--transition);
            overflow-x: hidden;
        }

        h1,
        h2,
        h3,
        h4 {
            font-family: 'Outfit', sans-serif;
            color: var(--title-light);
            font-weight: 700;
        }

        @media (prefers-color-scheme: dark) {
            body {
                background-color: var(--bg-dark);
                color: var(--text-dark);
            }

            h1,
            h2,
            h3,
            h4 {
                color: var(--title-dark);
            }
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* Navigation */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 0;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            z-index: 10;
        }

        .logo {
            font-family: 'Outfit', sans-serif;
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .logo svg {
            width: 32px;
            height: 32px;
        }

        .nav-links {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: inherit;
            font-weight: 500;
            font-size: 0.9rem;
            transition: var(--transition);
        }

        .nav-links a:hover {
            color: var(--primary);
        }

        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            border-radius: var(--radius-md);
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
            cursor: pointer;
            border: none;
            font-size: 0.95rem;
        }

        .btn-primary {
            background: var(--primary);
            color: white !important;
            box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.4);
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.4);
        }

        .btn-outline {
            border: 2px solid var(--primary);
            color: var(--primary) !important;
        }

        .btn-outline:hover {
            background: rgba(99, 102, 241, 0.05);
        }

        /* Hero Section */
        .hero {
            padding: 10rem 0 6rem;
            position: relative;
            text-align: center;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -20%;
            width: 140%;
            height: 140%;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.08) 0%, transparent 70%);
            z-index: -1;
        }

        .hero-badge {
            display: inline-block;
            padding: 0.4rem 1rem;
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary);
            border-radius: 2rem;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 2rem;
        }

        .hero h1 {
            font-size: clamp(2.5rem, 8vw, 4rem);
            line-height: 1.1;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, var(--primary) 0%, #a855f7 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p {
            font-size: 1.25rem;
            max-width: 700px;
            margin: 0 auto 2.5rem;
            color: var(--text-light);
            opacity: 0.8;
        }

        @media (prefers-color-scheme: dark) {
            .hero p {
                color: var(--text-dark);
            }
        }

        .hero-actions {
            display: flex;
            justify-content: center;
            gap: 1rem;
        }

        /* Features Grid */
        .features {
            padding: 6rem 0;
        }

        .section-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-header h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .section-header p {
            max-width: 600px;
            margin: 0 auto;
            opacity: 0.7;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            padding: 2.5rem;
            background: var(--card-light);
            border-radius: var(--radius-lg);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            transition: var(--transition);
            border: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        @media (prefers-color-scheme: dark) {
            .feature-card {
                background: var(--card-dark);
                border-color: rgba(255, 255, 255, 0.05);
            }
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow);
            border-color: var(--primary);
        }

        .feature-icon {
            width: 56px;
            height: 56px;
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary);
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .feature-icon svg {
            width: 28px;
            height: 28px;
        }

        .feature-card h3 {
            font-size: 1.25rem;
        }

        /* Stats Section */
        .stats {
            padding: 4rem 0;
            background: var(--primary);
            color: white;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 3rem;
            text-align: center;
        }

        .stat-item h4 {
            font-size: 2.5rem;
            color: white;
            margin-bottom: 0.5rem;
        }

        .stat-item p {
            opacity: 0.9;
            font-weight: 500;
        }

        /* Footer */
        footer {
            padding: 4rem 0 2rem;
            text-align: center;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }

        @media (prefers-color-scheme: dark) {
            footer {
                border-color: rgba(255, 255, 255, 0.05);
            }
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin: 2rem 0;
        }

        .footer-links a {
            text-decoration: none;
            color: inherit;
            opacity: 0.6;
            transition: var(--transition);
        }

        .footer-links a:hover {
            color: var(--primary);
            opacity: 1;
        }

        .copyright {
            font-size: 0.9rem;
            opacity: 0.5;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .delay-1 {
            animation-delay: 0.1s;
        }

        .delay-2 {
            animation-delay: 0.2s;
        }

        .delay-3 {
            animation-delay: 0.3s;
        }

        /* Mobile adjustments */
        @media (max-width: 768px) {
            .container {
                padding: 0 1.5rem;
            }

            .hero {
                padding: 8rem 0 4rem;
            }

            .hero h1 {
                font-size: 3rem;
            }

            .hero-actions {
                flex-direction: column;
                padding: 0 2rem;
            }

            .nav-links {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <nav>
            <a href="/" class="logo">
                <svg fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L2 7v10l10 5 10-5V7L12 2zm0 2.8L19.5 8 12 11.2 4.5 8 12 4.8zM4 16.3V9.5l7 3.2v6.8l-7-3.2zm16 0l-7 3.2v-6.8l7-3.2v6.8z" />
                </svg>
                SAMS
            </a>
            <div class="nav-links">
                @if (Route::has('login'))
                @auth
                <a href="{{ url('/home') }}">Dashboard</a>
                @else
                <a href="{{ route('login') }}">Login</a>
                @if (Route::has('register'))
                <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
                @endif
                @endauth
                @endif
            </div>
        </nav>
    </div>

    <section class="hero">
        <div class="container">
            <!-- <span class="hero-badge animate"></span> -->
            <h1 class="animate delay-1">Smart Management for Modern Societies</h1>
            <p class="animate delay-2">The all-in-one ERP solution for accounting, automated billing, and a seamless resident experience.</p>
            <div class="hero-actions animate delay-3">
                @if (Route::has('login'))
                @auth
                <a href="{{ url('/home') }}" class="btn btn-primary">Launch Dashboard</a>
                @else
                <a href="{{ route('login') }}" class="btn btn-primary">Get Started</a>
                <a href="#features" class="btn btn-outline">Explore Features</a>
                @endauth
                @endif
            </div>
        </div>
    </section>

    <section class="stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <h4>500+</h4>
                    <p>Societies Managed</p>
                </div>
                <div class="stat-item">
                    <h4>50k+</h4>
                    <p>Active Residents</p>
                </div>
                <div class="stat-item">
                    <h4>$2M+</h4>
                    <p>Invoices Automated</p>
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="features">
        <div class="container">
            <div class="section-header">
                <h2>Everything you need to thrive</h2>
                <p>Powerful tools designed to simplify complex society management tasks.</p>
            </div>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3>Professional Accounting</h3>
                    <p>Double-entry bookkeeping system with automated Trial Balance, Balance Sheet, and P&L reports tailored for Indian Societies.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3>Automated Billing</h3>
                    <p>Recurring invoice generation with balance tracking, late fee calculation, and automated defaulter reports.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h3>Smart Infrastructure</h3>
                    <p>Modular setup for Wings, Floors, Units, and Parking. Comprehensive member and resident lifecycle management.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3>Security & Visitors</h3>
                    <p>Visitor management with OTP verification and seamless society access control systems.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M7 12l3-3 3 3 4-4M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3>Advanced Analytics</h3>
                    <p>Real-time insights through interactive dashboards. Track maintenance trends and cashflow with ease.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 005.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3>Multi-Tenancy</h3>
                    <p>Scalable SaaS architecture with shared schema isolation, ensuring your data is secure and logically separated.</p>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <a href="/" class="logo" style="justify-content: center; margin-bottom: 1rem;">
                <svg fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L2 7v10l10 5 10-5V7L12 2zm0 2.8L19.5 8 12 11.2 4.5 8 12 4.8zM4 16.3V9.5l7 3.2v6.8l-7-3.2zm16 0l-7 3.2v-6.8l7-3.2v6.8z" />
                </svg>
                SAMS
            </a>
            <p>Proprietary Software by <strong>Aadhar Gaur</strong></p>
            <div class="footer-links">
                <a href="https://laravel.com/docs">Laravel Docs</a>
                <a href="https://github.com/aadhar41">GitHub</a>
                <a href="#">Terms</a>
                <a href="#">Privacy</a>
            </div>
            <p class="copyright">Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }}) &bull; &copy; {{ date('Y') }} SAMS ERP.</p>
        </div>
    </footer>
</body>

</html>