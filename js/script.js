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
function openExerciseModal(exerciseId) {

    // Modal open
    const modal = document.getElementById('exerciseModal');
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';

    // Fetch exercise data
    fetch("fetch_exercise.php?id=" + exerciseId)
        .then(res => res.json())
        .then(d => {

            // Title
            document.getElementById('exerciseTitle').innerText = d.exercise_name;

            // Description (muscle head)
            document.getElementById('modalDescription').innerText = d.description;

            // Image
            document.getElementById('modalHeadImage').src = "./uploads/" + d.image_url;

            // Equipment
            document.getElementById('tagEquipment').innerHTML =
                `<span class="material-symbols-rounded">fitness_center</span> ${d.equipment}`;

            // Muscle + Head
            document.getElementById('tagMuscle').innerHTML =
                `<span class="material-symbols-rounded">local_fire_department</span> ${d.muscle_name} – ${d.head_name}`;

            // Difficulty
            document.getElementById('tagLevel').innerHTML =
                `<span class="material-symbols-rounded">signal_cellular_alt</span> ${d.difficulty}`;
        })
        .catch(err => console.error("Exercise Fetch Error:", err));
}

// Close modal
function closeExerciseModal() {
    const modal = document.getElementById('exerciseModal');
    modal.classList.remove('active');
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('exerciseModal');
    if (event.target === modal) {
        closeExerciseModal();
    }
};

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    const modal = document.getElementById('exerciseModal');
    if (modal.classList.contains('active') && event.key === 'Escape') {
        closeExerciseModal();
    }
});
