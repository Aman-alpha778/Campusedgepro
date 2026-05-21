const toggle = document.querySelector("[data-menu-toggle]");
const links = document.querySelector("[data-nav-links]");

if (toggle && links) {
  toggle.addEventListener("click", () => {
    const isOpen = links.classList.toggle("open");
    toggle.setAttribute("aria-expanded", String(isOpen));
  });
}

const normalizeNavLayout = () => {
  document.querySelectorAll(".nav").forEach((nav) => {
    const navLinks = nav.querySelector("[data-nav-links]");
    if (!navLinks) {
      return;
    }

    const inlineCta = navLinks.querySelector("a.button");
    const existingCta = nav.querySelector(".nav-cta");

    if (inlineCta) {
      inlineCta.classList.add("nav-cta");
      nav.appendChild(inlineCta);
      return;
    }

    if (existingCta && existingCta.parentElement !== nav) {
      nav.appendChild(existingCta);
    }
  });
};

normalizeNavLayout();

const renderSharedFooter = () => {
  const path = window.location.pathname.replace(/\\/g, "/");
  const nested = ["/blog/", "/modules/", "/docs/"].some((segment) => path.includes(segment));
  const prefix = nested ? "../" : "";

  const footerMarkup = `
    <footer class="footer footer-reference">
      <div class="footer-inner footer-reference-inner">
        <div class="footer-brand-block">
          <a class="brand footer-brand" href="${prefix}index.html">
            <img class="brand-logo footer-logo" src="/assets/footer-logo.png" alt="CampusEdgePro">
          </a>
          <p class="footer-kicker">CampusEdgePro by Sortiq Solutions</p>
          <h3>Professional ERP for colleges that want clarity across admissions, academics, finance, and reporting.</h3>
          <p>One connected platform for student operations, institutional teams, and leadership visibility.</p>
          <div class="footer-socials" aria-label="Sortiq Solutions links">
            <a href="https://www.facebook.com/SortiqSolutions" target="_blank" rel="noreferrer" aria-label="Facebook">Facebook</a>
            <a href="https://www.linkedin.com/company/sortiqsolutions" target="_blank" rel="noreferrer" aria-label="LinkedIn">LinkedIn</a>
            <a href="mailto:hello@sortiqsolutions.com" aria-label="Email">Email us</a>
          </div>
        </div>
        <div class="footer-columns">
          <div class="footer-column">
            <strong>Platform</strong>
            <a href="${prefix}features.html">Features</a>
            <a href="${prefix}modules.html">Modules</a>
            <a href="${prefix}pricing.html">Pricing</a>
            <a href="${prefix}demo.html">Book Demo</a>
          </div>
          <div class="footer-column">
            <strong>Company</strong>
            <a href="${prefix}about.html">About Us</a>
            <a href="${prefix}blog.html">Blog</a>
            <a href="${prefix}contact.html">Contact</a>
            <a href="${prefix}ads.html">Campaign Page</a>
          </div>
          <div class="footer-column">
            <strong>Resources</strong>
            <a href="${prefix}docs/index.html">Documentation</a>
            <a href="${prefix}docs/faq.html">Help Center</a>
            <a href="${prefix}modules/student-management.html">Student Module</a>
            <a href="${prefix}modules/fees.html">Fee Module</a>
          </div>
          <div class="footer-column footer-contact-card">
            <strong>Get started</strong>
            <p>Need setup help or a quick product walkthrough?</p>
            <a class="footer-primary-link" href="${prefix}contact.html">Register your interest</a>
            <a class="footer-secondary-link" href="mailto:hello@sortiqsolutions.com">hello@sortiqsolutions.com</a>
          </div>
        </div>
      </div>
      <div class="footer-bottom">
        <div class="footer-bottom-inner">
          <p>&copy; <span data-year></span> CampusEdgePro by Sortiq Solutions. All rights reserved.</p>
          <div class="footer-bottom-actions">
            <span class="footer-badge">Admissions CRM</span>
            <span class="footer-badge">Fee Management</span>
            <span class="footer-badge">Attendance Tracking</span>
            <a class="footer-chat" href="${prefix}demo.html" aria-label="Book a demo">Book Demo</a>
          </div>
        </div>
      </div>
    </footer>
  `;

  const existingFooter = document.querySelector("footer");
  if (existingFooter) {
    existingFooter.outerHTML = footerMarkup;
  } else {
    const main = document.querySelector("main");
    if (main) {
      main.insertAdjacentHTML("afterend", footerMarkup);
    } else {
      document.body.insertAdjacentHTML("beforeend", footerMarkup);
    }
  }
};

renderSharedFooter();

document.querySelectorAll("[data-year]").forEach((node) => {
  node.textContent = new Date().getFullYear();
});

document.querySelectorAll("[data-slider]").forEach((slider) => {
  const track = slider.querySelector("[data-slider-track]");
  const dots = Array.from(slider.querySelectorAll("[data-slider-dot]"));
  if (!track || dots.length === 0) return;

  let index = 0;

  const showSlide = (nextIndex) => {
    index = (nextIndex + dots.length) % dots.length;
    track.style.transform = `translateX(-${index * 100}%)`;
    dots.forEach((dot, dotIndex) => {
      dot.classList.toggle("active", dotIndex === index);
      dot.setAttribute("aria-pressed", String(dotIndex === index));
    });
  };

  dots.forEach((dot, dotIndex) => {
    dot.addEventListener("click", () => showSlide(dotIndex));
  });

  showSlide(0);
  window.setInterval(() => showSlide(index + 1), 3200);
});

document.querySelectorAll(".contact-inquiry-type").forEach((group) => {
  const input = group.querySelector("[data-inquiry-input]");
  const options = Array.from(group.querySelectorAll("[data-inquiry-option]"));
  if (!input || options.length === 0) return;

  const setActiveOption = (nextValue) => {
    input.value = nextValue;
    options.forEach((option) => {
      const isActive = option.dataset.inquiryOption === nextValue;
      option.classList.toggle("active", isActive);
      option.setAttribute("aria-pressed", String(isActive));
    });
  };

  options.forEach((option) => {
    option.addEventListener("click", () => {
      setActiveOption(option.dataset.inquiryOption || option.textContent.trim());
    });
  });
});

document.querySelectorAll("[data-site-notice]").forEach((notice) => {
  const closeButton = notice.querySelector("[data-site-notice-close]");

  const hideNotice = () => {
    notice.classList.remove("is-visible");
    window.setTimeout(() => {
      notice.remove();
    }, 250);
  };

  if (closeButton) {
    closeButton.addEventListener("click", hideNotice);
  }

  

  window.setTimeout(hideNotice, 4500);
});
