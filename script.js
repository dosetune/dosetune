document.addEventListener("DOMContentLoaded", () => {
    // Function to handle slider actions (prev and next) for any slider
    function initializeSlider(sliderClass, prevButtonClass, nextButtonClass) {
      const slider = document.querySelector(sliderClass);
      const slides = document.querySelectorAll(`${sliderClass} .slide`);
      const prevButton = document.querySelector(prevButtonClass);
      const nextButton = document.querySelector(nextButtonClass);
  
      let currentIndex = 0;
      let autoSlideInterval;
  
      // Function to update the display of slides
      function updateSlider() {
        slides.forEach((slide, index) => {
          slide.style.display = "none";  // Hide all slides
        });
        slides[currentIndex].style.display = "block";  // Show the current slide
      }
  
      // Show the first slide initially
      updateSlider();
  
      // Function to go to the next slide
      function nextSlide() {
        currentIndex = (currentIndex < slides.length - 1) ? currentIndex + 1 : 0;
        updateSlider();
      }
  
      // Function to go to the previous slide
      function prevSlide() {
        currentIndex = (currentIndex > 0) ? currentIndex - 1 : slides.length - 1;
        updateSlider();
      }
  
      // Set interval for auto sliding (every 2 seconds)
      function startAutoSlide() {
        autoSlideInterval = setInterval(nextSlide, 2000);
      }
  
      // Stop auto sliding
      function stopAutoSlide() {
        clearInterval(autoSlideInterval);
      }
  
      // Restart auto sliding
      function restartAutoSlide() {
        stopAutoSlide();
        startAutoSlide();
      }
  
      // Event listener for the previous button
      prevButton.addEventListener("click", () => {
        prevSlide();
        restartAutoSlide();
      });
  
      // Event listener for the next button
      nextButton.addEventListener("click", () => {
        nextSlide();
        restartAutoSlide();
      });
  
      // Start the automatic slide change for this slider
      startAutoSlide();
    }
  
    // Initialize both sliders with the same function and the same buttons
    initializeSlider(".slider", ".prev", ".next");
    initializeSlider(".slider2", ".prev", ".next");
  });
  
  // Get elements for hamburger menu toggle
  const hamburger = document.querySelector('.hamburger');
  const menu = document.querySelector('header nav ul');
  
  // Toggle 'active' class on hamburger and menu
  hamburger.addEventListener('click', () => {
    hamburger.classList.toggle('active');  // Add/remove 'active' class to hamburger
    menu.classList.toggle('active');  // Add/remove 'active' class to menu
  });
  