<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stress Induction System</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #f4f7f6; color: #333; line-height: 1.6; }
        
        /* Navigation Bar */
        nav { display: flex; justify-content: space-between; align-items: center; padding: 20px 50px; background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.05); position: fixed; width: 100%; top: 0; z-index: 1000; }
        .logo { font-size: 20px; font-weight: bold; color: #4b6bfb; }
        .nav-links a { margin-left: 20px; text-decoration: none; color: #555; font-weight: 500; transition: 0.2s; }
        .nav-links a:hover { color: #4b6bfb; }
        .login-btn { background-color: #4b6bfb; color: white !important; padding: 10px 20px; border-radius: 6px; font-weight: bold; }
        .login-btn:hover { background-color: #3a56d4; }

        /* Hero Section */
        .hero { display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 150px 20px 100px 20px; background: linear-gradient(135deg, #eef2ff 0%, #f4f7f6 100%); }
        .hero h1 { font-size: 48px; font-weight: 800; color: #222; max-width: 800px; margin-bottom: 20px; line-height: 1.2; }
        .hero p { font-size: 18px; color: #666; max-width: 600px; margin-bottom: 40px; }
        .hero-buttons { display: flex; gap: 15px; }
        .btn-primary { background-color: #4b6bfb; color: white; text-decoration: none; padding: 15px 30px; border-radius: 6px; font-weight: bold; font-size: 16px; transition: 0.2s; }
        .btn-primary:hover { background-color: #3a56d4; }
        .btn-secondary { background-color: white; color: #4b6bfb; border: 2px solid #4b6bfb; text-decoration: none; padding: 13px 30px; border-radius: 6px; font-weight: bold; font-size: 16px; transition: 0.2s; }
        .btn-secondary:hover { background-color: #eef2ff; }

        /* Features Section */
        .features { padding: 80px 50px; display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; max-width: 1200px; margin: auto; }
        .feature-card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #eaeaea; text-align: center; }
        .feature-card h3 { color: #4b6bfb; margin-bottom: 15px; font-size: 20px;}

        /* Contact Section */
        .contact-section { background: white; padding: 80px 20px; text-align: center; border-top: 1px solid #eaeaea; }
        .contact-container { max-width: 600px; margin: auto; background: #f8f9fa; padding: 40px; border-radius: 12px; border: 1px solid #eaeaea; }
        .contact-container h2 { margin-bottom: 10px; color: #222; }
        .contact-container p { color: #666; margin-bottom: 30px; font-size: 15px;}
        .form-group { margin-bottom: 15px; text-align: left; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; color: #444; font-size: 14px;}
        .form-group input, .form-group textarea { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; font-family: inherit; }
        .submit-btn { width: 100%; background-color: #222; color: white; border: none; padding: 15px; border-radius: 6px; font-weight: bold; font-size: 16px; cursor: pointer; transition: 0.2s; }
        .submit-btn:hover { background-color: #444; }

        footer { text-align: center; padding: 30px; background: #222; color: #aaa; font-size: 14px; }
    </style>
</head>
<body>

    <nav>
        <div class="logo">Stress System</div>
        <div class="nav-links">
            <a href="#about">About</a>
            <a href="#contact">Contact Us</a>
            <a href="/login" class="login-btn">Researcher Login</a>
        </div>
    </nav>

    <section class="hero">
        <h1>Centralized Web-Based System for Stress Induction Methods</h1>
        <p>A unified platform integrating the Stroop Test, Montreal Imaging Stress Task (MIST), and Trier Social Stress Test (TSST) for advanced psychological research.</p>
        <div class="hero-buttons">
            <a href="/login" class="btn-primary">Go to Dashboard</a>
            <a href="#contact" class="btn-secondary">Request Access</a>
        </div>
    </section>

    <section class="features" id="about">
        <div class="feature-card">
            <h3>Stroop Test</h3>
            <p>Measure cognitive interference and selective attention with automated reaction time tracking and accuracy scoring.</p>
        </div>
        <div class="feature-card">
            <h3>MIST</h3>
            <p>Induce stress through time-pressured arithmetic tasks with real-time feedback and dynamic difficulty adjustment.</p>
        </div>
        <div class="feature-card">
            <h3>TSST Arithmetic</h3>
            <p>Standardized serial subtraction tasks designed to trigger acute psychological stress under evaluative observation.</p>
        </div>
    </section>

    <section class="contact-section" id="contact">
        <div class="contact-container">
            <h2>Request Researcher Access</h2>
            <p>For security and data privacy, public registration is disabled. Please contact the system administrator to request an account for your institution.</p>
            
            <form action="#" method="GET">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" placeholder="Dr. Jane Doe" required>
                </div>
                <div class="form-group">
                    <label>Institution / University Email</label>
                    <input type="email" placeholder="jane@university.edu" required>
                </div>
                <div class="form-group">
                    <label>Research Purpose</label>
                    <textarea rows="4" placeholder="Briefly describe your research study..." required></textarea>
                </div>
                <button type="submit" class="submit-btn" onclick="alert('Thank you! The administrator will review your request.')">Submit Request</button>
            </form>
        </div>
    </section>

    <footer>
        &copy; 2026 Centralized Stress Induction System. All Rights Reserved.
    </footer>

</body>
</html>