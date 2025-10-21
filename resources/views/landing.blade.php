<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CTP Faisalabad Welfare & E-Services Portal</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --ctp-navy: #1A3E6D; /* Primary: Authority & Trust */
            --ctp-teal: #00A693; /* Secondary: Action & Efficiency */
            --ctp-amber: #FFC000; /* Accent: Highlight & Warning */
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #F8F9FA; /* Light Gray Background */
        }

        /* Custom Colors for Bootstrap Classes */
        .bg-ctp-navy { background-color: var(--ctp-navy) !important; }
        .text-ctp-teal { color: var(--ctp-teal) !important; }
        .btn-ctp-teal {
            background-color: var(--ctp-teal);
            border-color: var(--ctp-teal);
            color: white;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .btn-ctp-teal:hover {
            background-color: #008f7d;
            border-color: #008f7d;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 166, 147, 0.4);
        }

        /* Hero Section Styling */
        .hero-section {
            background: linear-gradient(rgba(26, 62, 109, 0.85), rgba(26, 62, 109, 0.7)), url('placeholder-bg-traffic.jpg') center center/cover;
            /* Use a relevant, high-res image of Faisalabad traffic/road */
            color: white;
            padding: 8rem 0;
        }

        /* Road Icon Animation (Traffic Flow) */
        @keyframes flow-in {
            0% { transform: translateX(-100px); opacity: 0; }
            100% { transform: translateX(0); opacity: 1; }
        }

        .traffic-icon-flow {
            animation: flow-in 1.5s ease-out 0.5s backwards; /* Subtle animation on load */
        }

        .traffic-icon-flow:nth-child(2) { animation-delay: 0.8s; } /* Stagger the icons */
        .traffic-icon-flow:nth-child(3) { animation-delay: 1.1s; }

        /* Feature Cards */
        .feature-card {
            border-top: 4px solid var(--ctp-teal);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
        }
        .feature-card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            transform: translateY(-5px);
        }

    </style>
</head>
<body data-bs-spy="scroll" data-bs-target="#mainNav">

    <header>
        <nav id="mainNav" class="navbar navbar-expand-lg navbar-dark bg-ctp-navy shadow-sm fixed-top">
            <div class="container">
                <a class="navbar-brand fw-bold" href="#">
                    <i class="bi bi-stoplights me-2 text-ctp-teal"></i> CTP Faisalabad E-Portal
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link" href="#hero">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="#services">E-Services</a></li>
                        <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                    </ul>
                    <a href="#" class="btn btn-warning btn-sm fw-bold ms-lg-3 d-none d-lg-inline-block">Staff Login</a>
                </div>
            </div>
        </nav>
    </header>

    <section id="hero" class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bolder mb-3">
                Your Road to <span class="text-ctp-teal">Safer, Easier</span> Traffic Services.
            </h1>
            <p class="lead mb-5 text-opacity-75">Official Welfare & E-Challan Management System by CTP Faisalabad. Speed, Safety, and Transparency.</p>

            <div class="d-flex justify-content-center gap-4 mb-5">
                <i class="bi bi-bus-front display-4 traffic-icon-flow"></i>
                <i class="bi bi-car-front display-4 traffic-icon-flow"></i>
                <i class="bi bi-motorcycle display-4 traffic-icon-flow"></i>
            </div>

            <div class="d-grid gap-3 d-md-flex justify-content-center">
                <a href="/pay-fee" class="btn btn-ctp-teal btn-lg px-5 shadow-lg">
                    <i class="bi bi-wallet2 me-2"></i> Pay Medical Check-up Fee &rarr;
                </a>

                <a href="/dept-challan-login" class="btn btn-outline-light btn-lg px-5">
                    <i class="bi bi-person-badge me-2"></i> Lifter Squad E-Challan Login
                </a>
            </div>
        </div>
    </section>

    <section id="services" class="py-5 bg-white">
        <div class="container">
            <h2 class="text-center mb-5 fw-bold text-ctp-navy">Key Road & Licensing E-Services</h2>

            <div class="row g-4">

                <div class="col-md-4">
                    <div class="card p-4 h-100 border-0 feature-card">
                        <i class="bi bi-file-medical-fill fs-1 mb-3 text-ctp-teal"></i>
                        <h3 class="card-title h5 fw-bold text-ctp-navy">License Medical Clearance</h3>
                        <p class="card-text">Securely pay the required fee online for your driving license medical check-up. Mandatory for public and departmental applicants.</p>
                        <a href="/pay-fee" class="stretched-link text-ctp-teal fw-bold">Start Payment &rarr;</a>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card p-4 h-100 border-0 feature-card">
                        <i class="bi bi-receipt-cutoff fs-1 mb-3 text-ctp-teal"></i>
                        <h3 class="card-title h5 fw-bold text-ctp-navy">E-Challan Management</h3>
                        <p class="card-text">Exclusive system for Lifter Squads to issue and manage digital traffic violation tickets. Ensures transparency and immediate record-keeping.</p>
                        <a href="/dept-challan-login" class="stretched-link text-ctp-teal fw-bold">Department Access &rarr;</a>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card p-4 h-100 border-0 feature-card">
                        <i class="bi bi-stoplights-fill fs-1 mb-3 text-ctp-teal"></i>
                        <h3 class="card-title h5 fw-bold text-ctp-navy">Traffic Rules & Signs</h3>
                        <p class="card-text">Access a comprehensive guide to local traffic rules, road signs, and safe driving practices. Be a responsible citizen.</p>
                        <a href="/traffic-education" class="stretched-link text-ctp-teal fw-bold">Learn More &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer id="contact" class="bg-ctp-navy pt-5 pb-3">
        <div class="container text-white">
            <div class="row">

                <div class="col-md-5 mb-4">
                    <h5 class="fw-bold mb-3 text-ctp-teal">CTP Faisalabad Welfare Project</h5>
                    <p class="small text-white-50">Committed to modernizing traffic management and contributing to the welfare of the police force and the public through transparent e-services.</p>
                </div>

                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3 text-ctp-teal">Official Contact</h5>
                    <ul class="list-unstyled small">
                        <li><i class="bi bi-envelope-fill me-2"></i> <a href="mailto:contact@ctpfsd.gop.pk" class="text-white-75">contact@ctpfsd.gop.pk</a></li>
                        <li><i class="bi bi-telephone-fill me-2"></i> <a href="tel:+920419201163" class="text-white-75">+92 (041) 920-1163</a></li>
                        <li class="mt-2 text-white-50">CTO Office, Near Civil Lines Police Station, Faisalabad</li>
                    </ul>
                </div>

                <div class="col-md-3 mb-4">
                    <h5 class="fw-bold mb-3 text-ctp-teal">Quick Links</h5>
                    <ul class="list-unstyled small">
                        <li><a href="#" class="text-white-75">Privacy Policy</a></li>
                        <li><a href="#" class="text-white-75">Terms of Service</a></li>
                        <li><a href="#" class="text-white-75">Traffic Fine Calculator</a></li>
                    </ul>
                </div>
            </div>

            <div class="text-center border-top border-white-50 pt-3">
                <p class="small text-white-50">&copy; 2025 CTP Faisalabad. All rights reserved. Government of Punjab.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
