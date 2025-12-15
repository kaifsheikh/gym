<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitTrack Pro | Workout Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --accent-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --success-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --warning-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --bg-color: #f8fafc;
            --card-bg: #ffffff;
            --text-color: #2d3748;
            --text-light: #718096;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --shadow-hover: 0 20px 40px rgba(0, 0, 0, 0.12);
            --radius: 20px;
            --radius-small: 12px;
            --transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.1);
        }

        .dark-mode {
            --primary-gradient: linear-gradient(135deg, #8a63d2 0%, #a55fc1 100%);
            --secondary-gradient: linear-gradient(135deg, #c471f5 0%, #fa71a3 100%);
            --accent-gradient: linear-gradient(135deg, #36d1dc 0%, #5b86e5 100%);
            --success-gradient: linear-gradient(135deg, #00cdac 0%, #02aab0 100%);
            --warning-gradient: linear-gradient(135deg, #ff9a9e 0%, #fad0c4 100%);
            --bg-color: #0f172a;
            --card-bg: #1e293b;
            --text-color: #f1f5f9;
            --text-light: #94a3b8;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
            --shadow-hover: 0 20px 40px rgba(0, 0, 0, 0.35);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            transition: var(--transition);
            overflow-x: hidden;
            min-height: 100vh;
            position: relative;
        }

        /* Animated Background */
        .animated-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0.05;
            background: radial-gradient(circle at 20% 50%, rgba(102, 126, 234, 0.2) 0%, transparent 50%),
                        radial-gradient(circle at 80% 20%, rgba(118, 75, 162, 0.2) 0%, transparent 50%),
                        radial-gradient(circle at 40% 80%, rgba(79, 172, 254, 0.2) 0%, transparent 50%);
        }

        /* Navbar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.2rem 2.5rem;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
            transition: var(--transition);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 800;
            font-size: 1.8rem;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            animation: fadeInDown 0.8s ease;
        }

        .logo i {
            font-size: 2rem;
        }

        .nav-links {
            display: flex;
            gap: 2.5rem;
            list-style: none;
            animation: fadeInDown 0.8s ease 0.2s both;
        }

        .nav-links a {
            color: var(--text-color);
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            position: relative;
            padding: 8px 0;
            transition: var(--transition);
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 3px;
            background: var(--primary-gradient);
            border-radius: 10px;
            transition: var(--transition);
        }

        .nav-links a:hover {
            color: #667eea;
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .nav-links a.active {
            color: #667eea;
        }

        .nav-links a.active::after {
            width: 100%;
        }

        .navbar-controls {
            display: flex;
            align-items: center;
            gap: 1.2rem;
            animation: fadeInDown 0.8s ease 0.4s both;
        }

        .dark-mode-toggle {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            width: 50px;
            height: 26px;
            border-radius: 50px;
            position: relative;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: inset 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .dark-mode-toggle::before {
            content: '';
            position: absolute;
            top: 3px;
            left: 3px;
            width: 20px;
            height: 20px;
            background: white;
            border-radius: 50%;
            transition: var(--transition);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .dark-mode .dark-mode-toggle::before {
            transform: translateX(24px);
            background: #f1f5f9;
        }

        .dark-mode-toggle i {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            font-size: 12px;
            color: #fbbf24;
        }

        .dark-mode-toggle .fa-sun {
            left: 6px;
            opacity: 0;
        }

        .dark-mode-toggle .fa-moon {
            right: 6px;
            opacity: 1;
        }

        .dark-mode .dark-mode-toggle .fa-sun {
            opacity: 1;
        }

        .dark-mode .dark-mode-toggle .fa-moon {
            opacity: 0;
        }

        .user-profile {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .user-profile:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        .mobile-menu-btn {
            display: none;
            background: transparent;
            border: none;
            color: var(--text-color);
            font-size: 1.8rem;
            cursor: pointer;
            transition: var(--transition);
        }

        /* Main Content */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem 2.5rem;
            animation: fadeIn 0.8s ease;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
            animation: fadeInUp 0.8s ease 0.2s both;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 800;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            position: relative;
            display: inline-block;
        }

        .page-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 60px;
            height: 5px;
            background: var(--primary-gradient);
            border-radius: 10px;
        }

        .stats-container {
            display: flex;
            gap: 1.5rem;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 10px;
            background: var(--card-bg);
            padding: 0.8rem 1.5rem;
            border-radius: var(--radius-small);
            box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .stat-item:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
        }

        .stat-1 .stat-icon {
            background: var(--primary-gradient);
        }

        .stat-2 .stat-icon {
            background: var(--secondary-gradient);
        }

        .stat-3 .stat-icon {
            background: var(--accent-gradient);
        }

        .stat-info h3 {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .stat-info p {
            font-size: 0.85rem;
            color: var(--text-light);
        }

        /* Cards Grid */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            margin-bottom: 3rem;
            animation: fadeInUp 0.8s ease 0.4s both;
        }

        .workout-card {
            background: var(--card-bg);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
            position: relative;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .workout-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-hover);
        }

        .workout-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: var(--primary-gradient);
        }

        .card-header {
            padding: 1.8rem 1.8rem 1.2rem;
            position: relative;
        }

        .category-badge {
            display: inline-block;
            padding: 6px 15px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: white;
        }

        .chest-badge {
            background: var(--primary-gradient);
        }

        .legs-badge {
            background: var(--secondary-gradient);
        }

        .shoulders-badge {
            background: var(--accent-gradient);
        }

        .back-badge {
            background: var(--success-gradient);
        }

        .arms-badge {
            background: var(--warning-gradient);
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-subtitle {
            color: var(--text-light);
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-subtitle i {
            color: #667eea;
        }

        .card-body {
            padding: 0 1.8rem 1.8rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .workout-meta {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.2rem;
            margin-bottom: 1.8rem;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: rgba(102, 126, 234, 0.08);
            border-radius: var(--radius-small);
            transition: var(--transition);
        }

        .meta-item:hover {
            background: rgba(102, 126, 234, 0.15);
            transform: translateY(-3px);
        }

        .meta-icon {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-small);
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--primary-gradient);
            color: white;
            font-size: 1.1rem;
        }

        .meta-info h4 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .meta-info p {
            font-size: 0.9rem;
            color: var(--text-light);
        }

        .exercise-section {
            margin-top: auto;
        }

        .exercise-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 0.8rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .exercise-header h3 {
            font-size: 1.1rem;
            font-weight: 600;
        }

        .exercise-count {
            background: var(--primary-gradient);
            color: white;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .exercise-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: var(--radius-small);
            margin-bottom: 0.8rem;
            transition: var(--transition);
        }

        .exercise-item:hover {
            background: rgba(102, 126, 234, 0.1);
            transform: translateX(5px);
        }

        .exercise-name {
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .exercise-name i {
            color: #667eea;
        }

        .exercise-reps {
            font-weight: 700;
            color: #667eea;
            background: rgba(102, 126, 234, 0.1);
            padding: 6px 12px;
            border-radius: 50px;
        }

        .card-footer {
            padding: 1.5rem 1.8rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .calories-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 700;
            color: #f59e0b;
        }

        .detail-btn {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .detail-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        /* Footer */
        .footer {
            text-align: center;
            padding: 2rem;
            color: var(--text-light);
            font-size: 0.9rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 2rem;
            animation: fadeIn 0.8s ease 0.6s both;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

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

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .cards-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 992px) {
            .navbar {
                padding: 1rem 1.5rem;
            }
            
            .nav-links {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background: var(--card-bg);
                flex-direction: column;
                padding: 1.5rem;
                text-align: center;
                box-shadow: var(--shadow);
                border-top: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: 0 0 var(--radius) var(--radius);
            }
            
            .nav-links.active {
                display: flex;
                animation: fadeInDown 0.5s ease;
            }
            
            .mobile-menu-btn {
                display: block;
            }
            
            .stats-container {
                flex-direction: column;
                width: 100%;
                margin-top: 1rem;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 1.5rem;
            }
            
            .cards-grid {
                grid-template-columns: 1fr;
            }
            
            .workout-meta {
                grid-template-columns: 1fr;
            }
            
            .page-title {
                font-size: 2rem;
            }
        }

        @media (max-width: 576px) {
            .logo span {
                display: none;
            }
            
            .navbar {
                padding: 1rem;
            }
            
            .container {
                padding: 1rem;
            }
            
            .card-header, .card-body, .card-footer {
                padding: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Animated Background -->
    <div class="animated-bg"></div>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo">
            <i class="fas fa-dumbbell"></i>
            <span>FitTrack Pro</span>
        </div>
        
        <ul class="nav-links">
            <li><a href="#" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="#"><i class="fas fa-dumbbell"></i> Workouts</a></li>
            <li><a href="#"><i class="fas fa-chart-line"></i> Analytics</a></li>
            <li><a href="#"><i class="fas fa-calendar-alt"></i> Schedule</a></li>
            <li><a href="#"><i class="fas fa-user"></i> Profile</a></li>
        </ul>
        
        <div class="navbar-controls">
            <button class="dark-mode-toggle" id="darkModeToggle">
                <i class="fas fa-sun"></i>
                <i class="fas fa-moon"></i>
            </button>
            <div class="user-profile">JD</div>
            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Workout Dashboard</h1>
            <div class="stats-container">
                <div class="stat-item stat-1">
                    <div class="stat-icon">
                        <i class="fas fa-fire-alt"></i>
                    </div>
                    <div class="stat-info">
                        <h3>1,240</h3>
                        <p>Calories Today</p>
                    </div>
                </div>
                <div class="stat-item stat-2">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-info">
                        <h3>42</h3>
                        <p>Workout Hours</p>
                    </div>
                </div>
                <div class="stat-item stat-3">
                    <div class="stat-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="stat-info">
                        <h3>18</h3>
                        <p>Completed Plans</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Cards Grid -->
        <div class="cards-grid">
            <!-- Card 1 - Chest Workout -->
            <div class="workout-card">
                <div class="card-header">
                    <div class="category-badge chest-badge">CHEST</div>
                    <div class="card-title">
                        <span>Upper Chest Focus</span>
                        <span class="difficulty intermediate">INTERMEDIATE</span>
                    </div>
                    <div class="card-subtitle">
                        <i class="fas fa-bullseye"></i>
                        <span>Target: Upper Pectorals</span>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="workout-meta">
                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="fas fa-dumbbell"></i>
                            </div>
                            <div class="meta-info">
                                <h4>Barbell</h4>
                                <p>Equipment</p>
                            </div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="meta-info">
                                <h4>30 Min</h4>
                                <p>Duration</p>
                            </div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="fas fa-list-ol"></i>
                            </div>
                            <div class="meta-info">
                                <h4>5</h4>
                                <p>Exercises</p>
                            </div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <div class="meta-info">
                                <h4>300</h4>
                                <p>Calories</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="exercise-section">
                        <div class="exercise-header">
                            <h3>Main Exercise</h3>
                            <div class="exercise-count">4x12</div>
                        </div>
                        
                        <div class="exercise-item">
                            <div class="exercise-name">
                                <i class="fas fa-angle-right"></i>
                                <span>Incline Barbell Press</span>
                            </div>
                            <div class="exercise-reps">4 sets × 12 reps</div>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer">
                    <div class="calories-badge">
                        <i class="fas fa-fire"></i>
                        <span>300 kcal Burn</span>
                    </div>
                    <button class="detail-btn" onclick="showExerciseDetails('Incline Barbell Press')">
                        <i class="fas fa-info-circle"></i> Details
                    </button>
                </div>
            </div>
            
            <!-- Card 2 - Legs Workout -->
            <div class="workout-card">
                <div class="card-header">
                    <div class="category-badge legs-badge">LEGS</div>
                    <div class="card-title">
                        <span>Leg Day Power</span>
                        <span class="difficulty advanced">ADVANCED</span>
                    </div>
                    <div class="card-subtitle">
                        <i class="fas fa-bullseye"></i>
                        <span>Target: Quads & Glutes</span>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="workout-meta">
                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="fas fa-dumbbell"></i>
                            </div>
                            <div class="meta-info">
                                <h4>Barbell</h4>
                                <p>Equipment</p>
                            </div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="meta-info">
                                <h4>45 Min</h4>
                                <p>Duration</p>
                            </div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="fas fa-list-ol"></i>
                            </div>
                            <div class="meta-info">
                                <h4>6</h4>
                                <p>Exercises</p>
                            </div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <div class="meta-info">
                                <h4>420</h4>
                                <p>Calories</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="exercise-section">
                        <div class="exercise-header">
                            <h3>Main Exercise</h3>
                            <div class="exercise-count">5x10</div>
                        </div>
                        
                        <div class="exercise-item">
                            <div class="exercise-name">
                                <i class="fas fa-angle-right"></i>
                                <span>Barbell Squats</span>
                            </div>
                            <div class="exercise-reps">5 sets × 10 reps</div>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer">
                    <div class="calories-badge">
                        <i class="fas fa-fire"></i>
                        <span>420 kcal Burn</span>
                    </div>
                    <button class="detail-btn" onclick="showExerciseDetails('Barbell Squats')">
                        <i class="fas fa-info-circle"></i> Details
                    </button>
                </div>
            </div>
            
            <!-- Card 3 - Shoulders Workout -->
            <div class="workout-card">
                <div class="card-header">
                    <div class="category-badge shoulders-badge">SHOULDERS</div>
                    <div class="card-title">
                        <span>Deltoid Development</span>
                        <span class="difficulty intermediate">INTERMEDIATE</span>
                    </div>
                    <div class="card-subtitle">
                        <i class="fas fa-bullseye"></i>
                        <span>Target: All Deltoid Heads</span>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="workout-meta">
                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="fas fa-dumbbell"></i>
                            </div>
                            <div class="meta-info">
                                <h4>Dumbbells</h4>
                                <p>Equipment</p>
                            </div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="meta-info">
                                <h4>35 Min</h4>
                                <p>Duration</p>
                            </div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="fas fa-list-ol"></i>
                            </div>
                            <div class="meta-info">
                                <h4>5</h4>
                                <p>Exercises</p>
                            </div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <div class="meta-info">
                                <h4>280</h4>
                                <p>Calories</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="exercise-section">
                        <div class="exercise-header">
                            <h3>Main Exercise</h3>
                            <div class="exercise-count">4x12</div>
                        </div>
                        
                        <div class="exercise-item">
                            <div class="exercise-name">
                                <i class="fas fa-angle-right"></i>
                                <span>Overhead Press</span>
                            </div>
                            <div class="exercise-reps">4 sets × 12 reps</div>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer">
                    <div class="calories-badge">
                        <i class="fas fa-fire"></i>
                        <span>280 kcal Burn</span>
                    </div>
                    <button class="detail-btn" onclick="showExerciseDetails('Overhead Press')">
                        <i class="fas fa-info-circle"></i> Details
                    </button>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <p>FitTrack Pro Workout Dashboard • Track, Train, Transform • © 2023 All Rights Reserved</p>
        </div>
    </div>

    <!-- Exercise Details Modal -->
    <div id="exerciseModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Exercise Details</h2>
                <button class="close-modal" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Content will be inserted here -->
            </div>
        </div>
    </div>

    <script>
        // Dark Mode Toggle
        const darkModeToggle = document.getElementById('darkModeToggle');
        
        darkModeToggle.addEventListener('click', () => {
            document.body.classList.toggle('dark-mode');
            localStorage.setItem('darkMode', document.body.classList.contains('dark-mode') ? 'enabled' : 'disabled');
        });
        
        // Check for saved dark mode preference
        if (localStorage.getItem('darkMode') === 'enabled') {
            document.body.classList.add('dark-mode');
        }
        
        // Mobile Menu Toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const navLinks = document.querySelector('.nav-links');
        
        mobileMenuBtn.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            mobileMenuBtn.querySelector('i').className = 
                navLinks.classList.contains('active') ? 'fas fa-times' : 'fas fa-bars';
        });
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!navLinks.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
                navLinks.classList.remove('active');
                mobileMenuBtn.querySelector('i').className = 'fas fa-bars';
            }
        });
        
        // Exercise Details Modal
        function showExerciseDetails(exerciseName) {
            const modal = document.getElementById('exerciseModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalBody = document.getElementById('modalBody');
            
            modalTitle.textContent = `${exerciseName} - Detailed Instructions`;
            
            let content = '';
            
            if (exerciseName === 'Incline Barbell Press') {
                content = `
                    <div class="exercise-detail">
                        <div class="detail-section">
                            <h3><i class="fas fa-dumbbell"></i> How to Perform</h3>
                            <ol>
                                <li>Set an incline bench to 30-45 degrees angle.</li>
                                <li>Lie back on the bench with feet flat on the floor.</li>
                                <li>Grasp the barbell with hands slightly wider than shoulder-width.</li>
                                <li>Unrack the barbell and lower it to your upper chest.</li>
                                <li>Press the barbell back up to the starting position.</li>
                                <li>Repeat for the desired number of repetitions.</li>
                            </ol>
                        </div>
                        
                        <div class="detail-section">
                            <h3><i class="fas fa-muscle"></i> Target Muscles</h3>
                            <ul>
                                <li><strong>Primary:</strong> Upper Pectoralis Major</li>
                                <li><strong>Secondary:</strong> Anterior Deltoids, Triceps</li>
                            </ul>
                        </div>
                        
                        <div class="detail-section">
                            <h3><i class="fas fa-lightbulb"></i> Tips for Maximum Effectiveness</h3>
                            <ul>
                                <li>Keep your back firmly against the bench throughout the movement.</li>
                                <li>Don't arch your back excessively to lift more weight.</li>
                                <li>Lower the bar slowly and with control to maximize muscle tension.</li>
                                <li>Exhale as you press the weight up, inhale as you lower it.</li>
                                <li>For intermediate level, aim for 3-5 sets of 8-12 reps.</li>
                            </ul>
                        </div>
                        
                        <div class="detail-section">
                            <h3><i class="fas fa-exclamation-triangle"></i> Common Mistakes to Avoid</h3>
                            <ul>
                                <li>Bouncing the bar off your chest</li>
                                <li>Flaring elbows out too wide</li>
                                <li>Lifting hips off the bench</li>
                                <li>Using too much weight with poor form</li>
                            </ul>
                        </div>
                    </div>
                `;
            } else if (exerciseName === 'Barbell Squats') {
                content = `
                    <div class="exercise-detail">
                        <div class="detail-section">
                            <h3><i class="fas fa-dumbbell"></i> How to Perform</h3>
                            <ol>
                                <li>Position the barbell on your upper back (not your neck).</li>
                                <li>Stand with feet shoulder-width apart, toes slightly pointed out.</li>
                                <li>Engage your core and keep your chest up.</li>
                                <li>Lower your body by bending at the hips and knees.</li>
                                <li>Descend until your thighs are parallel to the floor.</li>
                                <li>Drive through your heels to return to the starting position.</li>
                            </ol>
                        </div>
                        
                        <div class="detail-section">
                            <h3><i class="fas fa-muscle"></i> Target Muscles</h3>
                            <ul>
                                <li><strong>Primary:</strong> Quadriceps, Glutes</li>
                                <li><strong>Secondary:</strong> Hamstrings, Calves, Core</li>
                            </ul>
                        </div>
                    </div>
                `;
            } else {
                content = `
                    <div class="exercise-detail">
                        <div class="detail-section">
                            <h3><i class="fas fa-dumbbell"></i> How to Perform</h3>
                            <ol>
                                <li>Sit on a bench with back support.</li>
                                <li>Hold dumbbells at shoulder height with palms facing forward.</li>
                                <li>Press the weights overhead until arms are fully extended.</li>
                                <li>Lower the weights back to shoulder height with control.</li>
                                <li>Repeat for the desired number of repetitions.</li>
                            </ol>
                        </div>
                        
                        <div class="detail-section">
                            <h3><i class="fas fa-muscle"></i> Target Muscles</h3>
                            <ul>
                                <li><strong>Primary:</strong> Anterior & Lateral Deltoids</li>
                                <li><strong>Secondary:</strong> Triceps, Upper Trapezius</li>
                            </ul>
                        </div>
                    </div>
                `;
            }
            
            modalBody.innerHTML = content;
            modal.style.display = 'flex';
            
            // Add modal styles dynamically
            const modalStyle = document.createElement('style');
            modalStyle.textContent = `
                .modal {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.7);
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    z-index: 1000;
                    backdrop-filter: blur(10px);
                    animation: fadeIn 0.3s ease;
                }
                
                .modal-content {
                    background: var(--card-bg);
                    border-radius: var(--radius);
                    width: 90%;
                    max-width: 700px;
                    max-height: 80vh;
                    overflow-y: auto;
                    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                    animation: fadeInUp 0.4s ease;
                    border: 1px solid rgba(255, 255, 255, 0.1);
                }
                
                .modal-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 1.8rem;
                    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                }
                
                .modal-header h2 {
                    background: var(--primary-gradient);
                    -webkit-background-clip: text;
                    background-clip: text;
                    color: transparent;
                    font-size: 1.8rem;
                    font-weight: 700;
                }
                
                .close-modal {
                    background: transparent;
                    border: none;
                    color: var(--text-color);
                    font-size: 2rem;
                    cursor: pointer;
                    transition: var(--transition);
                    width: 40px;
                    height: 40px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border-radius: 50%;
                }
                
                .close-modal:hover {
                    background: rgba(255, 255, 255, 0.1);
                    transform: rotate(90deg);
                }
                
                .modal-body {
                    padding: 2rem;
                }
                
                .detail-section {
                    margin-bottom: 2rem;
                }
                
                .detail-section h3 {
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    margin-bottom: 1rem;
                    color: var(--text-color);
                    font-size: 1.3rem;
                }
                
                .detail-section h3 i {
                    color: #667eea;
                }
                
                .detail-section ol, .detail-section ul {
                    padding-left: 1.5rem;
                    line-height: 1.7;
                }
                
                .detail-section li {
                    margin-bottom: 0.5rem;
                    color: var(--text-light);
                }
                
                @media (max-width: 768px) {
                    .modal-content {
                        width: 95%;
                        margin: 1rem;
                    }
                    
                    .modal-header, .modal-body {
                        padding: 1.2rem;
                    }
                }
            `;
            
            if (!document.querySelector('#modalStyles')) {
                modalStyle.id = 'modalStyles';
                document.head.appendChild(modalStyle);
            }
        }
        
        function closeModal() {
            document.getElementById('exerciseModal').style.display = 'none';
        }
        
        // Close modal when clicking outside
        document.getElementById('exerciseModal').addEventListener('click', function(e) {
            if (e.target.id === 'exerciseModal') {
                closeModal();
            }
        });
        
        // Add hover animation to cards on load
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.workout-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>
</body>
</html>