// assets/js/app.js

// Toggle goal form visibility
function toggleGoalForm() {
    const form = document.getElementById('goalForm');
    if (form) {
        form.classList.toggle('hidden');
    }
}

// Simulate asking AI for advice (front-end only)
function askAI() {
    const aiMessages = [
        "Based on your study patterns, try the Pomodoro technique: 25 minutes of focused study followed by a 5-minute break.",
        "Your most productive time seems to be in the evenings. Try scheduling important study sessions during that time.",
        "Consider creating flashcards for quick reviews of your most challenging subjects.",
        "Mix up your study locations occasionally. A change of environment can boost retention.",
        "Have you tried teaching the material to someone else? It's one of the most effective ways to learn.",
        "Don't forget to stay hydrated! Even mild dehydration can affect cognitive performance."
    ];

    const randomMessage = aiMessages[Math.floor(Math.random() * aiMessages.length)];

    // Find the container for AI messages (same structure as in PHP-rendered part)
    const container = document.querySelector('.ai-messages-container');
    if (container) {
        const newMessage = document.createElement('div');
        newMessage.className = 'ai-message bg-gray-100 rounded-lg p-3 mb-2';
        const now = new Date();
        // Format like "Jun 25 12:34 PM"
        const timestamp = now.toLocaleString('en-US', {
            month: 'short',
            day: 'numeric',
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });
        newMessage.innerHTML = `
            <div class="flex items-start">
                <div class="flex-shrink-0 bg-gray-200 p-1 rounded-full mr-2">
                    <i class="fas fa-robot text-gray-600 text-xs"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">${timestamp}</p>
                    <p class="text-sm text-gray-700 mt-1">${randomMessage}</p>
                </div>
            </div>
        `;
        // Prepend
        container.insertBefore(newMessage, container.firstChild);
        newMessage.scrollIntoView({ behavior: 'smooth' });
    }
}

// Initialize date inputs with today's date
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('date');
    if (dateInput) {
        const today = new Date().toISOString().split('T')[0];
        dateInput.value = today;
    }
});
