// Function to show the profile section and hide the dashboard
function showProfile() {
    const profileSection = document.getElementById('profile-section');
    const dashboard = document.querySelector('.dashboard .navigation');
    profileSection.style.display = 'block';
    dashboard.style.display = 'none';
}

// Function to hide the profile section and show the dashboard
function hideProfile() {
    const profileSection = document.getElementById('profile-section');
    const dashboard = document.querySelector('.dashboard .navigation');
    profileSection.style.display = 'none';
    dashboard.style.display = 'flex'; // Restore the dashboard layout
}