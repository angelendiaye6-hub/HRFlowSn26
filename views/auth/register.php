<?php
require_once __DIR__ . '/../../includes/session.php';
$flash = get_flash_message();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - HRFlowSn</title>
    <meta name="description" content="Créez votre compte HRFlowSn pour accéder au système de gestion des ressources humaines.">
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
        .register-left {
            flex: 1;
            background: linear-gradient(135deg, #1a0533 0%, #2d1b69 30%, #EC4899 60%, #8B5CF6 100%);
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

        .register-left::before,
        .register-left::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.4;
            animation: float 6s ease-in-out infinite;
        }

        .register-left::before {
            width: 300px;
            height: 300px;
            background: #8B5CF6;
            top: 10%;
            left: -5%;
        }

        .register-left::after {
            width: 250px;
            height: 250px;
            background: #EC4899;
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
            50% { box-shadow: 0 16px 48px rgba(236, 72, 153, 0.5); }
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

        .brand-steps {
            list-style: none;
            margin-top: 48px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .brand-steps li {
            display: flex;
            align-items: center;
            gap: 16px;
            font-size: 15px;
            font-weight: 400;
            opacity: 0.9;
        }

        .step-num {
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
            flex-shrink: 0;
        }

        /* ─── Right Panel: Register Form ─── */
        .register-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: #0f0a1e;
            position: relative;
            overflow-y: auto;
        }

        .register-right::before {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(236, 72, 153, 0.1) 0%, transparent 70%);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .register-form-wrapper {
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
            margin-bottom: 36px;
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
        .register-alert {
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

        .register-alert-success {
            background: rgba(16, 185, 129, 0.12);
            color: #34D399;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .register-alert-error {
            background: rgba(239, 68, 68, 0.12);
            color: #F87171;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .register-alert .btn-close-alert {
            margin-left: auto;
            background: none;
            border: none;
            color: inherit;
            cursor: pointer;
            font-size: 18px;
            opacity: 0.7;
            transition: opacity 0.2s;
        }

        .register-alert .btn-close-alert:hover {
            opacity: 1;
        }

        /* Form Fields */
        .form-group {
            margin-bottom: 20px;
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

        .input-wrapper input,
        .input-wrapper select {
            width: 100%;
            padding: 14px 18px 14px 52px;
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            color: #fff;
            font-family: 'Outfit', sans-serif;
            font-size: 15px;
            transition: all 0.3s ease;
            outline: none;
        }

        .input-wrapper select {
            appearance: none;
            -webkit-appearance: none;
            cursor: pointer;
        }

        .input-wrapper select option {
            background: #1a0533;
            color: #fff;
        }

        .input-wrapper input::placeholder {
            color: #4B5563;
        }

        .input-wrapper input:focus,
        .input-wrapper select:focus {
            border-color: #EC4899;
            background: rgba(236, 72, 153, 0.06);
            box-shadow: 0 0 0 4px rgba(236, 72, 153, 0.12);
        }

        .input-wrapper:focus-within i {
            color: #EC4899;
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
            color: #EC4899;
        }

        /* Two columns */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        /* Submit Button */
        .btn-register {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #EC4899 0%, #DB2777 50%, #8B5CF6 100%);
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
            box-shadow: 0 8px 32px rgba(236, 72, 153, 0.35);
            margin-top: 8px;
        }

        .btn-register:hover {
            background-position: 100% 50%;
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(236, 72, 153, 0.5);
        }

        .btn-register:active {
            transform: translateY(0);
        }

        .btn-register::after {
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

        .btn-register:hover::after {
            transform: translateX(100%);
        }

        /* Footer Link */
        .form-footer {
            text-align: center;
            margin-top: 28px;
            font-size: 14px;
            color: #9CA3AF;
        }

        .form-footer a {
            color: #F472B6;
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
            background: linear-gradient(135deg, #EC4899, #8B5CF6);
            transition: width 0.3s ease;
        }

        .form-footer a:hover {
            color: #FBCFE8;
        }

        .form-footer a:hover::after {
            width: 100%;
        }

        .form-divider {
            display: flex;
            align-items: center;
            margin: 24px 0;
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

            .register-left {
                padding: 40px 24px;
                min-height: auto;
            }

            .brand-content h1 {
                font-size: 32px;
            }

            .brand-steps {
                display: none;
            }

            .register-right {
                padding: 40px 24px;
                min-height: auto;
            }
        }

        @media (max-width: 480px) {
            .register-left {
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

            .register-right {
                padding: 32px 20px;
            }

            .form-header h2 {
                font-size: 26px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Left Panel: Branding -->
    <div class="register-left">
        <div class="brand-content">
            <div class="brand-icon">
                <i class="bi bi-person-plus-fill"></i>
            </div>
            <h1>Rejoignez-nous</h1>
            <p>Créez votre compte et accédez à tous les outils RH dont vous avez besoin</p>
            <ul class="brand-steps">
                <li>
                    <span class="step-num">1</span>
                    <span>Créez votre compte en quelques clics</span>
                </li>
                <li>
                    <span class="step-num">2</span>
                    <span>Complétez votre profil employé</span>
                </li>
                <li>
                    <span class="step-num">3</span>
                    <span>Accédez à votre tableau de bord</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Right Panel: Register Form -->
    <div class="register-right">
        <div class="register-form-wrapper">
            <div class="form-header">
                <h2>Créer un compte ✨</h2>
                <p>Remplissez les informations ci-dessous pour commencer</p>
            </div>

            <?php if ($flash): ?>
            <div class="register-alert register-alert-<?= $flash['type'] === 'success' ? 'success' : 'error' ?>" id="flashAlert">
                <i class="bi bi-<?= $flash['type'] === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill' ?>"></i>
                <span><?= htmlspecialchars($flash['message']) ?></span>
                <button class="btn-close-alert" onclick="document.getElementById('flashAlert').style.display='none'">
                    <i class="bi bi-x"></i>
                </button>
            </div>
            <?php endif; ?>

            <form method="POST" action="/HRFlowSn/index.php?route=auth/register" id="registerForm">
                <div class="form-group">
                    <label for="email">Adresse email</label>
                    <div class="input-wrapper">
                        <i class="bi bi-envelope"></i>
                        <input type="email" id="email" name="email" placeholder="nom@entreprise.com" required autocomplete="email">
                    </div>
                </div>

                <div class="form-group">
                    <label for="role_id">Rôle</label>
                    <div class="input-wrapper">
                        <i class="bi bi-person-badge"></i>
                        <select id="role_id" name="role_id">
                            <option value="4">Employé</option>
                            <option value="3">Manager</option>
                            <option value="2">RH</option>
                            <option value="1">Administrateur</option>
                        </select>
                    </div>
                </div>

                <div class="form-col">
                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <div class="input-wrapper">
                            <i class="bi bi-lock"></i>
                            <input type="password" id="password" name="password" placeholder="••••••" required minlength="6" autocomplete="new-password">
                            <button type="button" class="password-toggle" data-target="password" aria-label="Afficher le mot de passe">
                                <i class="bi MOT DE PASSE"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirmer</label>
                        <div class="input-wrapper">
                            <i class="bi bi-lock-fill"></i>
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="••••••" required minlength="6" autocomplete="new-password">
                            <button type="button" class="password-toggle" data-target="confirm_password" aria-label="Afficher le mot de passe">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-register" id="btnRegister">
                    Créer mon compte
                </button>
            </form>

            <div class="form-divider">
                <span>ou</span>
            </div>

            <div class="form-footer">
                <p>Déjà un compte ? <a href="/HRFlowSn/index.php?route=auth/login">Se connecter</a></p>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        document.querySelectorAll('.password-toggle').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                this.querySelector('i').classList.toggle('bi-eye');
                this.querySelector('i').classList.toggle('bi-eye-slash');
            });
        });

        // Form submit animation
        document.getElementById('registerForm').addEventListener('submit', function () {
            const btn = document.getElementById('btnRegister');
            btn.innerHTML = '<i class="bi bi-arrow-repeat" style="animation: spin 1s linear infinite; display: inline-block;"></i> Création en cours...';
            btn.style.pointerEvents = 'none';
        });

        // Add spin animation
        const style = document.createElement('style');
        style.textContent = '@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }';
        document.head.appendChild(style);
    </script>
</body>
</html>
