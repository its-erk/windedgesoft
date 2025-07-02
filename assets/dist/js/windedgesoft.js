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

window.addEventListener("scroll", function () {
  const scrolled = window.scrollY;
  const hero = document.querySelector(".hero");
  hero.style.backgroundPositionY = `${scrolled * 0.5}px`;
});
