<?php
require_once __DIR__ . '/../../includes/session.php';
$flash = get_flash_message();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - HRFlowSn</title>
    <meta name="description" content="Connectez-vous à HRFlowSn, votre système de gestion des ressources humaines.">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', sans-serif;
            min-height: 100vh;
            display: flex;
            background: #0f0a1e;
            overflow: hidden;
        }

        /* ─── Left Panel: Animated Branding ─── */
        .login-left {
            flex: 1;
            background: linear-gradient(135deg, #1a0533 0%, #2d1b69 30%, #8B5CF6 60%, #EC4899 100%);
            background-size: 300% 300%;
            animation: gradientShift 8s ease infinite;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 40px;
            position: relative;
            overflow: hidden;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .login-left::before,
        .login-left::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.4;
            animation: float 6s ease-in-out infinite;
        }

        .login-left::before {
            width: 300px;
            height: 300px;
            background: #EC4899;
            top: 10%;
            left: -5%;
        }

        .login-left::after {
            width: 250px;
            height: 250px;
            background: #8B5CF6;
            bottom: 10%;
            right: -5%;
            animation-delay: -3s;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(30px, -30px); }
        }

        .brand-content {
            position: relative;
            z-index: 2;
            text-align: center;
            color: white;
        }

        .brand-icon {
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 32px;
            font-size: 48px;
            box-shadow: 0 16px 48px rgba(0, 0, 0, 0.3);
            animation: iconPulse 3s ease-in-out infinite;
        }

        @keyframes iconPulse {
            0%, 100% { box-shadow: 0 16px 48px rgba(0, 0, 0, 0.3); }
            50% { box-shadow: 0 16px 48px rgba(139, 92, 246, 0.5); }
        }

        .brand-content h1 {
            font-size: 42px;
            font-weight: 800;
            letter-spacing: -1px;
            margin-bottom: 16px;
            text-shadow: 0 4px 24px rgba(0, 0, 0, 0.3);
        }

        .brand-content p {
            font-size: 17px;
            font-weight: 300;
            opacity: 0.85;
            max-width: 360px;
            line-height: 1.6;
        }

        .brand-features {
            list-style: none;
            margin-top: 48px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .brand-features li {
            display: flex;
            align-items: center;
            gap: 14px;
            font-size: 15px;
            font-weight: 400;
            opacity: 0.9;
        }

        .brand-features li i {
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, 0.12);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        /* ─── Right Panel: Login Form ─── */
        .login-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: #0f0a1e;
            position: relative;
        }

        .login-right::before {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(139, 92, 246, 0.12) 0%, transparent 70%);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .login-form-wrapper {
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 2;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-header {
            margin-bottom: 40px;
        }

        .form-header h2 {
            font-size: 32px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 8px;
        }

        .form-header p {
            color: #9CA3AF;
            font-size: 15px;
            font-weight: 400;
        }

        /* Alert */
        .login-alert {
            padding: 14px 18px;
            border-radius: 14px;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideUp 0.4s ease-out;
        }

        .login-alert-success {
            background: rgba(16, 185, 129, 0.12);
            color: #34D399;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .login-alert-error {
            background: rgba(239, 68, 68, 0.12);
            color: #F87171;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .login-alert .btn-close-alert {
            margin-left: auto;
            background: none;
            border: none;
            color: inherit;
            cursor: pointer;
            font-size: 18px;
            opacity: 0.7;
            transition: opacity 0.2s;
        }

        .login-alert .btn-close-alert:hover {
            opacity: 1;
        }

        /* Form Fields */
        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #D1D5DB;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrapper i {
            position: absolute;
            left: 18px;
            color: #6B7280;
            font-size: 18px;
            transition: color 0.3s;
            z-index: 1;
        }

        .input-wrapper input {
            width: 100%;
            padding: 16px 18px 16px 52px;
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            color: #fff;
            font-family: 'Outfit', sans-serif;
            font-size: 15px;
            transition: all 0.3s ease;
            outline: none;
        }

        .input-wrapper input::placeholder {
            color: #4B5563;
        }

        .input-wrapper input:focus {
            border-color: #8B5CF6;
            background: rgba(139, 92, 246, 0.06);
            box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.12);
        }

        .input-wrapper input:focus + i,
        .input-wrapper:focus-within i {
            color: #8B5CF6;
        }

        .password-toggle {
            position: absolute;
            right: 18px;
            background: none;
            border: none;
            color: #6B7280;
            cursor: pointer;
            font-size: 18px;
            transition: color 0.3s;
            z-index: 1;
        }

        .password-toggle:hover {
            color: #8B5CF6;
        }

        /* Submit Button */
        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 50%, #EC4899 100%);
            background-size: 200% 200%;
            color: white;
            border: none;
            border-radius: 14px;
            font-family: 'Outfit', sans-serif;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(139, 92, 246, 0.35);
            margin-top: 8px;
        }

        .btn-login:hover {
            background-position: 100% 50%;
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(139, 92, 246, 0.5);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                45deg,
                transparent 30%,
                rgba(255, 255, 255, 0.1) 50%,
                transparent 70%
            );
            transition: transform 0.6s;
            transform: translateX(-100%);
        }

        .btn-login:hover::after {
            transform: translateX(100%);
        }

        /* Footer Link */
        .form-footer {
            text-align: center;
            margin-top: 32px;
            font-size: 14px;
            color: #9CA3AF;
        }

        .form-footer a {
            color: #A78BFA;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            position: relative;
        }

        .form-footer a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(135deg, #8B5CF6, #EC4899);
            transition: width 0.3s ease;
        }

        .form-footer a:hover {
            color: #C4B5FD;
        }

        .form-footer a:hover::after {
            width: 100%;
        }

        /* Divider */
        .form-divider {
            display: flex;
            align-items: center;
            margin: 28px 0;
            color: #4B5563;
            font-size: 13px;
        }

        .form-divider::before,
        .form-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255, 255, 255, 0.08);
        }

        .form-divider span {
            padding: 0 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 500;
        }

        /* ─── Responsive ─── */
        @media (max-width: 968px) {
            body {
                flex-direction: column;
                overflow: auto;
            }

            .login-left {
                padding: 40px 24px;
                min-height: auto;
            }

            .brand-content h1 {
                font-size: 32px;
            }

            .brand-features {
                display: none;
            }

            .login-right {
                padding: 40px 24px;
                min-height: 70vh;
            }
        }

        @media (max-width: 480px) {
            .login-left {
                padding: 32px 20px;
            }

            .brand-icon {
                width: 72px;
                height: 72px;
                font-size: 36px;
                border-radius: 20px;
                margin-bottom: 20px;
            }

            .brand-content h1 {
                font-size: 26px;
            }

            .login-right {
                padding: 32px 20px;
            }

            .form-header h2 {
                font-size: 26px;
            }
        }
    </style>
</head>
<body>
    <!-- Left Panel: Branding -->
    <div class="login-left">
        <div class="brand-content">
            <div class="brand-icon">
                <i class="bi bi-people-fill"></i>
            </div>
            <h1>HRFlowSn</h1>
            <p>Votre plateforme intelligente de gestion des ressources humaines</p>
            <ul class="brand-features">
                <li>
                    <i class="bi bi-shield-check"></i>
                    <span>Gestion sécurisée des données</span>
                </li>
                <li>
                    <i class="bi bi-graph-up-arrow"></i>
                    <span>Suivi des performances en temps réel</span>
                </li>
                <li>
                    <i class="bi bi-calendar2-check"></i>
                    <span>Gestion des congés simplifiée</span>
                </li>
                <li>
                    <i class="bi bi-cash-coin"></i>
                    <span>Paie et contrats automatisés</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Right Panel: Login Form -->
    <div class="login-right">
        <div class="login-form-wrapper">
            <div class="form-header">
                <h2>Bienvenue 👋</h2>
                <p>Connectez-vous pour accéder à votre espace</p>
            </div>

            <?php if ($flash): ?>
            <div class="login-alert login-alert-<?= $flash['type'] === 'success' ? 'success' : 'error' ?>" id="flashAlert">
                <i class="bi bi-<?= $flash['type'] === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill' ?>"></i>
                <span><?= htmlspecialchars($flash['message']) ?></span>
                <button class="btn-close-alert" onclick="document.getElementById('flashAlert').style.display='none'">
                    <i class="bi bi-x"></i>
                </button>
            </div>
            <?php endif; ?>

            <form method="POST" action="/HRFlowSn/index.php?route=auth/login" id="loginForm">
                <div class="form-group">
                    <label for="email">Adresse email</label>
                    <div class="input-wrapper">
                        <i class="bi bi-envelope"></i>
                        <input type="email" id="email" name="email" placeholder="nom@entreprise.com" required autocomplete="email">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <div class="input-wrapper">
                        <i class="bi bi-lock"></i>
                        <input type="password" id="password" name="password" placeholder="••••••••" required autocomplete="current-password">
                        <button type="button" class="password-toggle" id="togglePassword" aria-label="Afficher le mot de passe">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-login" id="btnLogin">
                    Se connecter
                </button>
            </form>

            <div class="form-divider">
                <span>ou</span>
            </div>

            <div class="form-footer">
                <p>Pas encore de compte ? <a href="/HRFlowSn/index.php?route=auth/register">Créer un compte</a></p>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.querySelector('i').classList.toggle('bi-eye');
            this.querySelector('i').classList.toggle('bi-eye-slash');
        });

        // Form submit animation
        document.getElementById('loginForm').addEventListener('submit', function () {
            const btn = document.getElementById('btnLogin');
            btn.innerHTML = '<i class="bi bi-arrow-repeat" style="animation: spin 1s linear infinite; display: inline-block;"></i> Connexion...';
            btn.style.pointerEvents = 'none';
        });

        // Add spin animation
        const style = document.createElement('style');
        style.textContent = '@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }';
        document.head.appendChild(style);
    </script>
</body>
</html>
