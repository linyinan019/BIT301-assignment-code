document.getElementById("profile-btn").addEventListener("click", function() {
    document.getElementById("dropdown-menu").classList.toggle("hidden");
});

document.addEventListener("click", function(event) {
    let profileDropdown = document.getElementById("profile-btn");
    let dropdownMenu = document.getElementById("dropdown-menu");

    if (!profileDropdown.contains(event.target) && !dropdownMenu.contains(event.target)) {
        dropdownMenu.classList.add("hidden");
    }
});

function handleLogout() {
    localStorage.removeItem("username"); 
    alert("Logged out successfully!");
    window.location.href = "index.php"; 
}

document.getElementById("dropdown-logout").addEventListener("click", handleLogout);
document.getElementById("sidebar-logout").addEventListener("click", handleLogout);
