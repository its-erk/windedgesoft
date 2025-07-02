window.addEventListener("scroll", function () {
  const navbar = document.getElementById("mainNav");
  if (window.scrollY > 50) {
    navbar.classList.add("scrolled", "navbar-light");
    navbar.classList.remove("navbar-dark", "bg-transparent");
  } else {
    navbar.classList.remove("scrolled", "navbar-light");
    navbar.classList.add("navbar-dark", "bg-transparent");
  }
});