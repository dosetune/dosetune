<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DoseTune - Login</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" href="../logo.png" type="image/x-icon">
</head>
<body>
    <header>
        <div class="container">
            <img src="../logo.png" alt="DoseTune Logo" class="logo">
            <nav>
                <ul class="menu">
                    <li><a href="../index.html">Home</a></li>
                    <li><a href="../services.html">Services</a></li>
                    <li><a href="../about.html">About</a></li>
                    <li><a href="../contactus.html">Contact</a></li>
                    <li><a href="../redirect.php" class="order-now">Order Now</a></li>
                </ul>
                <div class="hamburger" id="hamburger-icon">
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                </div>
            </nav>
        </div>
    </header>

   
    
    <section class="form-section">
          <!-- Message display -->
        <div class="form-container">
            <!-- Error message for signup failure -->
            <?php if (isset($_GET['error'])): ?>
                    <div class="error-message">
                        <?php
                            if ($_GET['error'] == 'invalid') {
                                echo "Invalid username or password. Please try again.";
                            } elseif ($_GET['error'] == 'email') {
                                echo "The email address is already taken. Please log in.";
                            }
                        ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($_GET['success']) && $_GET['success'] == 'signup'): ?>
        <div class="success-message">
            You have successfully signed up. Please log in to continue.
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error']) && $_GET['error'] == 'signup'): ?>
        <div class="error-message">
            Error: Could not complete registration. Please try again.
        </div>
    <?php endif; ?>
            <!-- Login Form Box -->
            <div class="form-box login-box">
                <h2>Login</h2>
                <form action="login_process.php" method="post">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn">Login</button>
                </form>
                <div class="forgot-password">
                    <a href="../forgot_password/forgot_password.php">Forgot Password?</a>
                </div>
                <!-- Error message for login failure -->
                <div class="dont">
                    <p>Don't have an account? <a href="#" id="to-signup">Sign up</a></p>
                </div>
            </div>

            <!-- Sign Up Form Box -->
            <div class="form-box signup-box" id="signup-box">
                <h2>Sign Up</h2>
                <form id="signup-form" action="signup_process.php" method="post">
                    <!-- Username -->
                    <div class="form-group">
                        <label for="new-username">Username:</label>
                        <input type="text" id="new-username" name="username" required>
                    </div>

                    <!-- Full Name -->
                    <div class="form-group">
                        <label for="fullname">Full Name:</label>
                        <input type="text" id="fullname" name="fullname" required>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="new-email">Email:</label>
                        <input type="email" id="new-email" name="email" required>
                    </div>

                    <!-- Phone Number -->
                    <div class="form-group">
                        <label for="phone">Phone Number:</label>
                        <input type="text" id="phone" name="phone" required>
                    </div>

                    <!-- Date of Birth -->
                    <div class="form-group">
                        <label for="dob">Date of Birth:</label>
                        <input type="date" id="dob" name="dob" required>
                    </div>

                    <!-- Gender -->
                    <div class="form-group">
                        <label for="gender">Gender:</label>
                        <select id="gender" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="new-password">Password:</label>
                        <input type="password" id="new-password" name="password" required>
                    </div>

                    <!-- OTP -->
                    <div class="form-group">
                        <label for="otp">Enter OTP:</label>
                        <input type="text" id="otp" name="otp" required>
                    </div>

                    <!-- Send OTP Button -->
                    <button type="button" id="send-otp" class="btn">Send OTP</button>
                    <br>
                    <!-- Submit Button -->
                    <button type="submit" class="btn">Sign Up</button>
                </form>

                <div class="dont">
                    <p>Already have an account? <a href="#" id="to-login">Login</a></p>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="footer-left">
                <h2>DoseTune</h2>
                <p>Your trusted partner in healthcare solutions. We provide quality medicines and exceptional service.</p>
            </div>
            <div class="footer-right">
              <p>Follow Us</p>
              
              <a href="#" aria-label="Facebook">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-facebook h-6 w-6">
                      <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                  </svg>
              </a>
              <a href="#" aria-label="Twitter">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-twitter h-6 w-6">
                      <path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"></path>
                  </svg>
              </a>
              <a href="https://www.instagram.com/dosetune?igsh=bHBrNmUwOGJiYXNi" aria-label="Instagram">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-instagram h-6 w-6">
                      <rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect>
                      <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                      <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"></line>
                  </svg>
              </a>
          </div>
        </div> 
        <div class="footer-bottom">
          <hr><br>
            <p>&copy; 2024 DoseTune. All rights reserved.</p>
        </div>
    </footer>
    <script src="script.js"></script>
</body>
</html>
