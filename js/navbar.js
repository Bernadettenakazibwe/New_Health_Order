

// Convert this to:
document.addEventListener("DOMContentLoaded", function() {
  const navbar = document.getElementById("topnav");
  navbar.innerHTML = `
    <a href="index.html">Home</a>
    <a href="about.html">About</a>
  `;
});
