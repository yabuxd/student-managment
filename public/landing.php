<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIS | Digitalize Your School</title>
    <meta name="description"
        content="A comprehensive digitalized and secured school management system for modern institutions.">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* Specific Landing Page Styles mimicking the Ethify screenshot */
        body {
            background-color: #111111;
            /* Slightly lighter dark for landing page base */
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
        }

        .landing-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2%;
            max-width: 1200px;
            width: 90%;
            margin: 0 auto;
            position: sticky;
            top: 0;
            z-index: 1000;
            backdrop-filter: blur(10px);
            background-color: rgba(10, 10, 10, 0.8);
            transition: all 0.3s ease;
        }

        .landing-nav .nav-center {
            display: flex;
            gap: 2rem;
            color: #a1a1aa;
            font-size: 0.875rem;
        }

        .landing-nav .nav-center a:hover {
            color: #fff;
        }

        .landing-nav .nav-right {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .special-offer-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border: 1px solid #333;
            border-radius: 2rem;
            font-size: 0.875rem;
            color: #a1a1aa;
            background-color: #0a0a0a;
            margin-bottom: 2rem;
        }

        .special-offer-pill span {
            color: #ededed;
        }

        .hero-section {
            text-align: center;
            padding: 4rem 1rem;
            max-width: 800px;
            margin: 0 auto;
        }

        .hero-section h1 {
            font-size: 4.5rem;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            font-weight: 500;
            color: #e5e5e5;
        }

        .hero-section p {
            font-size: 1.25rem;
            color: #a1a1aa;
            margin-bottom: 3rem;
            line-height: 1.6;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
        }

        .btn-white {
            background-color: #ededed;
            color: #0a0a0a;
            padding: 0.875rem 1.5rem;
            border-radius: 0.375rem;
            font-weight: 500;
        }

        .btn-white:hover {
            background-color: #ffffff;
        }

        .btn-dark {
            background-color: #222222;
            color: #ededed;
            padding: 0.875rem 1.5rem;
            border-radius: 0.375rem;
            font-weight: 500;
            border: 1px solid #333;
        }

        .btn-dark:hover {
            background-color: #333333;
        }

        .mockup-container {
            max-width: 1000px;
            margin: 0 auto 5rem auto;
            background-color: #0a0a0a;
            border: 1px solid #333;
            border-radius: 1rem 1rem 0 0;
            height: 300px;
            border-bottom: none;
            position: relative;
            overflow: hidden;
        }

        .mockup-header {
            height: 40px;
            background-color: #1a1a1a;
            border-bottom: 1px solid #333;
            display: flex;
            align-items: center;
            padding: 0 1rem;
            gap: 0.5rem;
        }

        .mockup-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #333;
        }

        /* Pricing */
        .pricing-section {
            background-color: #1c1b1a;
            /* Slight dark brown tint from image */
            padding: 6rem 5%;
            text-align: center;
        }

        .pricing-section h2 {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .pricing-section>p {
            color: #a1a1aa;
            margin-bottom: 4rem;
        }

        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            max-width: 1100px;
            margin: 0 auto;
            text-align: left;
        }

        .pricing-card {
            background-color: #111111;
            border: 1px solid #333;
            border-radius: 1rem;
            padding: 2.5rem;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .popular-badge {
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(90deg, #ff7eb3, #ff758c);
            color: #fff;
            padding: 0.25rem 1rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: bold;
        }

        .pricing-card h3 {
            font-size: 1.25rem;
            margin-bottom: 1rem;
            font-weight: 500;
        }

        .pricing-price {
            font-size: 2rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 0.5rem;
        }

        .pricing-price span {
            font-size: 1rem;
            font-weight: 400;
        }

        .pricing-desc {
            color: #a1a1aa;
            font-size: 0.875rem;
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 1px dashed #333;
        }

        .pricing-features {
            list-style: none;
            margin-bottom: 2.5rem;
            flex: 1;
        }

        .pricing-features li {
            color: #ededed;
            font-size: 0.875rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .pricing-features li svg {
            color: #fff;
        }

        .btn-pricing-dark {
            background-color: #222;
            color: #ededed;
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }

        .btn-pricing-dark:hover {
            background-color: #333;
        }

        .btn-pricing-brown {
            background-color: #8c6d59;
            color: #fff;
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }

        .btn-pricing-brown:hover {
            background-color: #7a5c48;
        }

        /* About & How It Works Sections */
        .info-section {
            padding: 6rem 5%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .info-section h2 {
            font-size: 3rem;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        
        .info-section > p.subtitle {
            text-align: center;
            color: #a1a1aa;
            margin-bottom: 4rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
        }

        .feature-box {
            background-color: #111;
            border: 1px solid #333;
            border-radius: 1rem;
            padding: 2rem;
            transition: transform 0.3s ease;
        }

        .feature-box:hover {
            transform: translateY(-5px);
            border-color: #555;
        }

        .feature-box h3 {
            font-size: 1.25rem;
            margin-bottom: 1rem;
            color: #ededed;
        }

        .feature-box p {
            color: #a1a1aa;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        /* How It Works Steps */
        .steps-container {
            display: flex;
            flex-direction: column;
            gap: 2rem;
            max-width: 800px;
            margin: 0 auto;
        }

        .step-item {
            display: flex;
            gap: 1.5rem;
            align-items: flex-start;
            background: #111;
            padding: 1.5rem;
            border-radius: 1rem;
            border: 1px solid #333;
        }

        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #222;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.25rem;
            flex-shrink: 0;
            border: 1px solid #444;
        }

        .step-content h3 {
            margin-bottom: 0.5rem;
            font-size: 1.25rem;
            color: #fff;
        }
        
        .step-content p {
            color: #a1a1aa;
            line-height: 1.5;
            margin: 0;
        }

        /* Footer */
        .site-footer {
            background-color: #0a0a0a;
            border-top: 1px solid #333;
            padding: 4rem 5% 2rem;
            margin-top: 4rem;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 3rem;
            max-width: 1200px;
            margin: 0 auto;
            margin-bottom: 3rem;
        }

        .footer-col h4 {
            color: #fff;
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
            margin-top: 0;
        }

        .footer-col ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-col ul li {
            margin-bottom: 0.75rem;
        }

        .footer-col ul li a {
            color: #a1a1aa;
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer-col ul li a:hover {
            color: #fff;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid #222;
            color: #777;
            font-size: 0.875rem;
        }
    </style>
</head>

<body>

    <nav class="landing-nav">
        <div class="logo" style="color: #fff; font-weight: 500; font-size: 1.25rem;">
            SIS
        </div>
        <div class="nav-center">
            <a href="#about">About</a>
            <a href="#how-it-works">How It Works</a>
            <a href="#pricing">Pricing</a>
        </div>
        <div class="nav-right">
            <a href="/auth/login" class="btn btn-outline" style="border: 1px solid #333; color: #a1a1aa;">Login</a>
            <a href="/auth/signup" class="btn btn-auth" style="background-color: #8c6d59; border: none; color: #fff;">Sign
                Up</a>
        </div>
    </nav>

    <main>
        <section class="hero-section">
            <div class="special-offer-pill">
                <span
                    style="background: linear-gradient(90deg, #ff7eb3, #ff758c); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Special
                    offer:</span>
                Get 1 month free trial and free setup assistance!
                <span style="color: #555;">→</span>
            </div>

            <h1>Digitalize your School<br>with Your own Portal</h1>
            <p>Build your institution's brand, reach more parents, and manage your school with a beautiful, easy-to-use
                digital system.</p>

            <div class="hero-buttons">
                <a href="/auth/signup" class="btn btn-white">Start Building</a>
                <a href="#" class="btn btn-dark">Request a demo</a>
            </div>
        </section>

        <div class="mockup-container">
            <div class="mockup-header">
                <div class="mockup-dot"></div>
                <div class="mockup-dot"></div>
                <div class="mockup-dot"></div>
            </div>
            <!-- Mockup content area -->
            <div style="padding: 2rem; display: flex; gap: 2rem;">
                <div style="width: 250px; background: #111; border-radius: 0.5rem; height: 200px; border: 1px solid #333;"></div>
                <div style="flex: 1; background: #111; border-radius: 0.5rem; height: 200px; border: 1px solid #333;"></div>
            </div>
        </div>

        <section id="about" class="info-section">
            <h2>About SIS</h2>
            <p class="subtitle">A complete ecosystem designed to bridge the gap between education and technology, empowering schools to operate efficiently.</p>
            
            <div class="features-grid">
                <div class="feature-box">
                    <h3>Multi-Tenant Architecture</h3>
                    <p>Every school gets its own dedicated subdomain and securely partitioned database workspace. Maintain your unique branding and data privacy seamlessly.</p>
                </div>
                <div class="feature-box">
                    <h3>Role-Based Portals</h3>
                    <p>Tailored dashboards for Directors, Teachers, Students, and Parents. Everyone gets exactly the tools they need without the clutter.</p>
                </div>
                <div class="feature-box">
                    <h3>Real-Time Insights</h3>
                    <p>Track attendance, monitor academic performance, and manage faculty scheduling with live data dashboards built for immediate action.</p>
                </div>
                <div class="feature-box">
                    <h3>Seamless Communication</h3>
                    <p>Integrated messaging systems allow teachers to instantly connect with parents and students, fostering a collaborative educational environment.</p>
                </div>
            </div>
        </section>

        <section id="how-it-works" class="info-section" style="background-color: #151515;">
            <h2>How It Works</h2>
            <p class="subtitle">Getting your institution online is faster than you think. Follow these simple steps.</p>
            
            <div class="steps-container">
                <div class="step-item">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h3>Register Your School</h3>
                        <p>Sign up and claim your unique school subdomain (e.g. yourschool.sis.com). Customize your branding and select your preferred template theme.</p>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h3>Configure the System</h3>
                        <p>Log in as the Director to set up academic years, create grade-level sections, and define your curriculum subjects.</p>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h3>Onboard Users</h3>
                        <p>Import your faculty, students, and parents. Assign teachers to classes and students to their respective grade sections.</p>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-number">4</div>
                    <div class="step-content">
                        <h3>Go Live</h3>
                        <p>Your school is fully digitalized. Teachers can start recording grades, parents can monitor progress, and you can oversee everything from your command center.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="pricing" class="pricing-section">
            <h2>Pricing</h2>
            <p>Choose the perfect plan for your institution. Start for free and upgrade as you grow with SIS.</p>

            <div class="pricing-grid">

                <!-- Starter -->
                <div class="pricing-card">
                    <h3>Starter</h3>
                    <div class="pricing-price">200 ETB <span>/ mo</span></div>
                    <div class="pricing-desc">Perfect for testing the waters.</div>
                    <ul class="pricing-features">
                        <li><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg> 1 School Domain</li>
                        <li><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg> Up to 500 Students</li>
                        <li><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg> Basic Analytics</li>
                        <li><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg> Standard Support</li>
                    </ul>
                    <button class="btn btn-pricing-dark" onclick="mockCheckout(1, 'Starter')">Get Started</button>
                </div>

                <!-- Growth (Popular) -->
                <div class="pricing-card" style="border-color: #555;">
                    <div class="popular-badge">Popular</div>
                    <h3>Growth</h3>
                    <div class="pricing-price">399 ETB <span>/ mo</span></div>
                    <div class="pricing-desc">For growing institutions.</div>
                    <ul class="pricing-features">
                        <li><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg> Everything in Starter</li>
                        <li><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg> Custom Domain Connection</li>
                        <li><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg> Up to 2000 Students</li>
                        <li><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg> Priority Support</li>
                        <li><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                    </ul>
                    <button class="btn btn-pricing-brown" onclick="mockCheckout(2, 'Growth')">Get Started</button>
                </div>

                <!-- Scale -->
                <div class="pricing-card">
                    <h3>Scale</h3>
                    <div class="pricing-price">799 ETB <span>/ mo</span></div>
                    <div class="pricing-desc">For high-volume schools.</div>
                    <ul class="pricing-features">
                        <li><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg> Everything in Growth</li>
                        <li><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg> Unlimited Students</li>
                        <li><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg> Advanced API Tools</li>
                            </svg>
                        <li><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg> Dedicated Account Manager</li>
                    </ul>
                    <button class="btn btn-pricing-dark" onclick="mockCheckout(3, 'Scale')">Get Started</button>
                </div>

            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="footer-grid">
            <div class="footer-col">
                <div class="logo" style="color: #fff; font-weight: 700; font-size: 1.5rem; margin-bottom: 1rem;">SIS</div>
                <p style="color: #a1a1aa; font-size: 0.9rem; line-height: 1.5; margin-bottom: 1.5rem;">
                    Empowering educational institutions with modern, secure, and intuitive digital tools.
                </p>
            </div>
            <div class="footer-col">
                <h4>Product</h4>
                <ul>
                    <li><a href="#features">Features</a></li>
                    <li><a href="#pricing">Pricing</a></li>
                    <li><a href="#how-it-works">How It Works</a></li>
                    <li><a href="#">Security</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Resources</h4>
                <ul>
                    <li><a href="#">Help Center</a></li>
                    <li><a href="#">Documentation</a></li>
                    <li><a href="#">School Templates</a></li>
                    <li><a href="#">API Reference</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Legal</h4>
                <ul>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                    <li><a href="#">Data Processing</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; 2026 School Management System (SIS). All rights reserved.
        </div>
    </footer>

    <!-- Checkout Modal Mock -->
    <div class="modal-overlay" id="checkoutModal">
        <div class="modal">
            <div class="modal-header">
                <h3 style="color: #fff;">Complete Purchase: <span id="checkoutPlanName"></span></h3>
                <button class="close-btn" onclick="closeModal('checkoutModal')">&times;</button>
            </div>
            <div class="form-group">
                <label>Credit Card Number (Mock)</label>
                <input type="text" class="form-control" placeholder="**** **** **** ****" value="4242 4242 4242 4242">
            </div>
            <button class="btn btn-white" onclick="processCheckout()" style="width: 100%">Confirm & Pay</button>
        </div>
    </div>

    <script src="assets/js/app.js"></script>
</body>

</html>
