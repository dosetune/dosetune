// JavaScript to toggle between login and signup forms
document.getElementById('to-signup').addEventListener('click', function() {
    document.querySelector('.form-container').classList.add('show-signup');
});

document.getElementById('to-login').addEventListener('click', function() {
    document.querySelector('.form-container').classList.remove('show-signup');
});


  // Get elements for hamburger menu toggle
  const hamburger = document.querySelector('.hamburger');
  const menu = document.querySelector('header nav ul');
  
  // Toggle 'active' class on hamburger and menu
  hamburger.addEventListener('click', () => {
    hamburger.classList.toggle('active');  // Add/remove 'active' class to hamburger
    menu.classList.toggle('active');  // Add/remove 'active' class to menu
  });


 // Handle Send OTP button click
 document.getElementById('send-otp').addEventListener('click', function() {
    var email = document.getElementById('new-email').value;
    if (!email) {
        alert('Please enter a valid email address.');
        return;
    }

    // Send OTP request via AJAX
    fetch('send_otp.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email: email })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('OTP sent successfully to your Email... [Check Spam Folder if Not received!] !');
        } else {
            alert(data.error);
        }
    })
    .catch(error => {
        console.error('Error sending OTP:', error);
        alert('Something went wrong, please try again.');
    });
});

// Toggle between Sign Up and Login
document.getElementById('to-signup').addEventListener('click', function() {
    document.querySelector('.login-box').style.display = 'none';
    document.querySelector('.signup-box').style.display = 'block';
});

document.getElementById('to-login').addEventListener('click', function() {
    document.querySelector('.signup-box').style.display = 'none';
    document.querySelector('.login-box').style.display = 'block';
});