<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="FASILITA - Sistem Pelaporan Fasilitas untuk kampus">
    <title>FASILITA - Sistem Pelaporan Fasilitas</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('assets/images/fasilita-icon.png') }}" />

    <style>
        /**
         * FASILITA Landing Page Styles with Enhanced Animations
         * Added comprehensive fade in/out effects and smooth transitions
         */

        /* Global Variables */
        :root {
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --text-dark: #1f2937;
            --text-gray: #6b7280;
            --text-light: #9ca3af;
            --bg-gray: #f9fafb;
            --bg-dark: #111827;
            --black: #000000;

            /* Animation Variables */
            --animation-duration: 0.8s;
            --animation-delay: 0.2s;
            --animation-easing: cubic-bezier(0.25, 0.46, 0.45, 0.94);

            /* Spacing Variables */
            --section-spacing-y: 120px;
            --section-spacing-y-mobile: 80px;
            --content-spacing: 2.5rem;
            --card-spacing: 1.75rem;
            --text-spacing: 1.25rem;
            --element-spacing: 1rem;
        }

        /* Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
            overflow-x: hidden;
        }

        /* ========================================
           ANIMATION CLASSES - FADE IN/OUT EFFECTS
           ======================================== */

        /* Base Fade Animation Classes */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: all var(--animation-duration) var(--animation-easing);
        }

        .fade-in.animate {
            opacity: 1;
            transform: translateY(0);
        }

        .fade-in-left {
            opacity: 0;
            transform: translateX(-50px);
            transition: all var(--animation-duration) var(--animation-easing);
        }

        .fade-in-left.animate {
            opacity: 1;
            transform: translateX(0);
        }

        .fade-in-right {
            opacity: 0;
            transform: translateX(50px);
            transition: all var(--animation-duration) var(--animation-easing);
        }

        .fade-in-right.animate {
            opacity: 1;
            transform: translateX(0);
        }

        .fade-in-up {
            opacity: 0;
            transform: translateY(50px);
            transition: all var(--animation-duration) var(--animation-easing);
        }

        .fade-in-up.animate {
            opacity: 1;
            transform: translateY(0);
        }

        .fade-in-down {
            opacity: 0;
            transform: translateY(-50px);
            transition: all var(--animation-duration) var(--animation-easing);
        }

        .fade-in-down.animate {
            opacity: 1;
            transform: translateY(0);
        }

        /* Scale Animations */
        .scale-in {
            opacity: 0;
            transform: scale(0.8);
            transition: all var(--animation-duration) var(--animation-easing);
        }

        .scale-in.animate {
            opacity: 1;
            transform: scale(1);
        }

        .scale-in-bounce {
            opacity: 0;
            transform: scale(0.3);
            transition: all var(--animation-duration) cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .scale-in-bounce.animate {
            opacity: 1;
            transform: scale(1);
        }

        /* Rotation Animations */
        .rotate-in {
            opacity: 0;
            transform: rotate(-180deg) scale(0.8);
            transition: all var(--animation-duration) var(--animation-easing);
        }

        .rotate-in.animate {
            opacity: 1;
            transform: rotate(0deg) scale(1);
        }

        /* Stagger Animation Delays */
        .stagger-1 { transition-delay: 0.1s; }
        .stagger-2 { transition-delay: 0.2s; }
        .stagger-3 { transition-delay: 0.3s; }
        .stagger-4 { transition-delay: 0.4s; }
        .stagger-5 { transition-delay: 0.5s; }
        .stagger-6 { transition-delay: 0.6s; }
        .stagger-7 { transition-delay: 0.7s; }
        .stagger-8 { transition-delay: 0.8s; }

        /* Hover Animations */
        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .hover-scale {
            transition: transform 0.3s ease;
        }

        .hover-scale:hover {
            transform: scale(1.05);
        }

        /* Pulse Animation */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        /* Floating Animation */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .float-animation {
            animation: float 3s ease-in-out infinite;
        }

        /* Typing Animation */
        @keyframes typing {
            from { width: 0; }
            to { width: 100%; }
        }

        @keyframes blink {
            50% { border-color: transparent; }
        }

        .typing-animation {
            overflow: hidden;
            border-right: 2px solid var(--primary-color);
            white-space: nowrap;
            animation: typing 3s steps(40, end), blink 0.75s step-end infinite;
        }

        /* Slide Animations */
        @keyframes slideInLeft {
            0% {
                opacity: 0;
                transform: translateX(-100px);
            }
            100% {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            0% {
                opacity: 0;
                transform: translateX(100px);
            }
            100% {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .slide-in-left {
            animation: slideInLeft 0.8s ease-out;
        }

        .slide-in-right {
            animation: slideInRight 0.8s ease-out;
        }

        /* Bounce Animation */
        @keyframes bounceIn {
            0% {
                opacity: 0;
                transform: scale(0.3);
            }
            50% {
                opacity: 1;
                transform: scale(1.05);
            }
            70% {
                transform: scale(0.9);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        .bounce-in {
            animation: bounceIn 1s ease-out;
        }

        /* Loading Animation */
        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        /* Shimmer Effect */
        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .shimmer-effect {
            position: relative;
            overflow: hidden;
        }

        .shimmer-effect::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            animation: shimmer 2s infinite;
        }

        /* Enhanced Media Container Styles */
        .media-container {
            position: relative;
            width: 100%;
            overflow: hidden;
            border-radius: 12px;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .media-container:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .media-container img,
        .media-container video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
            display: block;
        }

        .media-container:hover img,
        .media-container:hover video {
            transform: scale(1.02);
        }

        /* Responsive Image Classes */
        .responsive-image {
            max-width: 100%;
            height: auto;
            display: block;
            border-radius: inherit;
        }

        .aspect-ratio-16-9 {
            aspect-ratio: 16 / 9;
            min-height: 300px;
        }

        .aspect-ratio-4-3 {
            aspect-ratio: 4 / 3;
            min-height: 250px;
        }

        .aspect-ratio-1-1 {
            aspect-ratio: 1 / 1;
            min-height: 200px;
        }

        .aspect-ratio-3-2 {
            aspect-ratio: 3 / 2;
            min-height: 200px;
        }

        /* Enhanced Video Player Styles */
        .video-container {
            position: relative;
            width: 100%;
            background: #000;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .video-player {
            width: 100%;
            height: 100%;
            border: none;
            outline: none;
            border-radius: inherit;
        }

        .video-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(0,0,0,0.4), rgba(79, 70, 229, 0.3));
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 2;
            border-radius: inherit;
        }

        .video-overlay:hover {
            background: linear-gradient(45deg, rgba(0,0,0,0.6), rgba(79, 70, 229, 0.4));
        }

        .video-overlay.hidden {
            opacity: 0;
            pointer-events: none;
        }

        /* Enhanced Play Button */
        .play-button {
            position: relative;
            width: 80px;
            height: 80px;
            background: rgba(255,255,255,0.95);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 2rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            backdrop-filter: blur(10px);
        }

        .play-button:hover {
            background: white;
            transform: scale(1.1);
            box-shadow: 0 12px 35px rgba(0,0,0,0.3);
        }

        .play-button i {
            margin-left: 4px;
        }

        /* Navigation Styles */
        .navbar {
            padding: 1.25rem 0;
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.95) !important;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            background-color: rgba(255, 255, 255, 0.98) !important;
        }

        .navbar-nav-centered {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .navbar-nav-centered .nav-link {
            color: var(--text-gray);
            font-weight: 500;
            text-decoration: none;
            transition: color 0.3s ease;
            padding: 0.5rem 1.25rem;
        }

        .navbar-nav-centered .nav-link:hover {
            color: var(--text-dark);
        }

        /* Responsive navigation */
        @media (max-width: 991.98px) {
            .navbar-nav-centered {
                position: static;
                transform: none;
                flex-direction: column;
                width: 100%;
                margin: 1rem 0;
            }

            .navbar-collapse {
                text-align: center;
            }

            .auth-buttons {
                justify-content: center !important;
                margin-top: 1rem;
            }
        }

        /* Button Styles */
        .btn-primary-custom {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 50px;
            padding: 12px 28px;
            font-weight: 500;
            color: white !important;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-primary-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-primary-custom:hover::before {
            left: 100%;
        }

        .btn-primary-custom:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            color: white !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
            text-decoration: none;
        }

        .btn-outline-custom {
            border: 1px solid #d1d5db;
            color: var(--text-gray);
            border-radius: 50px;
            padding: 12px 28px;
            font-weight: 500;
            background: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-outline-custom:hover {
            background-color: #f9fafb;
            border-color: #9ca3af;
            color: var(--text-dark);
            text-decoration: none;
        }

        /* Logo Styles */
        .logo-image {
            height: 50px;
            width: auto;
            max-width: 200px;
            object-fit: contain;
            transition: transform 0.3s ease;
        }

        .logo-image:hover {
            transform: scale(1.05);
        }

        .logo-image-large {
            height: 80px;
            width: auto;
            max-width: 250px;
            object-fit: contain;
            opacity: 0.9;
            transition: opacity 0.3s ease;
        }

        .logo-image-large:hover {
            opacity: 1;
        }

        .navbar-brand {
            padding: 0;
        }

        /* Section Styles */
        .hero-section {
            background: linear-gradient(135deg, #f9fafb 0%, #ffffff 100%);
            padding: 80px 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 30% 20%, rgba(79, 70, 229, 0.05) 0%, transparent 50%);
            pointer-events: none;
        }

        .hero-title {
            font-size: 4rem;
            font-weight: 800;
            color: var(--primary-color);
            line-height: 1.1;
            margin-bottom: 2.5rem;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            color: var(--text-gray);
            margin-bottom: 2.5rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .badge-custom {
            background-color: var(--primary-color);
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .badge-custom:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
        }

        .section-light, .section-dark, .section-gray {
            padding: var(--section-spacing-y) 0;
        }

        .section-dark {
            background-color: var(--black);
            color: white;
            padding: var(--section-spacing-y) 0;
        }

        .section-gray {
            background-color: var(--bg-gray);
            padding: var(--section-spacing-y) 0;
        }

        /* Endorsement Section */
        .endorsement-section {
            padding: var(--section-spacing-y) 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border: 1px solid #f1f5f9;
        }

        .logo-container:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }

        /* Enhanced Card Styles */
        .feature-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: var(--card-spacing);
            height: 100%;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--primary-color);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .feature-icon {
            width: 48px;
            height: 48px;
            background-color: var(--primary-color);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .feature-icon:hover {
            transform: rotate(5deg) scale(1.1);
        }

        .feature-icon i {
            color: white;
            font-size: 1.5rem;
        }

        .feature-icon.inactive {
            background-color: #e5e7eb;
        }

        .feature-icon.inactive i {
            color: #9ca3af;
        }

        /* Role Card Styles */
        .role-card {
            background: white;
            border: 2px solid #f1f5f9;
            border-radius: 20px;
            padding: var(--card-spacing);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .role-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(79, 70, 229, 0.15);
            border-color: var(--primary-color);
        }

        .role-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), #8b5cf6);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .role-card:hover::before {
            opacity: 1;
        }

        .role-card-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .role-card-content {
            flex: 1;
        }

        .role-card-content p {
            margin-bottom: 2rem;
        }

        .role-icon {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: white;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .role-icon:hover {
            transform: scale(1.1) rotate(5deg);
        }

        .role-icon::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: inherit;
            opacity: 0.1;
            border-radius: inherit;
        }

        .role-icon.students {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        }

        .role-icon.technician {
            background: linear-gradient(135deg, #10b981, #047857);
        }

        .role-icon.admin {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        }

        .role-features {
            margin-top: 1.5rem;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            font-size: 0.95rem;
            color: var(--text-gray);
            transition: all 0.3s ease;
        }

        .feature-item:hover {
            color: var(--primary-color);
            transform: translateX(5px);
        }

        .feature-item:last-child {
            margin-bottom: 0;
        }

        /* Tool Card Styles */
        .tool-card {
            background: #1f2937;
            border: 1px solid #374151;
            border-radius: 12px;
            padding: var(--card-spacing);
            height: 100%;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            color: white;
        }

        .tool-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary-color);
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.2);
        }

        .tool-card-header {
            text-align: center;
            margin-bottom: 1rem;
            flex-shrink: 0;
        }

        .tool-card-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            text-align: left;
        }

        .tool-card-text {
            flex: 1;
            margin-bottom: 1rem;
        }

        .tool-card-image-container {
            margin-top: auto;
            height: 180px;
            overflow: hidden;
            border-radius: 8px;
            background: #374151;
        }

        .tool-card h4,
        .tool-card h5,
        .tool-card h6,
        .tool-card p,
        .tool-card span,
        .tool-card div {
            color: white !important;
        }

        .tool-card .text-muted {
            color: #d1d5db !important;
        }

        .tool-icon {
            width: 64px;
            height: 64px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
            transition: all 0.3s ease;
        }

        .tool-icon-skydash {
            width: 64px;
            height: 64px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
            transition: all 0.3s ease;
        }

        /* Buat gambar mengisi penuh dan menjaga aspek ratio */
        .tool-icon img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .tool-icon-skydash img {
            max-width: 500%;
            max-height: 50%;
            object-fit: contain;
        }

        .tool-icon:hover {
            transform: scale(1.1) rotate(5deg);
        }

        .tool-icon.laravel { background-color: #ef4444; }
        .tool-icon.bootstrap { background-color: #8b5cf6; }
        .tool-icon.skydash { background-color: #3b82f6; }
        .tool-icon.jquery { background-color: #06b6d4; }
        .tool-icon.figma { background: linear-gradient(45deg, #8b5cf6, #ec4899); }
        .tool-icon.mysql { background-color: #f97316; }

        /* Team Card Styles */
        .team-card {
            background: white;
            border: 2px solid #e0e7ff;
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .team-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            border-color: var(--primary-color);
        }

        .team-image {
            aspect-ratio: 1;
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            overflow: hidden;
            position: relative;
        }

        .team-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .team-image:hover img {
            transform: scale(1.05);
        }

        .team-info {
            background-color: #000;
            color: white;
            padding: 1.5rem;
        }

        .team-info h5,
        .team-info h6,
        .team-info p,
        .team-info small,
        .team-info span {
            color: white !important;
        }

        .team-info .text-muted {
            color: #d1d5db !important;
        }

        .team-section-heading {
            color: var(--primary-color) !important;
        }

        /* Avatar Styles */
        .avatar-container {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            background: linear-gradient(135deg, #e5e7eb 0%, #f3f4f6 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #f1f5f9;
        }

        .avatar-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Dashboard Preview Styles */
        .dashboard-preview {
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            margin-top: 3rem;
            margin-bottom: 1rem;
        }

        .dashboard-preview img {
            width: 100%;
            height: auto;
            display: block;
            border-radius: inherit;
        }

        /* Image Loading States */
        .image-loading {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
            border-radius: inherit;
        }

        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* Error State Styles */
        .image-error {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #dc2626;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 2rem;
            border-radius: inherit;
            border: 2px dashed #fca5a5;
        }

        .image-error i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            opacity: 0.7;
        }

        /* Footer */
        .footer-dark {
            background-color: #000;
            color: white;
            padding: 80px 0 40px;
        }

        .footer-link {
            color: #9ca3af;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-link:hover {
            color: white;
        }

        .social-icon {
            color: #9ca3af;
            font-size: 1.25rem;
            transition: color 0.3s ease;
        }

        .social-icon:hover {
            color: white;
        }

        /* Enhanced Logo Container Styles */
        .logo-container-enhanced {
            background: white;
            border-radius: 24px;
            padding: 3.5rem 2.5rem;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
            transition: all 0.4s ease;
            border: 2px solid #f8fafc;
            position: relative;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .logo-container-enhanced::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), #8b5cf6);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .logo-container-enhanced:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 50px rgba(79, 70, 229, 0.15);
            border-color: var(--primary-color);
        }

        .logo-container-enhanced:hover::before {
            opacity: 1;
        }

        .logo-wrapper {
            position: relative;
            min-height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
        }

        .official-logo {
            max-height: 100px;
            max-width: 100%;
            width: auto;
            height: auto;
            object-fit: contain;
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.1));
            transition: all 0.3s ease;
        }

        .logo-container-enhanced:hover .official-logo {
            transform: scale(1.05);
            filter: drop-shadow(0 6px 12px rgba(0,0,0,0.15));
        }

        .logo-fallback {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 2rem;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-radius: 16px;
            border: 2px dashed #cbd5e1;
            min-height: 120px;
        }

        .fallback-icon {
            width: 60px;
            height: 60px;
            background: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .fallback-icon i {
            color: white;
            font-size: 1.5rem;
        }

        .logo-info h5 {
            color: var(--text-dark);
            font-size: 1.1rem;
        }

        .logo-info p {
            color: var(--text-gray);
            font-size: 0.9rem;
        }

        /* Partnership Benefits Styles */
        .benefit-card {
            padding: 2.5rem 1.5rem;
            background: #f8fafc;
            border-radius: 16px;
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
            margin-bottom: 1rem;
        }

        .benefit-card:hover {
            background: white;
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .benefit-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary-color), #8b5cf6);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: white;
            font-size: 1.25rem;
            transition: all 0.3s ease;
        }

        .benefit-icon:hover {
            transform: scale(1.1) rotate(5deg);
        }

        /* Responsive Media Queries */
        @media (max-width: 1200px) {
            .hero-title {
                font-size: 3.5rem;
            }

            .dashboard-preview {
                margin-top: 2rem;
            }
        }

        @media (max-width: 992px) {
            :root {
                --section-spacing-y: 100px;
                --content-spacing: 2rem;
                --card-spacing: 1.5rem;
            }

            .row.g-5 {
                --bs-gutter-y: 2.5rem;
            }

            .hero-title {
                font-size: 3rem;
            }

            .hero-subtitle {
                font-size: 1.1rem;
            }

            .logo-image {
                height: 35px;
            }

            .tool-card-image-container {
                height: 150px;
            }

            .aspect-ratio-16-9 {
                min-height: 250px;
            }

            .aspect-ratio-4-3 {
                min-height: 200px;
            }

            .logo-container-enhanced {
                padding: 2.5rem 1.5rem;
                margin-bottom: 2rem;
            }

            .official-logo {
                max-height: 80px;
            }

            .logo-wrapper {
                min-height: 100px;
            }

            .role-card {
                padding: 2rem;
                margin-bottom: 1rem;
            }

            .role-icon {
                width: 70px;
                height: 70px;
                font-size: 1.75rem;
            }
        }

        @media (max-width: 768px) {
            :root {
                --section-spacing-y: var(--section-spacing-y-mobile);
                --content-spacing: 1.75rem;
                --card-spacing: 1.25rem;
                --text-spacing: 1rem;
            }

            .row.g-4 {
                --bs-gutter-y: 1.5rem;
            }

            .row.g-5 {
                --bs-gutter-y: 2rem;
            }

            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1rem;
            }

            .section-light, .section-dark, .section-gray {
                padding: 60px 0;
            }

            .endorsement-section {
                padding: 40px 0;
            }

            .logo-image {
                height: 30px;
            }

            .play-button {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }

            .aspect-ratio-16-9 {
                min-height: 200px;
            }

            .aspect-ratio-4-3 {
                min-height: 150px;
            }

            .logo-container {
                padding: 1.5rem;
            }

            .logo-container-enhanced {
                padding: 2rem 1rem;
            }

            .official-logo {
                max-height: 70px;
            }

            .logo-wrapper {
                min-height: 90px;
            }

            .benefit-card {
                padding: 1.5rem 1rem;
                margin-bottom: 1rem;
            }

            .role-card {
                padding: 1.5rem;
            }

            .role-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .hero-title {
                font-size: 2rem;
            }

            .badge-custom {
                font-size: 0.75rem;
            }

            .section-light, .section-dark, .section-gray {
                padding: 40px 0;
            }

            .endorsement-section {
                padding: 30px 0;
            }

            .logo-image {
                height: 28px;
            }

            .tool-card-image-container {
                height: 120px;
            }

            .play-button {
                width: 50px;
                height: 50px;
                font-size: 1.25rem;
            }

            .aspect-ratio-16-9 {
                min-height: 180px;
            }

            .logo-container {
                padding: 1rem;
            }

            .logo-container-enhanced {
                padding: 1.5rem 1rem;
            }

            .official-logo {
                max-height: 60px;
            }

            .logo-wrapper {
                min-height: 80px;
            }

            .benefit-card {
                padding: 1.5rem 1rem;
                margin-bottom: 1rem;
            }
        }

        /* General Spacing Adjustments */
        p {
            margin-bottom: var(--text-spacing);
        }

        h1, h2, h3, h4, h5, h6 {
            margin-bottom: var(--element-spacing);
        }

        /* Section Header Spacing */
        .section-header {
            margin-bottom: 3.5rem;
        }

        /* Margin Bottom Utilities */
        .mb-5 {
            margin-bottom: 3rem !important;
        }

        .mb-4 {
            margin-bottom: 2rem !important;
        }

        .mb-3 {
            margin-bottom: 1.5rem !important;
        }

        /* Row Gutter Adjustments */
        .row.g-4 {
            --bs-gutter-y: 2rem;
        }

        .row.g-5 {
            --bs-gutter-y: 3rem;
        }

        /* Responsive Spacing Adjustments */
        @media (max-width: 992px) {
            .row.g-5 {
                --bs-gutter-y: 2.5rem;
            }
        }

        @media (max-width: 768px) {
            .row.g-4 {
                --bs-gutter-y: 1.5rem;
            }

            .row.g-5 {
                --bs-gutter-y: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- HEADER NAVIGATION -->
    <header class="navbar navbar-expand-lg navbar-light sticky-top fade-in-down">
        <div class="container position-relative">
            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center fade-in-left stagger-1" href="{{ url('/') }}">
                <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/LOGO%20%28Revisi%20Warna%29-eWjC9ZoeAArOze6ELrzncIwo0ebVJI.png"
                     alt="FASILITA Logo"
                     class="logo-image responsive-image hover-scale"
                     loading="lazy"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <!-- Fallback for logo -->
                <div class="d-none bg-primary text-white rounded d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; min-width: 32px;">
                    <strong style="font-size: 14px;">F</strong>
                </div>
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler fade-in-right stagger-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navigation Links -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Centered Navigation Menu -->
                <nav class="navbar-nav-centered d-none d-lg-flex fade-in stagger-3">
                </nav>

                <!-- Mobile Navigation Menu -->
                <nav class="navbar-nav d-lg-none w-100">
                    <div class="d-flex flex-column align-items-center">
                    </div>
                </nav>

                <!-- Auth Buttons -->
                <div class="d-flex gap-2 auth-buttons ms-auto fade-in-right stagger-4">
                    <a href="{{ url('/login') }}" class="btn btn-outline-custom hover-lift">Login</a>
                    <a href="{{ url('/register') }}" class="btn btn-primary-custom shimmer-effect">Sign in Now</a>
                </div>
            </div>
        </div>
    </header>

    <!-- HERO SECTION -->
    <section class="hero-section text-center" style="padding-top: 100px; padding-bottom: 120px;">
        <div class="container">
            <!-- Badge -->
            <div class="badge-custom fade-in-up stagger-1 pulse-animation">
                <i class="fas fa-calendar-alt me-2"></i>Cepat, transparan, dan efisien
            </div>

            <!-- Hero Title -->
            <h1 class="hero-title fade-in-up stagger-2">
                Sistem<br>
                <span class="typing-animation">Pelaporan Fasilitas</span>
            </h1>

            <!-- Hero Subtitle -->
            <p class="hero-subtitle fade-in-up stagger-3">
                Bantu kami menjaga fasilitas kampus tetap optimal dengan sistem pelaporan
                kerusakan yang mudah digunakan dan terintegrasi.
            </p>

            <!-- CTA Button -->
            <a href="{{ route('register') }}" class="btn btn-primary-custom btn-lg mb-5 fade-in-up stagger-4 hover-lift shimmer-effect">
                <i class="fas fa-rocket me-2"></i>Get Started
            </a>

            <!-- Dashboard Preview -->
            <div class="dashboard-preview mx-auto aspect-ratio-20-9 fade-in-up stagger-5 float-animation" style="max-width: 1000px;">
                <div class="media-container hover-lift">
                    <img src="{{ asset('assets/images/dashboard-preview.jpg') }}"
                         alt="FASILITA Dashboard Preview - Sistem Pelaporan Fasilitas Kampus"
                         class="responsive-image"
                         loading="lazy"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                    <!-- Fallback for dashboard preview -->
                    <div class="image-error d-none">
                        <i class="fas fa-desktop"></i>
                        <p class="mb-0 small">Dashboard Preview</p>
                        <small>Image not found</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SISTEM PELAPORAN SECTION -->
    <section class="section-light" style="padding: 100px 0;">
    <div class="container">
        <div class="text-center mb-5 section-header">
            <h2 class="fw-bold text-primary mb-4 fade-in-up stagger-2" style="font-size: 3rem; line-height: 1.2;">
                Sistem Pelaporan<br>
                untuk Semua Pengguna
            </h2>
            <p class="text-muted fs-5 mx-auto fade-in-up stagger-3" style="max-width: 700px;">
                FASILITA dirancang untuk melayani berbagai peran dalam ekosistem kampus,
                dari mahasiswa hingga teknisi, dengan antarmuka yang disesuaikan untuk setiap kebutuhan.
            </p>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-lg-3 col-md-6">
                <div class="role-card h-100 fade-in-left stagger-1 hover-lift">
                    <div class="role-card-header">
                        <div class="role-icon students scale-in-bounce stagger-2">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h4 class="fw-bold text-primary mb-3 fade-in-up stagger-3">Mahasiswa, Dosen, Tendik</h4>
                    </div>
                    <div class="role-card-content">
                        <p class="text-muted mb-4 fade-in-up stagger-4">
                            Pengguna dapat dengan mudah melaporkan kerusakan fasilitas, memantau status, dan memberikan voting untuk mendukung percepatan penanganan.
                        </p>
                        <div class="role-features">
                            <div class="feature-item fade-in-up stagger-5">
                                <i class="fas fa-plus-circle text-primary me-2"></i>
                                <span>Buat laporan kerusakan</span>
                            </div>
                            <div class="feature-item fade-in-up stagger-6">
                                <i class="fas fa-eye text-primary me-2"></i>
                                <span>Pantau status laporan</span>
                            </div>
                            <div class="feature-item fade-in-up stagger-7">
                                <i class="fas fa-thumbs-up text-primary me-2"></i>
                                <span>Vote laporan prioritas</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="role-card h-100 fade-in stagger-2 hover-lift">
                    <div class="role-card-header">
                        <div class="role-icon technician scale-in-bounce stagger-3">
                            <i class="fas fa-tools"></i>
                        </div>
                        <h4 class="fw-bold text-primary mb-3 fade-in-up stagger-4">Teknisi</h4>
                    </div>
                    <div class="role-card-content">
                        <p class="text-muted mb-4 fade-in-up stagger-5">
                            Teknisi bertugas menangani laporan, melakukan pengecekan, dan memperbarui status progres perbaikan yang dilakukan.
                        </p>
                        <div class="role-features">
                            <div class="feature-item fade-in-up stagger-6">
                                <i class="fas fa-clipboard-check text-primary me-2"></i>
                                <span>Terima & verifikasi laporan</span>
                            </div>
                            <div class="feature-item fade-in-up stagger-7">
                                <i class="fas fa-wrench text-primary me-2"></i>
                                <span>Lakukan perbaikan</span>
                            </div>
                            <div class="feature-item fade-in-up stagger-8">
                                <i class="fas fa-sync-alt text-primary me-2"></i>
                                <span>Update status progres</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="role-card h-100 fade-in-right stagger-3 hover-lift">
                    <div class="role-card-header">
                        <div class="role-icon sarpras scale-in-bounce stagger-4" style="background-color: #ffc107;">
                            <i class="fas fa-building"></i>
                        </div>
                        <h4 class="fw-bold text-primary mb-3 fade-in-up stagger-5">Sarana Prasarana</h4>
                    </div>
                    <div class="role-card-content">
                        <p class="text-muted mb-4 fade-in-up stagger-6">
                            Bagian Sarpras mengelola alur laporan, menugaskan teknisi, dan memantau ketersediaan sumber daya untuk perbaikan.
                        </p>
                        <div class="role-features">
                            <div class="feature-item fade-in-up stagger-7">
                                <i class="fas fa-tasks text-primary me-2"></i>
                                <span>Manajemen Laporan</span>
                            </div>
                            <div class="feature-item fade-in-up stagger-8">
                                <i class="fas fa-user-check text-primary me-2"></i>
                                <span>Tugaskan Teknisi</span>
                            </div>
                             <div class="feature-item fade-in-up stagger-8">
                                <i class="fas fa-archive text-primary me-2"></i>
                                <span>Kelola Inventaris</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="role-card h-100 fade-in-right stagger-4 hover-lift">
                    <div class="role-card-header">
                        <div class="role-icon admin scale-in-bounce stagger-5">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <h4 class="fw-bold text-primary mb-3 fade-in-up stagger-6">Admin</h4>
                    </div>
                    <div class="role-card-content">
                        <p class="text-muted mb-4 fade-in-up stagger-7">
                           Admin memastikan seluruh proses berjalan lancar dengan memverifikasi laporan dan mengelola semua akun pengguna dalam sistem.
                        </p>
                        <div class="role-features">
                            <div class="feature-item fade-in-up stagger-8">
                                <i class="fas fa-check-double text-primary me-2"></i>
                                <span>Verifikasi laporan</span>
                            </div>
                            <div class="feature-item fade-in-up stagger-9">
                                <i class="fas fa-users-cog text-primary me-2"></i>
                                <span>Kelola akun pengguna</span>
                            </div>
                            <div class="feature-item fade-in-up stagger-10">
                                <i class="fas fa-chart-line text-primary me-2"></i>
                                <span>Monitor sistem</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row align-items-center fade-in-up stagger-4">
            <div class="col-lg-8">
                <div class="bg-light p-4 rounded-3 hover-lift">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary text-white rounded d-flex align-items-center justify-content-center me-3 pulse-animation" style="width: 48px; height: 48px;">
                            <i class="fas fa-info"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">Akses Sesuai Peran</h5>
                            <p class="text-muted mb-0">
                                Setiap pengguna mendapatkan dashboard dan fitur yang disesuaikan dengan peran mereka
                                untuk pengalaman yang optimal dan efisien.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-center">
                <a href="{{ url('/register') }}" class="btn btn-primary-custom btn-lg bounce-in shimmer-effect">
                    <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                </a>
            </div>
        </div>
    </div>
</section>

    <!-- ENDORSEMENT SECTION -->
    <section class="endorsement-section">
        <div class="container">
            <!-- Section Header -->
            <div class="text-center mb-5 section-header">
                <div class="badge-custom mb-3 fade-in-up stagger-1 hover-scale">
                    <i class="fas fa-shield-alt me-2"></i>Dukungan Resmi
                </div>
                <h2 class="fw-bold text-primary mb-3 fade-in-up stagger-2" style="font-size: 2.5rem;">
                    Didukung Resmi oleh<br>
                    Politeknik Negeri Malang
                </h2>
                <p class="text-muted fs-5 mx-auto fade-in-up stagger-3" style="max-width: 600px;">
                    FASILITA dikembangkan dengan dukungan penuh dari institusi pendidikan terkemuka
                    untuk memastikan kualitas dan keberlanjutan sistem.
                </p>
            </div>

            <!-- Logo Display Grid -->
            <div class="row justify-content-center align-items-center g-5">
                <!-- Polinema Logo -->
                <div class="col-lg-5 col-md-6">
                    <div class="logo-container-enhanced fade-in-left stagger-1 hover-lift">
                        <div class="logo-wrapper">
                            <img src="{{ asset('assets/images/logos/polinema-logo.png') }}"
                                 alt="Politeknik Negeri Malang - Official Partner"
                                 class="official-logo responsive-image hover-scale"
                                 loading="lazy"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                            <!-- Fallback for Polinema logo -->
                            <div class="logo-fallback d-none">
                                <div class="fallback-icon">
                                    <i class="fas fa-university"></i>
                                </div>
                                <div class="fallback-text">
                                    <h5 class="fw-bold mb-1">Politeknik Negeri Malang</h5>
                                    <p class="text-muted small mb-0">Official Institution Partner</p>
                                </div>
                            </div>
                        </div>
                        <div class="logo-info text-center mt-3">
                            <h5 class="fw-bold text-primary mb-1">Politeknik Negeri Malang</h5>
                            <p class="text-muted small mb-0">Institusi Pendidikan Utama</p>
                        </div>
                    </div>
                </div>

                <!-- JTI Logo -->
                <div class="col-lg-5 col-md-6">
                    <div class="logo-container-enhanced fade-in-right stagger-2 hover-lift">
                        <div class="logo-wrapper">
                            <img src="{{ asset('assets/images/logos/jti-logo.png') }}"
                                 alt="Jurusan Teknologi Informasi - Department Partner"
                                 class="official-logo responsive-image hover-scale"
                                 loading="lazy"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                            <!-- Fallback for JTI logo -->
                            <div class="logo-fallback d-none">
                                <div class="fallback-icon">
                                    <i class="fas fa-code"></i>
                                </div>
                                <div class="fallback-text">
                                    <h5 class="fw-bold mb-1">Jurusan Teknologi Informasi</h5>
                                    <p class="text-muted small mb-0">Department Partner</p>
                                </div>
                            </div>
                        </div>
                        <div class="logo-info text-center mt-3">
                            <h5 class="fw-bold text-primary mb-1">Jurusan Teknologi Informasi</h5>
                            <p class="text-muted small mb-0">Departemen Pengembang</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Partnership Benefits -->
            <div class="row g-4 mt-5" style="margin-top: 4rem !important;">
                <div class="col-md-4">
                    <div class="benefit-card text-center fade-in-up stagger-1 hover-lift">
                        <div class="benefit-icon">
                            <i class="fas fa-certificate"></i>
                        </div>
                        <h6 class="fw-bold mb-2">Kualitas Terjamin</h6>
                        <p class="text-muted small mb-0">Dikembangkan sesuai standar akademik dan industri</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="benefit-card text-center fade-in-up stagger-2 hover-lift">
                        <div class="benefit-icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h6 class="fw-bold mb-2">Dukungan Berkelanjutan</h6>
                        <p class="text-muted small mb-0">Maintenance dan pengembangan yang berkesinambungan</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="benefit-card text-center fade-in-up stagger-3 hover-lift">
                        <div class="benefit-icon">
                            <i class="fas fa-shield-check"></i>
                        </div>
                        <h6 class="fw-bold mb-2">Keamanan Data</h6>
                        <p class="text-muted small mb-0">Perlindungan data sesuai standar institusi pendidikan</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES SECTION 1 -->
    <section class="section-light">
        <div class="container">
            <!-- Section Header -->
            <div class="row align-items-center mb-5 section-header">
                <div class="col-md-1">
                    <div class="bg-light rounded scale-in stagger-1" style="width: 32px; height: 32px;"></div>
                </div>
                <div class="col-md-11">
                    <h2 class="fw-bold text-primary mb-2 fade-in-left stagger-2">
                        Sistem Pelaporan<br>
                        Fasilitas
                    </h2>
                    <p class="text-muted fade-in-left stagger-3">
                        Laporkan kerusakan, pantau progres, dan lihat riwayat penanganan
                        langsung dari dashboard Anda.
                    </p>
                </div>
            </div>

            <!-- Feature Cards -->
            <div class="row g-4 mb-5">
                <!-- Feature Card 1 -->
                <div class="col-md-3">
                    <div class="feature-card fade-in-up stagger-1 hover-lift">
                        <div class="feature-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-2">Laporan</h5>
                            <p class="text-muted small mb-0">Kirim laporan kerusakan dengan mudah kapan saja.</p>
                        </div>
                    </div>
                </div>

                <!-- Feature Card 2 -->
                <div class="col-md-3">
                    <div class="feature-card fade-in-up stagger-2 hover-lift">
                        <div class="feature-icon">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-2 text-muted">Buat Laporan</h5>
                            <p class="text-muted small mb-0">Membuat laporan dengan mudah dan cepat.</p>
                        </div>
                    </div>
                </div>

                <!-- Feature Card 3 -->
                <div class="col-md-3">
                    <div class="feature-card fade-in-up stagger-3 hover-lift">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-2 text-muted">Pantau Laporan</h5>
                            <p class="text-muted small mb-0">Pantau status laporan Anda kapan saja, di mana saja.</p>
                        </div>
                    </div>
                </div>

                <!-- Feature Card 4 -->
                <div class="col-md-3">
                    <div class="feature-card fade-in-up stagger-4 hover-lift">
                        <div class="feature-icon">
                            <i class="fas fa-database"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-2 text-muted">Riwayat & Data</h5>
                            <p class="text-muted small mb-0">Semua laporan terdokumentasi lengkap dan bisa diakses kapan saja.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dashboard Interface Image -->
            <div class="mb-5 fade-in-up stagger-5" style="margin-bottom: 4rem !important;">
                <div class="media-container aspect-ratio-19-9 hover-lift">
                    <img src="{{ asset('assets/images/dashboard-interface.jpg') }}"
                         alt="FASILITA Dashboard Interface - Antarmuka Sistem Pelaporan"
                         class="responsive-image"
                         loading="lazy"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                    <!-- Fallback for dashboard interface -->
                    <div class="image-error d-none">
                        <i class="fas fa-chart-line"></i>
                        <p class="mb-0">Dashboard Interface</p>
                        <small>Image not found</small>
                    </div>
                </div>
            </div>

            <!-- Testimonial -->
            <div class="bg-light p-4 rounded d-flex align-items-center justify-content-between flex-wrap fade-in-up stagger-6 hover-lift">
                <div class="d-flex align-items-center mb-3 mb-md-0">
                    <div class="bg-primary text-white rounded d-flex align-items-center justify-content-center me-3 pulse-animation" style="width: 32px; height: 32px;">
                        <strong style="font-size: 14px;">F</strong>
                    </div>
                    <small class="text-muted">Laporan Sekarang</small>
                </div>

                <div class="text-center flex-fill mx-md-4 mb-3 mb-md-0">
                    <p class="fst-italic mb-0">"Sangat membantu! Saya bisa tahu laporan saya diproses atau belum hanya dari dashboard."</p>
                </div>

                <div class="d-flex align-items-center">
                    <div class="avatar-container me-2">
                        <img src="{{ asset('assets/images/avatars/gabriel-avatar.jpg') }}"
                             alt="Gabriel Batavia - Mahasiswa Teknik Informatika"
                             loading="lazy"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                        <!-- Fallback for avatar -->
                        <div class="d-none w-100 h-100 d-flex align-items-center justify-content-center">
                            <i class="fas fa-user text-muted"></i>
                        </div>
                    </div>
                    <div>
                        <div class="fw-bold small">Gabriel Batavia</div>
                        <div class="text-muted small">Teknik Informatika</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- REAL-TIME SECTION -->
    <section class="section-dark">
        <div class="container">
            <div class="row align-items-center g-5" style="--bs-gutter-y: 3.5rem;">
                <div class="col-lg-6">
                    <div class="d-flex align-items-center mb-4 fade-in-left stagger-1">
                        <div class="bg-primary rounded d-flex align-items-center justify-content-center me-3 pulse-animation" style="width: 48px; height: 48px;">
                            <div class="bg-white rounded" style="width: 24px; height: 24px;"></div>
                        </div>
                        <h2 class="fw-bold mb-0 text-white">
                            Pelaporan Real-Time,<br>
                            Tindakan Real-Time.
                        </h2>
                    </div>

                    <p class="text-light fs-5 mb-4 fade-in-left stagger-2">
                        Fasilitas kampusmu terus digunakan –<br>
                        kenapa sistem pelaporannya tidak langsung merespons?
                    </p>

                    <p class="text-light fade-in-left stagger-3">
                        Akses langsung ke seluruh riwayat laporan. Pantau setiap laporan kerusakan secara
                        langsung. Semua data pengguna, lokasi, status laporan, dan progres dapat diakses
                        secara real-time.
                    </p>
                </div>

                <div class="col-lg-6">
                    <!-- Real-time Dashboard Image -->
                    <div class="media-container aspect-ratio-4-3 fade-in-right stagger-2 hover-lift">
                        <img src="{{ asset('assets/images/realtime-dashboard.jpg') }}"
                             alt="Real-time Dashboard FASILITA - Monitoring Laporan Langsung"
                             class="responsive-image"
                             loading="lazy"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                        <!-- Fallback for real-time dashboard -->
                        <div class="image-error d-none">
                            <i class="fas fa-tachometer-alt"></i>
                            <p class="mb-0 text-white">Real-time Dashboard</p>
                            <small class="text-light">Image not found</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feature Cards -->
            <div class="row g-4 mt-5" style="margin-top: 4rem !important;">
                <!-- Feature Card 1 -->
                <div class="col-md-6">
                    <div class="tool-card fade-in-left stagger-1 hover-lift">
                        <div class="tool-card-content">
                            <div class="tool-card-text">
                                <h4 class="fw-bold text-white mb-3">Pemantauan Pelaporan yang Kuat</h4>
                                <p class="text-white mb-0" style="color: #d1d5db !important;">
                                    Lacak setiap interaksi pengguna dengan fasilitas kampus. Catat status, riwayat tindakan,
                                    komunikasi antar admin, dan feedback pelapor dalam satu tampilan yang mudah dimengerti.
                                </p>
                            </div>
                            <!-- Monitoring Interface Image -->
                            <div class="tool-card-image-container">
                                <div class="media-container">
                                    <img src="{{ asset('assets/images/monitoring-interface.jpg') }}"
                                         alt="Monitoring Interface - Antarmuka Pemantauan Laporan"
                                         class="responsive-image"
                                         loading="lazy"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                                    <!-- Fallback for monitoring interface -->
                                    <div class="image-error d-none">
                                        <i class="fas fa-chart-bar"></i>
                                        <p class="mb-0 text-white small">Monitoring Interface</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Feature Card 2 -->
                <div class="col-md-6">
                    <div class="tool-card fade-in-right stagger-2 hover-lift">
                        <div class="tool-card-content">
                            <div class="tool-card-text">
                                <h4 class="fw-bold text-white mb-3">Rekap Laporan Otomatis ke PDF</h4>
                                <p class="text-white mb-0" style="color: #d1d5db !important;">
                                    Laporan otomatis disimpan dalam format PDF untuk keperluan cetak,
                                    evaluasi, dan dokumentasi rutin.
                                </p>
                            </div>
                            <!-- Google Sheets Integration Image -->
                            <div class="tool-card-image-container">
                                <div class="media-container">
                                    <img src="{{ asset('assets/images/PDF-integration.jpg') }}"
                                         alt="PDF Integration - Integrasi Laporan Otomatis"
                                         class="responsive-image"
                                         loading="lazy"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                                    <!-- Fallback for PDF integration -->
                                    <div class="image-error d-none">
                                        <i class="fas fa-table"></i>
                                        <p class="mb-0 text-white small">PDF Integration</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- EASY REPORTING SECTION -->
    <section class="section-light">
        <div class="container">
            <!-- Section Header -->
            <div class="row align-items-center mb-5 section-header">
                <div class="col-md-1">
                    <div class="bg-light rounded scale-in stagger-1" style="width: 32px; height: 32px;"></div>
                </div>
                <div class="col-md-11">
                    <h2 class="fw-bold text-primary mb-2 fade-in-left stagger-2">
                        Laporan Kerusakan<br>
                        Fasilitas dengan Mudah
                    </h2>
                    <p class="text-muted fade-in-left stagger-3">
                        Ikuti langkah-langkah berikut untuk mengirim laporan, memantau status, dan melihat
                        tindak lanjut secara real-time langsung dari dashboard Anda.
                    </p>
                </div>
            </div>

            <!-- Feature Points -->
            <div class="row g-4 mb-5" style="margin-bottom: 4rem !important;">
                <!-- Feature Point 1 -->
                <div class="col-md-4">
                    <div class="d-flex fade-in-up stagger-1">
                        <i class="fas fa-chart-bar text-primary me-3 mt-1 pulse-animation" style="font-size: 1.5rem;"></i>
                        <div>
                            <h5 class="fw-bold mb-2">Dashboard</h5>
                            <p class="text-muted small">Dukung laporan rusak terbanyak untuk percepat perbaikan</p>
                        </div>
                    </div>
                </div>

                <!-- Feature Point 2 -->
                <div class="col-md-4">
                    <div class="d-flex fade-in-up stagger-2">
                        <i class="fas fa-file-alt text-muted me-3 mt-1" style="font-size: 1.5rem;"></i>
                        <div>
                            <h5 class="fw-bold mb-2 text-muted">Fitur Buat Laporan</h5>
                            <p class="text-muted small">Tempat melaporkan kerusakan fasilitas secara mudah dan cepat.</p>
                        </div>
                    </div>
                </div>

                <!-- Feature Point 3 -->
                <div class="col-md-4">
                    <div class="d-flex fade-in-up stagger-3">
                        <i class="fas fa-eye text-muted me-3 mt-1" style="font-size: 1.5rem;"></i>
                        <div>
                            <h5 class="fw-bold mb-2 text-muted">Fitur Riwayat Laporan</h5>
                            <p class="text-muted small">Anda bisa melihat riwayat pelapor anda dan juga edit pelaporan nya</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Video Container -->
            <div class="video-container aspect-ratio-16-9 fade-in-up stagger-4 hover-lift">
                <!-- Video Player -->
                <video class="video-player"
                       poster="{{ asset('assets/images/video-poster.jpg') }}"
                       preload="metadata"
                       controls
                       style="display: none;"
                       onloadstart="console.log('Video loading started')"
                       onerror="console.error('Video loading error'); this.style.display='none'; document.getElementById('videoFallback').style.display='flex';">
                    <source src="{{ asset('assets/videos/fasilita-demo.mp4') }}" type="video/mp4">
                    <source src="{{ asset('assets/videos/fasilita-demo.webm') }}" type="video/webm">
                    Your browser does not support the video tag.
                </video>

                <!-- Video Overlay with Play Button -->
                <div class="video-overlay" id="videoOverlay" onclick="playVideo()">
                    <div class="play-button pulse-animation">
                        <i class="fas fa-play"></i>
                    </div>
                </div>

                <!-- Video Poster/Thumbnail -->
                <img src="{{ asset('assets/images/video-poster.jpg') }}"
                     alt="FASILITA Demo Video - Tutorial Penggunaan Sistem"
                     class="responsive-image"
                     style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 1;"
                     loading="lazy"
                     onerror="this.style.display='none'; document.getElementById('videoFallback').style.display='flex';">

                <!-- Fallback for video -->
                <div class="image-error d-none" id="videoFallback">
                    <div class="play-button mb-3">
                        <i class="fas fa-play"></i>
                    </div>
                    <p class="mb-0">Demo Video</p>
                    <small>Video not available</small>
                </div>
            </div>
        </div>
    </section>

    <!-- SUPPORTED TOOLS SECTION -->
    <section class="section-dark">
        <div class="container">
            <h2 class="fw-bold mb-3 text-white fade-in-up stagger-1" style="font-size: 3rem;">Supported Tools</h2>
            <p class="fs-5 text-light mb-5 fade-in-up stagger-2" style="max-width: 800px;">
            Mengandalkan kombinasi tools terbaik seperti Laravel, MySQL, dan Bootstrap, sistem ini
            dibangun untuk memberikan pengalaman pelaporan yang cepat, aman, dan efisien.
            </p>

            <div class="row g-4">
            <!-- Laravel -->
            <div class="col-md-4">
                <div class="tool-card fade-in-up stagger-1 hover-lift">
                <div class="tool-card-header">
                    <div class="tool-icon">
                    <img src="{{ asset('assets/images/logos/laravel.png') }}" alt="Laravel Logo">
                    </div>
                    <h4 class="fw-bold text-white mb-3">Laravel</h4>
                    <p class="text-white">Framework backend utama untuk pengelolaan logika dan data.</p>
                </div>
                </div>
            </div>

            <!-- Bootstrap -->
            <div class="col-md-4">
                <div class="tool-card fade-in-up stagger-2 hover-lift">
                <div class="tool-card-header">
                    <div class="tool-icon">
                    <img src="{{ asset('assets/images/logos/bootstrap.png') }}" alt="Bootstrap Logo">
                    </div>
                    <h4 class="fw-bold text-white mb-3">Bootstrap 4</h4>
                    <p class="text-white">Membantu desain UI agar responsif dan rapi.</p>
                </div>
                </div>
            </div>

            <!-- Skydash -->
            <div class="col-md-4">
                <div class="tool-card fade-in-up stagger-3 hover-lift">
                <div class="tool-card-header">
                    <div class="tool-icon-skydash">
                    <img src="{{ asset('assets/images/logos/skydash.png') }}" alt="Skydash Logo">
                    </div>
                    <h4 class="fw-bold text-white mb-3">Skydash</h4>
                    <p class="text-white">Template admin siap pakai untuk dashboard.</p>
                </div>
                </div>
            </div>

            <!-- jQuery -->
            <div class="col-md-4">
                <div class="tool-card fade-in-up stagger-4 hover-lift">
                <div class="tool-card-header">
                    <div class="tool-icon">
                    <img src="{{ asset('assets/images/logos/jquery.png') }}" alt="jQuery Logo">
                    </div>
                    <h4 class="fw-bold text-white mb-3">jQuery</h4>
                    <p class="text-white">Mempermudah interaksi dinamis di halaman web.</p>
                </div>
                </div>
            </div>

            <!-- Figma -->
            <div class="col-md-4">
                <div class="tool-card fade-in-up stagger-5 hover-lift">
                <div class="tool-card-header">
                    <div class="tool-icon">
                    <img src="{{ asset('assets/images/logos/figma.png') }}" alt="Figma Logo">
                    </div>
                    <h4 class="fw-bold text-white mb-3">Figma</h4>
                    <p class="text-white">Digunakan untuk desain UI sebelum diimplementasi.</p>
                </div>
                </div>
            </div>

            <!-- MySQL -->
            <div class="col-md-4">
                <div class="tool-card fade-in-up stagger-6 hover-lift">
                <div class="tool-card-header">
                    <div class="tool-icon">
                    <img src="{{ asset('assets/images/logos/mysql.png') }}" alt="MySQL Logo">
                    </div>
                    <h4 class="fw-bold text-white mb-3">MySQL</h4>
                    <p class="text-white">Menyimpan data laporan dan pengguna secara aman.</p>
                </div>
                </div>
            </div>
            </div>
        </div>
    </section>

    <!-- TEAM SECTION -->
    <section class="section-gray">
        <div class="container">
            <div class="d-flex align-items-center mb-4 section-header">
                <div class="bg-primary rounded me-3 scale-in stagger-1" style="width: 24px; height: 24px;"></div>
                <span class="text-primary fw-semibold fade-in-left stagger-2">Our Team</span>
            </div>

            <div class="row align-items-center g-5 mb-5">
                <div class="col-lg-6">
                    <h2 class="fw-bold team-section-heading fade-in-left stagger-1" style="font-size: 2.5rem; line-height: 1.2;">
                        Kami membangun sistem pelaporan yang tidak hanya fungsional, tapi juga mudah
                        diakses dan digunakan siapa saja
                    </h2>
                </div>

                <div class="col-lg-6 d-flex justify-content-end">
                    <div class="team-card fade-in-right stagger-2 hover-lift" style="max-width: 300px;">
                        <div class="team-image">
                            <!-- Main Team Member Photo -->
                            <img src="{{ asset('assets/images/team/Erik.jpeg') }}"
                                 alt="Jocelyn Schleifer - Project Manager FASILITA"
                                 loading="lazy"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                            <!-- Fallback for main team member -->
                            <div class="image-error d-none">
                                <i class="fas fa-user-tie"></i>
                                <p class="mb-0">Erik Ridho</p>
                                <small>Project Manager</small>
                            </div>
                        </div>
                        <div class="team-info">
                            <h5 class="fw-bold text-white">Erik Ridho</h5>
                            <p class="text-white mb-0" style="color: #d1d5db !important;">Projek Manajer</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team Grid -->
            <div class="row g-4" style="--bs-gutter-y: 2.5rem;">
                <!-- Team Member 1 -->
                <div class="col-md-3">
                    <div class="team-card fade-in-up stagger-1 hover-lift">
                        <div class="team-image">
                            <img src="{{ asset('assets/images/team/petrus.jpg') }}"
                                 alt="Petrus Tyang A.R - UI/UX & Full Stack Developer"
                                 loading="lazy"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                            <!-- Fallback for team member 1 -->
                            <div class="image-error d-none">
                                <i class="fas fa-user-cog"></i>
                                <p class="mb-0">Petrus Tyang A.R</p>
                                <small>UI/UX & Full Stack</small>
                            </div>
                        </div>
                        <div class="team-info">
                            <h6 class="fw-bold text-white">Petrus Tyang A.R</h6>
                            <small class="text-white" style="color: #d1d5db !important;">UI/UX & Full Stack</small>
                        </div>
                    </div>
                </div>

                <!-- Team Member 2 -->
                <div class="col-md-3">
                    <div class="team-card fade-in-up stagger-2 hover-lift">
                        <div class="team-image">
                            <img src="{{ asset('assets/images/team/reika.jpg') }}"
                                 alt="Reika Amalia Syahputri - Full Stack Developer"
                                 loading="lazy"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                            <!-- Fallback for team member 2 -->
                            <div class="image-error d-none">
                                <i class="fas fa-user-code"></i>
                                <p class="mb-0">Reika Amalia</p>
                                <small>Full Stack</small>
                            </div>
                        </div>
                        <div class="team-info">
                            <h6 class="fw-bold text-white">Reika Amalia Syahputri</h6>
                            <small class="text-white" style="color: #d1d5db !important;">Full Stack</small>
                        </div>
                    </div>
                </div>

                <!-- Team Member 3 -->
                <div class="col-md-3">
                    <div class="team-card fade-in-up stagger-3 hover-lift">
                        <div class="team-image">
                            <img src="{{ asset('assets/images/team/Muhammad Rifda Musyaffa.jpg') }}"
                                 alt="Muhammad Rifda Musyaffa - Full Stack Developer"
                                 loading="lazy"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                            <!-- Fallback for team member 3 -->
                            <div class="image-error d-none">
                                <i class="fas fa-user-graduate"></i>
                                <p class="mb-0">Muhammad Rifda Musyaffa </p>
                                <small>Full Stack</small>
                            </div>
                        </div>
                        <div class="team-info">
                            <h6 class="fw-bold text-white">Muhammad Rifda Musyaffa</h6>
                            <small class="text-white" style="color: #d1d5db !important;">Full Stack</small>
                        </div>
                    </div>
                </div>

                <!-- Team Member 4 -->
                <div class="col-md-3">
                    <div class="team-card fade-in-up stagger-4 hover-lift">
                        <div class="team-image">
                            <img src="{{ asset('assets/images/team/Afif.jpeg') }}"
                                 alt="Muhammad Afif Al Ghifari - Full Stack Developer"
                                 loading="lazy"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                            <!-- Fallback for team member 4 -->
                            <div class="image-error d-none">
                                <i class="fas fa-user-friends"></i>
                                <p class="mb-0">Muhammad Afif Al Ghifarir</p>
                                <small>Full Stack</small>
                            </div>
                        </div>
                        <div class="team-info">
                            <h6 class="fw-bold text-white">Muhammad Afif Al Ghifari</h6>
                            <small class="text-white" style="color: #d1d5db !important;">Full Stack</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer-dark">
        <div class="container">
            <div class="row g-4 mb-5 fade-in-up" style="--bs-gutter-y: 2.5rem; margin-bottom: 3.5rem !important;">
                <!-- Logo -->
                <div class="col-md-3 fade-in-left stagger-1">
                    <div class="d-flex align-items-center mb-4">
                        <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/LOGO%20%28Revisi%20Warna%29-eWjC9ZoeAArOze6ELrzncIwo0ebVJI.png"
                             alt="FASILITA Logo"
                             class="logo-image me-2 responsive-image hover-scale"
                             loading="lazy"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <!-- Fallback for footer logo -->
                        <div class="d-none bg-primary text-white rounded d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                            <strong style="font-size: 14px;">F</strong>
                        </div>
                    </div>
                </div>

                <!-- Fitur admin -->
                <div class="col-md-3 fade-in-up stagger-2">
                    <h6 class="text-muted mb-3">Fitur admin</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ url('/features') }}" class="footer-link">Features</a></li>
                        <li class="mb-2"><a href="{{ url('/pricing') }}" class="footer-link">Pricing</a></li>
                        <li class="mb-2"><a href="{{ url('/demo') }}" class="footer-link">Book a demo</a></li>
                    </ul>
                </div>

                <!-- Fitur User -->
                <div class="col-md-3 fade-in-up stagger-3">
                    <h6 class="text-muted mb-3">Fitur User</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ url('/events') }}" class="footer-link">Events</a></li>
                        <li class="mb-2"><a href="{{ url('/blog') }}" class="footer-link">Blog</a></li>
                    </ul>
                </div>

                <!-- Fitur Teknisi -->
                <div class="col-md-3 fade-in-up stagger-4">
                    <h6 class="text-muted mb-3">Fitur Teknisi</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ url('/about') }}" class="footer-link">About us</a></li>
                        <li class="mb-2"><a href="{{ url('/contact') }}" class="footer-link">Contact us</a></li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Footer -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center pt-4 border-top border-secondary fade-in-up stagger-5">
                <p class="text-muted mb-3 mb-md-0">© 2022 Welcome. All right reserved.</p>

                <div class="d-flex gap-3">
                    <a href="#" class="social-icon hover-lift"><i class="fab fa-youtube"></i></a>
                    <a href="#" class="social-icon hover-lift"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon hover-lift"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        /**
         * FASILITA Landing Page Scripts with Enhanced Animations
         *
         * Comprehensive animation system using jQuery and vanilla JavaScript
         * with fade in/out effects, scroll animations, and interactive elements.
         */

        $(document).ready(function() {

            // Initialize all animations
            initializeAnimations();
            initializeScrollAnimations();
            initializeNavbarEffects();
            initializeInteractiveElements();
            initializeVideoPlayer();

            /**
             * Initialize base animations on page load
             */
            function initializeAnimations() {
                // Trigger initial animations with delay for elements in viewport
                setTimeout(function() {
                    $('.fade-in, .fade-in-up, .fade-in-down, .fade-in-left, .fade-in-right, .scale-in, .scale-in-bounce, .rotate-in').each(function(index) {
                        const $element = $(this);

                        // Check if element is in initial viewport
                        if (isElementInViewport(this)) {
                            const delay = getStaggerDelay($element, index);

                            setTimeout(function() {
                                $element.addClass('animate');
                            }, delay);
                        }
                    });
                }, 300);
            }

            /**
             * Get stagger delay based on element classes
             */
            function getStaggerDelay($element, index) {
                if ($element.hasClass('stagger-1')) return 100;
                if ($element.hasClass('stagger-2')) return 200;
                if ($element.hasClass('stagger-3')) return 300;
                if ($element.hasClass('stagger-4')) return 400;
                if ($element.hasClass('stagger-5')) return 500;
                if ($element.hasClass('stagger-6')) return 600;
                if ($element.hasClass('stagger-7')) return 700;
                if ($element.hasClass('stagger-8')) return 800;
                return index * 100;
            }

            /**
             * Check if element is in viewport
             */
            function isElementInViewport(element) {
                const rect = element.getBoundingClientRect();
                return (
                    rect.top >= 0 &&
                    rect.left >= 0 &&
                    rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                    rect.right <= (window.innerWidth || document.documentElement.clientWidth)
                );
            }

            /**
             * Scroll-triggered animations using Intersection Observer
             */
            function initializeScrollAnimations() {
                // Create intersection observer for scroll animations
                const observerOptions = {
                    threshold: 0.1,
                    rootMargin: '0px 0px -50px 0px'
                };

                const observer = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            const $element = $(entry.target);

                            // Add animation class with staggered delay
                            const delay = $element.data('delay') || 0;
                            setTimeout(function() {
                                $element.addClass('animate');

                                // Add special effects for certain elements
                                if ($element.hasClass('typing-animation')) {
                                    startTypingAnimation($element);
                                }
                            }, delay);

                            // Unobserve after animation
                            observer.unobserve(entry.target);
                        }
                    });
                }, observerOptions);

                // Observe all animation elements that are not initially visible
                $('.fade-in, .fade-in-up, .fade-in-down, .fade-in-left, .fade-in-right, .scale-in, .scale-in-bounce, .rotate-in').each(function(index) {
                    const $element = $(this);

                    // Only observe elements that are not in the initial viewport
                    if (!isElementInViewport(this)) {
                        $element.data('delay', getStaggerDelay($element, index));
                        observer.observe(this);
                    }
                });
            }

            /**
             * Enhanced navbar effects
             */
            function initializeNavbarEffects() {
                const $navbar = $('.navbar');

                $(window).scroll(function() {
                    if ($(window).scrollTop() > 50) {
                        $navbar.addClass('scrolled');
                    } else {
                        $navbar.removeClass('scrolled');
                    }
                });
            }

            /**
             * Interactive element animations
             */
            function initializeInteractiveElements() {
                // Enhanced button hover effects
                $('.btn-primary-custom').hover(
                    function() {
                        $(this).addClass('pulse-animation');
                    },
                    function() {
                        $(this).removeClass('pulse-animation');
                    }
                );

                // Card hover animations
                $('.feature-card, .role-card, .tool-card, .team-card, .benefit-card').hover(
                    function() {
                        $(this).find('.feature-icon, .role-icon, .tool-icon, .benefit-icon').addClass('pulse-animation');
                    },
                    function() {
                        $(this).find('.feature-icon, .role-icon, .tool-icon, .benefit-icon').removeClass('pulse-animation');
                    }
                );

                // Feature item hover effects
                $('.feature-item').hover(
                    function() {
                        $(this).addClass('text-primary');
                        $(this).find('i').addClass('pulse-animation');
                    },
                    function() {
                        $(this).removeClass('text-primary');
                        $(this).find('i').removeClass('pulse-animation');
                    }
                );

                // Smooth scroll for anchor links
                $('a[href^="#"]').click(function(e) {
                    e.preventDefault();
                    const target = $(this.getAttribute('href'));
                    if (target.length) {
                        $('html, body').animate({
                            scrollTop: target.offset().top - 80
                        }, 800, 'easeInOutCubic');
                    }
                });

                // Loading animation for images
                $('img').on('load', function() {
                    $(this).addClass('fade-in animate');
                });

                // Parallax effect for hero section
                $(window).scroll(function() {
                    const scrolled = $(window).scrollTop();
                    const parallax = $('.hero-section');
                    const speed = scrolled * 0.2;

                    parallax.css('transform', 'translateY(' + speed + 'px)');
                });

                // Add click animations to buttons
                $('.btn').on('click', function() {
                    $(this).addClass('scale-in-bounce animate');
                    setTimeout(() => {
                        $(this).removeClass('scale-in-bounce animate');
                    }, 600);
                });
            }

            /**
             * Enhanced video player functionality
             */
            function initializeVideoPlayer() {
                window.playVideo = function() {
                    const $videoContainer = $('.video-container');
                    const $videoOverlay = $('#videoOverlay');
                    const $videoPlayer = $('.video-player');

                    // Add loading animation
                    $videoOverlay.html('<div class="loading-spinner"></div>');

                    // Fade out overlay and show video
                    $videoOverlay.fadeOut(500, function() {
                        $videoPlayer.fadeIn(300);
                        $videoPlayer[0].play();
                    });
                };
            }

            /**
             * Typing animation for hero title
             */
            function startTypingAnimation($element) {
                const text = $element.text();
                $element.text('');

                let i = 0;
                const typeWriter = function() {
                    if (i < text.length) {
                        $element.text($element.text() + text.charAt(i));
                        i++;
                        setTimeout(typeWriter, 100);
                    } else {
                        $element.removeClass('typing-animation');
                    }
                };

                setTimeout(typeWriter, 500);
            }

            /**
             * Counter animation for statistics
             */
            function animateCounters() {
                $('.counter').each(function() {
                    const $counter = $(this);
                    const target = parseInt($counter.data('target'));
                    const duration = 2000;
                    const increment = target / (duration / 16);
                    let current = 0;

                    const timer = setInterval(function() {
                        current += increment;
                        if (current >= target) {
                            current = target;
                            clearInterval(timer);
                        }
                        $counter.text(Math.floor(current));
                    }, 16);
                });
            }

            /**
             * Image lazy loading with fade effect
             */
            function initializeLazyLoading() {
                const imageObserver = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            const $img = $(img);

                            if (img.dataset.src) {
                                img.src = img.dataset.src;
                            }
                            $img.addClass('fade-in animate');

                            img.onload = function() {
                                $img.removeClass('image-loading');
                            };

                            imageObserver.unobserve(img);
                        }
                    });
                });

                $('img[data-src]').each(function() {
                    imageObserver.observe(this);
                });
            }

            /**
             * Enhanced error handling for media
             */
            function initializeMediaErrorHandling() {
                $('img').on('error', function() {
                    const $img = $(this);
                    const $fallback = $img.next('.image-error');

                    $img.fadeOut(300, function() {
                        if ($fallback.length) {
                            $fallback.fadeIn(300);
                        }
                    });
                });

                $('video').on('error', function() {
                    const $video = $(this);
                    const $fallback = $('#videoFallback');

                    $video.fadeOut(300, function() {
                        if ($fallback.length) {
                            $fallback.fadeIn(300);
                        }
                    });
                });
            }

            /**
             * Smooth page transitions
             */
            function initializePageTransitions() {
                // Fade in page content on load
                $('body').addClass('fade-in animate');

                // Smooth transitions for internal links
                $('a:not([href^="http"]):not([href^="#"]):not([target="_blank"])').click(function(e) {
                    const href = $(this).attr('href');

                    if (href && href !== '#') {
                        e.preventDefault();

                        $('body').fadeOut(300, function() {
                            window.location.href = href;
                        });
                    }
                });
            }

            /**
             * Performance optimization
             */
            function optimizePerformance() {
                // Debounce scroll events
                let scrollTimeout;
                $(window).on('scroll', function() {
                    if (scrollTimeout) {
                        clearTimeout(scrollTimeout);
                    }
                    scrollTimeout = setTimeout(function() {
                        // Scroll-dependent animations here
                    }, 16);
                });

                // Preload critical images
                const criticalImages = [
                    '{{ asset("assets/images/dashboard-preview.jpg") }}',
                    '{{ asset("assets/images/dashboard-interface.jpg") }}',
                    '{{ asset("assets/images/video-poster.jpg") }}'
                ];

                criticalImages.forEach(function(src) {
                    const img = new Image();
                    img.src = src;
                });
            }

            /**
             * Accessibility enhancements
             */
            function initializeAccessibility() {
                // Keyboard navigation for interactive elements
                $('.hover-lift, .feature-card, .role-card').on('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        $(this).trigger('click');
                    }
                });

                // Focus management for modals and overlays
                $('.video-overlay').on('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        playVideo();
                    }
                });

                // Reduced motion support
                if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                    $('*').css({
                        'animation-duration': '0.01ms !important',
                        'animation-iteration-count': '1 !important',
                        'transition-duration': '0.01ms !important'
                    });
                }
            }

            /**
             * Mobile-specific optimizations
             */
            function initializeMobileOptimizations() {
                if (window.innerWidth <= 768) {
                    // Reduce animation complexity on mobile
                    $('.float-animation, .pulse-animation').removeClass('float-animation pulse-animation');

                    // Optimize touch interactions
                    $('.hover-lift').on('touchstart', function() {
                        $(this).addClass('touch-active');
                    }).on('touchend', function() {
                        $(this).removeClass('touch-active');
                    });
                }
            }

            // Initialize all features
            initializeLazyLoading();
            initializeMediaErrorHandling();
            initializePageTransitions();
            optimizePerformance();
            initializeAccessibility();
            initializeMobileOptimizations();

            // Console log for debugging
            console.log('FASILITA Landing Page: All animations and interactions initialized successfully!');
        });

        /**
         * Custom easing function for jQuery animations
         */
        $.easing.easeInOutCubic = function(x, t, b, c, d) {
            if ((t /= d / 2) < 1) return c / 2 * t * t * t + b;
            return c / 2 * ((t -= 2) * t * t + 2) + b;
        };

        /**
         * Global utility functions
         */
        window.FASILITA = {
            // Fade in element with custom duration
            fadeIn: function(element, duration = 500) {
                $(element).fadeIn(duration);
            },

            // Fade out element with custom duration
            fadeOut: function(element, duration = 500) {
                $(element).fadeOut(duration);
            },

            // Animate element with custom properties
            animate: function(element, properties, duration = 500) {
                $(element).animate(properties, duration, 'easeInOutCubic');
            },

            // Show loading state
            showLoading: function(element) {
                $(element).html('<div class="loading-spinner"></div>');
            },

            // Hide loading state
            hideLoading: function(element) {
                $(element).find('.loading-spinner').remove();
            },

            // Trigger custom animation
            triggerAnimation: function(element, animationType = 'fade-in') {
                $(element).addClass(animationType + ' animate');
            }
        };

        /**
         * Enhanced Video Player Functionality
         */
        function playVideo() {
            const videoContainer = document.querySelector('.video-container');
            const videoOverlay = document.getElementById('videoOverlay');
            const videoPlayer = document.querySelector('.video-player');

            videoOverlay.classList.add('hidden');
            videoPlayer.style.display = 'block';
            videoPlayer.play();
        }

        /**
         * Image Loading and Error Handling
         */
        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('.responsive-image');

            images.forEach(img => {
                if (!img.complete) {
                    img.classList.add('image-loading');
                }

                img.onload = () => {
                    img.classList.remove('image-loading');
                };

                img.onerror = () => {
                    img.classList.remove('image-loading');
                    img.style.display = 'none';
                    const fallback = img.nextElementSibling;
                    if (fallback && fallback.classList.contains('image-error')) {
                        fallback.style.display = 'flex';
                    }
                };
            });
        });
    </script>
</body>
</html>
