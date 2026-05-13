const toggle = document.querySelector("[data-menu-toggle]");
const links = document.querySelector("[data-nav-links]");

if (toggle && links) {
  toggle.addEventListener("click", () => {
    const isOpen = links.classList.toggle("open");
    toggle.setAttribute("aria-expanded", String(isOpen));
  });
}

const renderSharedFooter = () => {
  const path = window.location.pathname.replace(/\\/g, "/");
  const nested = ["/blog/", "/modules/", "/docs/"].some((segment) => path.includes(segment));
  const prefix = nested ? "../" : "";

  const footerMarkup = `
    <footer class="footer footer-reference">
      <div class="footer-inner footer-reference-inner">
        <div class="footer-brand-block">
          <a class="brand footer-brand" href="${prefix}index.html">
            <span class="brand-mark">CE</span>
            <span>CampusEdgePro</span>
          </a>
          <h3>Sortiq Management System</h3>
          <p>Simplifying administrative management for institutes of all sizes. Transform your operations with our powerful platform.</p>
          <div class="footer-socials" aria-label="Social links">
            <a href="#" aria-label="Facebook">f</a>
            <a href="#" aria-label="X">x</a>
            <a href="#" aria-label="LinkedIn">in</a>
            <a href="mailto:hello@sortiqsolutions.com" aria-label="Email">@</a>
          </div>
        </div>
        <div class="footer-columns">
          <div class="footer-column">
            <strong>Product</strong>
            <a href="${prefix}features.html">Features</a>
            <a href="${prefix}pricing.html">Pricing</a>
            <a href="${prefix}modules.html">Modules</a>
            <a href="${prefix}demo.html">Roadmap</a>
            <a href="${prefix}blog.html">Updates</a>
          </div>
          <div class="footer-column">
            <strong>Company</strong>
            <a href="${prefix}about.html">About Us</a>
            <a href="${prefix}blog.html">Blog</a>
            <a href="${prefix}contact.html">Contact</a>
            <a href="${prefix}modules.html">Partners</a>
          </div>
          <div class="footer-column">
            <strong>Resources</strong>
            <a href="${prefix}docs/index.html">Documentation</a>
            <a href="${prefix}docs/faq.html">Help Center</a>
            <a href="${prefix}blog.html">Community</a>
          </div>
          <div class="footer-column footer-subscribe">
            <strong>Stay Updated</strong>
            <p>Get the latest news and updates from the world of EdTech.</p>
            <form class="footer-subscribe-form">
              <input type="email" placeholder="Email Address" aria-label="Email address">
              <button type="button">Subscribe</button>
            </form>
          </div>
        </div>
      </div>
      <div class="footer-bottom">
        <div class="footer-bottom-inner">
          <p>&copy; <span data-year></span> Sortiq Management System. All rights reserved.</p>
          <div class="footer-bottom-actions">
            <span class="footer-badge">Secure</span>
            <span class="footer-badge">SOC 2 Certified</span>
            <a class="footer-chat" href="${prefix}contact.html" aria-label="Contact us">?</a>
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
