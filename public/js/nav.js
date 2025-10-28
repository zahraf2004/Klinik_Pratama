const hamburger = document.getElementById("hamburger");
  const navLinks = document.querySelector(".nav-links");

  // Toggle menu nav
  hamburger.addEventListener("click", () => {
    navLinks.classList.toggle("active");
  });

  // Dropdown di mobile
  document.querySelectorAll(".dropdown > a").forEach(item => {
    item.addEventListener("click", (e) => {
      if (window.innerWidth <= 768) { // hanya di HP/tablet
        e.preventDefault();
        item.parentElement.classList.toggle("active");
      }
    });
  });

  // User dropdown
  const profileBtn = document.getElementById("profileDropdownBtn");
  const profileMenu = document.getElementById("profileDropdownMenu");
  if(profileBtn) {
    profileBtn.addEventListener("click", () => {
      profileMenu.style.display =
        profileMenu.style.display === "flex" ? "none" : "flex";
    });
  }