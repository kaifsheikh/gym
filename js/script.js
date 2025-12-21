        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.tab'),
                navLinks = document.querySelectorAll('.nav-link'),
                forms = document.querySelectorAll('.form-card');

            function switchTab(tabId) {
                tabs.forEach(t => t.classList.remove('active'));
                navLinks.forEach(l => l.classList.remove('active'));
                forms.forEach(f => f.classList.remove('active'));
                document.querySelector(`.tab[data-tab="${tabId}"]`).classList.add('active');
                document.querySelector(`.nav-link[data-tab="${tabId}"]`).classList.add('active');
                document.getElementById(tabId).classList.add('active');
            }
            tabs.forEach(tab => tab.addEventListener('click', () => switchTab(tab.getAttribute('data-tab'))));
            navLinks.forEach(link => link.addEventListener('click', (e) => {
                e.preventDefault();
                switchTab(link.getAttribute('data-tab'));
            }));

            // Theme Toggle
            const themeToggle = document.getElementById('theme-toggle'),
                themeIcon = document.getElementById('theme-icon'),
                body = document.body;
            const currentTheme = localStorage.getItem('theme') || 'light';
            if (currentTheme === 'dark') {
                body.classList.add('dark-theme');
                themeIcon.classList.replace('fa-moon', 'fa-sun');
            }
            themeToggle.addEventListener('click', () => {
                body.classList.toggle('dark-theme');
                if (body.classList.contains('dark-theme')) {
                    themeIcon.classList.replace('fa-moon', 'fa-sun');
                    localStorage.setItem('theme', 'dark');
                } else {
                    themeIcon.classList.replace('fa-sun', 'fa-moon');
                    localStorage.setItem('theme', 'light');
                }
            });

            // Modal functionality
            window.openExerciseModal = function(exerciseId) {
                const modal = document.getElementById('exerciseModal');
                fetch(`?get_exercise_details&exercise_id=${exerciseId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            alert(data.error);
                            return;
                        }
                        document.getElementById('modal-exercise-name').innerText = data.exercise_name || 'N/A';
                        document.getElementById('modal-equipment').innerText = data.equipment || 'N/A';
                        document.getElementById('modal-muscle-head').innerText = `${data.muscle_name || 'N/A'} – ${data.head_name || 'N/A'}`;
                        document.getElementById('modal-difficulty').innerText = data.difficulty || 'N/A';
                        document.getElementById('modal-description').innerText = data.head_description || 'No description available.';
                        const imgElement = document.getElementById('modal-muscle-image');
                        if (data.muscle_image) {
                            imgElement.src = `./uploads/${data.muscle_image}`;
                        } else {
                            imgElement.style.display = 'none';
                        }
                        const instructionsList = document.getElementById('modal-instructions');
                        instructionsList.innerHTML = '';
                        const exampleInstructions = ["Step 1: Prepare your equipment.", "Step 2: Get into the starting position.", "Step 3: Perform the main movement.", "Step 4: Return to the starting position."];
                        exampleInstructions.forEach((text, index) => {
                            const li = document.createElement('li');
                            li.className = 'instruction-item';
                            li.innerHTML = `<span class="instruction-number">${index + 1}</span><span class="instruction-text">${text}</span>`;
                            instructionsList.appendChild(li);
                        });
                        modal.style.display = 'flex';
                    })
                    .catch(error => console.error('Error fetching exercise details:', error));
            };
            window.closeExerciseModal = function() {
                document.getElementById('exerciseModal').style.display = 'none';
            };
            window.onclick = function(event) {
                const modal = document.getElementById('exerciseModal');
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            };
        });