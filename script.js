const sidebarToggleBtns = document.querySelectorAll(".sidebar-toggle");
const sidebar = document.querySelector(".sidebar");
const searchForm = document.querySelector(".search-form");
const themeToggleBtn = document.querySelector(".theme-toggle");
const themeIcon = themeToggleBtn.querySelector(".theme-icon");
const menuLinks = document.querySelectorAll(".menu-link");
// Updates the theme icon based on current theme and sidebar state
const updateThemeIcon = () => {
  const isDark = document.body.classList.contains("dark-theme");
  themeIcon.textContent = sidebar.classList.contains("collapsed") ? (isDark ? "light_mode" : "dark_mode") : "dark_mode";
};
// Apply dark theme if saved or system prefers, then update icon
const savedTheme = localStorage.getItem("theme");
const systemPrefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
const shouldUseDarkTheme = savedTheme === "dark" || (!savedTheme && systemPrefersDark);
document.body.classList.toggle("dark-theme", shouldUseDarkTheme);
updateThemeIcon();
// Toggle between themes on theme button click
themeToggleBtn.addEventListener("click", () => {
  const isDark = document.body.classList.toggle("dark-theme");
  localStorage.setItem("theme", isDark ? "dark" : "light");
  updateThemeIcon();
});
// Toggle sidebar collapsed state on buttons click
sidebarToggleBtns.forEach((btn) => {
  btn.addEventListener("click", () => {
    sidebar.classList.toggle("collapsed");
    updateThemeIcon();
  });
});
// Expand the sidebar when the search form is clicked
searchForm.addEventListener("click", () => {
  if (sidebar.classList.contains("collapsed")) {
    sidebar.classList.remove("collapsed");
    searchForm.querySelector("input").focus();
  }
});
// Expand sidebar by default on large screens
if (window.innerWidth > 768) sidebar.classList.remove("collapsed");




// Exercise Modal Functions
      let currentSlideIndex = 1;
      
      function openExerciseModal(exerciseId) {
        document.getElementById('exerciseModal').classList.add('active');
        document.body.style.overflow = 'hidden';
        
        // Update exercise title based on exercise ID
        const exerciseTitles = {
          'bench-press': 'Bench Press',
          'incline-dumbbell-press': 'Incline Dumbbell Press',
          'cable-flyes': 'Cable Flyes',
          'push-ups': 'Push-ups',
          'dips': 'Dips',
          'barbell-curls': 'Barbell Curls',
          'hammer-curls': 'Hammer Curls',
          'concentration-curls': 'Concentration Curls',
          'preacher-curls': 'Preacher Curls',
          'tricep-pushdowns': 'Tricep Pushdowns',
          'skull-crushers': 'Skull Crushers',
          'overhead-extensions': 'Overhead Extensions',
          'diamond-push-ups': 'Diamond Push-ups',
          'tricep-dips': 'Tricep Dips',
          'overhead-press': 'Overhead Press',
          'lateral-raises': 'Lateral Raises',
          'face-pulls': 'Face Pulls',
          'front-raises': 'Front Raises',
          'shrugs': 'Shrugs',
          'deadlifts': 'Deadlifts',
          'pull-ups': 'Pull-ups',
          'bent-over-rows': 'Bent Over Rows',
          'lat-pulldowns': 'Lat Pulldowns',
          'seated-cable-rows': 'Seated Cable Rows',
          'hyperextensions': 'Hyperextensions',
          'squats': 'Squats',
          'leg-press': 'Leg Press',
          'romanian-deadlifts': 'Romanian Deadlifts',
          'leg-curls': 'Leg Curls',
          'calf-raises': 'Calf Raises',
          'walking-lunges': 'Walking Lunges',
          'crunches': 'Crunches',
          'leg-raises': 'Leg Raises',
          'russian-twists': 'Russian Twists',
          'plank': 'Plank',
          'bicycle-crunches': 'Bicycle Crunches'
        };
        
        document.getElementById('exerciseTitle').textContent = exerciseTitles[exerciseId] || 'Exercise';
        showSlide(1);
      }
      
      function closeExerciseModal() {
        document.getElementById('exerciseModal').classList.remove('active');
        document.body.style.overflow = 'auto';
      }
      
      function changeSlide(direction) {
        showSlide(currentSlideIndex += direction);
      }
      
      function currentSlide(n) {
        showSlide(currentSlideIndex = n);
      }
      
      function showSlide(n) {
        const slides = document.getElementsByClassName('carousel-slide');
        const dots = document.getElementsByClassName('carousel-dot');
        
        if (n > slides.length) { currentSlideIndex = 1 }
        if (n < 1) { currentSlideIndex = slides.length }
        
        for (let i = 0; i < slides.length; i++) {
          slides[i].classList.remove('active');
        }
        
        for (let i = 0; i < dots.length; i++) {
          dots[i].classList.remove('active');
        }
        
        slides[currentSlideIndex - 1].classList.add('active');
        dots[currentSlideIndex - 1].classList.add('active');
      }
      
      // Close modal when clicking outside
      window.onclick = function(event) {
        const modal = document.getElementById('exerciseModal');
        if (event.target == modal) {
          closeExerciseModal();
        }
      }
      
      // Keyboard navigation for carousel
      document.addEventListener('keydown', function(event) {
        const modal = document.getElementById('exerciseModal');
        if (modal.classList.contains('active')) {
          if (event.key === 'ArrowLeft') {
            changeSlide(-1);
          } else if (event.key === 'ArrowRight') {
            changeSlide(1);
          } else if (event.key === 'Escape') {
            closeExerciseModal();
          }
        }
      });