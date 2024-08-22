 // Custom JavaScript for CAT CPNS Simulation

// Function to confirm delete action
function confirmDelete(type, id) {
    if (confirm(`Are you sure you want to delete this ${type}?`)) {
        // If confirmed, redirect to delete URL
        window.location.href = `delete_${type}.php?id=${id}`;
    }
}

// Function to start the test timer
function startTimer(duration, display) {
    var timer = duration, minutes, seconds;
    setInterval(function () {
        minutes = parseInt(timer / 60, 10);
        seconds = parseInt(timer % 60, 10);

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.textContent = minutes + ":" + seconds;

        if (--timer < 0) {
            timer = 0;
            // Auto-submit the test when time is up
            document.getElementById("testForm").submit();
        }
    }, 1000);
}

// Initialize timer when the test page loads
window.onload = function () {
    var testDuration = 60 * 90, // 90 minutes
        display = document.querySelector('#time');
    if (display) {
        startTimer(testDuration, display);
    }
};
