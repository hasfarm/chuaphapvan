<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'chuaphapvan QC')</title>
    <meta name="description" content="@yield('description', 'Ứng dụng quản lý chuaphapvan')">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('img/icon/leaf.png') }}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-green: #10b981;
            --dark-green: #059669;
            --light-green: #d1fae5;
            --primary-orange: #f97316;
            --dark-orange: #ea580c;
            --light-orange: #fed7aa;
            --dark: #1f2937;
            --dark-light: #374151;
            --gray: #6b7280;
            --light-gray: #e5e7eb;
            --white: #ffffff;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.15);
            --control-height: 44px;
            --control-padding-x: 0.75rem;
        }

        html,
        body {
            height: 100%;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9fafb;
            color: var(--dark);
        }

        /* ============== Typography ============== */
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-weight: 600;
            color: var(--dark);
        }

        p {
            line-height: 1.6;
            color: var(--gray);
        }

        a {
            color: var(--primary-green);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        a:hover {
            color: var(--dark-green);
        }

        /* ============== Buttons ============== */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            border: none;
            color: var(--white);
            font-weight: 600;
            padding: 0.75rem 2rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: var(--shadow);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--dark-green), var(--primary-green));
            color: var(--white);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-secondary {
            background-color: var(--white);
            border: 2px solid var(--light-gray);
            color: var(--dark);
            font-weight: 600;
            padding: 0.75rem 2rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            border-color: var(--primary-green);
            color: var(--primary-green);
            background-color: var(--light-green);
        }

        /* ============== Forms ============== */
        .form-control,
        .form-select {
            border: 2px solid var(--light-gray);
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: var(--white);
        }

        input[type="text"],
        input[type="email"],
        input[type="number"],
        input[type="date"],
        input[type="password"],
        input[type="search"],
        .form-control,
        .form-select,
        select:not([multiple]):not([size]) {
            height: var(--control-height) !important;
            min-height: var(--control-height) !important;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
            padding-left: var(--control-padding-x) !important;
            padding-right: var(--control-padding-x) !important;
            line-height: calc(var(--control-height) - 4px) !important;
            box-sizing: border-box;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 0.2rem rgba(16, 185, 129, 0.25);
            background-color: var(--white);
        }

        .form-control::placeholder {
            color: var(--light-gray);
        }

        .form-label {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        /* ============== Alerts ============== */
        .alert {
            border: none;
            border-left: 4px solid;
            border-radius: 0.5rem;
            animation: slideIn 0.3s ease;
        }

        .alert-success {
            background-color: var(--light-green);
            border-left-color: var(--primary-green);
            color: var(--dark-green);
        }

        .alert-danger {
            background-color: #fee2e2;
            border-left-color: #ef4444;
            color: #991b1b;
        }

        .alert-warning {
            background-color: var(--light-orange);
            border-left-color: var(--primary-orange);
            color: var(--dark-orange);
        }

        .alert-info {
            background-color: #dbeafe;
            border-left-color: #3b82f6;
            color: #1e40af;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ============== Utilities ============== */
        .text-green {
            color: var(--primary-green);
        }

        .text-orange {
            color: var(--primary-orange);
        }

        .bg-light-green {
            background-color: var(--light-green);
        }

        .bg-light-orange {
            background-color: var(--light-orange);
        }

        /* ============== Responsive ============== */
        @media (max-width: 768px) {

            .btn-primary,
            .btn-secondary {
                padding: 0.6rem 1.5rem;
                font-size: 0.95rem;
            }

            h1 {
                font-size: 1.75rem;
            }

            h2 {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 576px) {

            html,
            body {
                font-size: 14px;
            }

            h1 {
                font-size: 1.5rem;
            }

            h2 {
                font-size: 1.25rem;
            }

            .form-control,
            .form-select {
                padding: 0.6rem 0.75rem;
                font-size: 0.95rem;
            }

            .btn-primary,
            .btn-secondary {
                width: 100%;
                padding: 0.7rem 1rem;
            }
        }
    </style>

    @yield('styles')
</head>

<body>
    <main>
        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script>
        // Ẩn alert sau 5 giây
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>

    @yield('scripts')
</body>

</html>
