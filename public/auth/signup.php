<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | SIS</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        /* Specific overrides for Auth matching Vercel screens exactly */
        body {
            background-color: #0a0a0a;
        }

        .auth-card {
            background-color: transparent;
            border: 1px solid #222;
            padding: 2.5rem;
            max-width: 420px;
        }

        .auth-card h2 {
            font-size: 1.5rem;
            margin-bottom: 0.25rem;
            font-weight: 600;
        }

        .auth-card p.subtitle {
            color: #a1a1aa;
            font-size: 0.875rem;
            margin-bottom: 2rem;
        }

        .form-group label {
            display: flex;
            justify-content: space-between;
            color: #ededed;
        }

        .form-control {
            background-color: #0a0a0a;
            border-color: #333;
            color: #ededed;
        }

        .form-control::placeholder {
            color: #555;
        }

        .btn-auth {
            background-color: #8c6d59;
            color: #fff;
            margin-bottom: 0.75rem;
            border: none;
            font-weight: 500;
        }

        .btn-auth:hover {
            background-color: #7a5c48;
        }

        .btn-google {
            background-color: #222;
            color: #ededed;
            border: 1px solid #333;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-google:hover {
            background-color: #333;
        }

        .google-icon {
            width: 16px;
            height: 16px;
        }

        .footer-text {
            margin-top: 1.5rem;
            color: #a1a1aa;
        }

        .footer-text a {
            color: #ededed;
            text-decoration: underline;
            text-underline-offset: 4px;
        }
    </style>
</head>

<body>
    <div class="auth-wrapper">
        <a href="/" class="back-btn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 18l-6-6 6-6" />
            </svg>
            Back
        </a>

        <!-- Register Section -->
        <div class="auth-card" id="registerSection">
            <h2>Create account</h2>
            <p class="subtitle">Enter your email below to create a new account</p>

            <div id="authAlert" class="alert"></div>

            <form id="registerForm" onsubmit="handleAuth(event, 'register')">
                <div class="form-group">
                    <label for="regFullName">Name</label>
                    <input type="text" id="regFullName" class="form-control" placeholder="John Doe" required>
                </div>
                <div class="form-group">
                    <label for="regEmail">Email</label>
                    <input type="email" id="regEmail" class="form-control" placeholder="m@example.com" required>
                    <input type="hidden" id="regUsername" value=""> <!-- Kept for backend compatibility -->
                </div>
                <div class="form-group">
                    <label for="regPassword">Password</label>
                    <input type="password" id="regPassword" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-auth">Sign Up</button>
                <button type="button" class="btn btn-google">
                    <svg class="google-icon" viewBox="0 0 24 24">
                        <path fill="#4285F4"
                            d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                        <path fill="#34A853"
                            d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                        <path fill="#FBBC05"
                            d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                        <path fill="#EA4335"
                            d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                        <path fill="none" d="M1 1h22v22H1z" />
                    </svg>
                    Sign Up with Google
                </button>

                <div class="footer-text">
                    Already have an account? <a href="/auth/login">Login</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Ensure API_BASE works before calling handleAuth -->
    <script>
        const API_BASE = '/api';
    </script>
    <script src="/assets/js/app.js"></script>
    <script>
        // Sync email to hidden username field on typing
        document.getElementById('regEmail').addEventListener('input', function () {
            document.getElementById('regUsername').value = this.value;
        });
    </script>
</body>

</html>
