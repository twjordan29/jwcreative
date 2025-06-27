// Smooth scrolling navigation
document.querySelectorAll(".nav-link").forEach((link) => {
  link.addEventListener("click", function (e) {
    e.preventDefault();
    const targetId = this.getAttribute("href");
    const targetSection = document.querySelector(targetId);

    if (targetSection) {
      targetSection.scrollIntoView({
        behavior: "smooth",
        block: "start",
      });
    }
  });
});

// Active navigation highlighting
const sections = document.querySelectorAll(".section");
const navLinks = document.querySelectorAll(".nav-link");

function updateActiveNav() {
  let current = "";
  sections.forEach((section) => {
    const sectionTop = section.offsetTop - 100;
    const sectionHeight = section.clientHeight;
    if (
      window.scrollY >= sectionTop &&
      window.scrollY < sectionTop + sectionHeight
    ) {
      current = "#" + section.getAttribute("id");
    }
  });

  navLinks.forEach((link) => {
    link.classList.remove("active");
    if (link.getAttribute("href") === current) {
      link.classList.add("active");
    }
  });
}

// Scroll animations
function animateOnScroll() {
  const animatedElements = document.querySelectorAll(".animate-on-scroll");

  animatedElements.forEach((element) => {
    const elementTop = element.getBoundingClientRect().top;
    const elementVisible = 150;

    if (elementTop < window.innerHeight - elementVisible) {
      element.classList.add("animate");
    }
  });
}

// Parallax effect for sections
function parallaxEffect() {
  const scrolled = window.pageYOffset;
  const parallaxElements = document.querySelectorAll(".section");

  parallaxElements.forEach((element, index) => {
    const rate = scrolled * -0.5;
    if (index % 2 === 0) {
      element.style.transform = `translate3d(0, ${rate * 0.1}px, 0)`;
    }
  });
}

// Scroll event listeners
window.addEventListener("scroll", () => {
  updateActiveNav();
  animateOnScroll();
  parallaxEffect();
});

// Form submission
document
  .querySelector(".contact-form")
  .addEventListener("submit", function (e) {
    e.preventDefault();

    // Simple form animation
    const submitBtn = this.querySelector(".submit-btn");
    const originalText = submitBtn.textContent;

    submitBtn.textContent = "Sending...";
    submitBtn.style.background =
      "linear-gradient(135deg, #4CAF50 0%, #45a049 100%)";

    setTimeout(() => {
      submitBtn.textContent = "Message Sent!";
      setTimeout(() => {
        submitBtn.textContent = originalText;
        submitBtn.style.background = "";
        this.reset();
      }, 2000);
    }, 1500);
  });

// Initialize animations on load
document.addEventListener("DOMContentLoaded", () => {
  animateOnScroll();
  updateActiveNav();
});

// Smooth hover effects for project cards
document.querySelectorAll(".project-card").forEach((card) => {
  card.addEventListener("mouseenter", function () {
    this.style.transform = "translateY(-10px) scale(1.02)";
  });

  card.addEventListener("mouseleave", function () {
    this.style.transform = "translateY(0) scale(1)";
  });
});
