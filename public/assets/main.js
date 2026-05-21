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
        <div class="footer-panel footer-panel-main">
          <div class="footer-split">
            <div class="footer-logo-part">
              <a class="brand footer-brand" href="${prefix}index.html">
                <img class="brand-logo logo-footer" src="/assets/cmpus.png" alt="CampusEdgePro">
              </a>
              <p>Sortiq Solutions, CampusEdgePro</p>
              <p>Unified ERP for admissions, academics, attendance, fees, and communication across schools and colleges.</p>
            </div>
            <div class="footer-about">
              <h6>About Company</h6>
              <p>CampusEdgePro helps institutions replace disconnected manual work with a streamlined digital campus workflow.</p>
              <a href="${prefix}about.html" class="btn-footer">More Info</a><br>
              <a href="${prefix}contact.html" class="btn-footer">Contact Us</a>
            </div>
          </div>
        </div>
        <div class="footer-panel footer-panel-links">
          <div class="footer-split">
            <div class="footer-help">
              <h6>Help Us</h6>
              <div class="footer-link-columns">
                <ul>
                  <li><a href="${prefix}index.html">Home</a></li>
                  <li><a href="${prefix}about.html">About</a></li>
                  <li><a href="${prefix}features.html">Features</a></li>
                  <li><a href="${prefix}modules.html">Modules</a></li>
                  <li><a href="${prefix}docs/index.html">Docs</a></li>
                  <li><a href="${prefix}contact.html">Contact</a></li>
                </ul>
                <ul>
                  <li><a href="${prefix}pricing.html">Pricing</a></li>
                  <li><a href="${prefix}demo.html">Book Demo</a></li>
                  <li><a href="${prefix}blog.html">Blog</a></li>
                  <li><a href="${prefix}docs/faq.html">Support</a></li>
                  <li><a href="${prefix}docs/api.html">API</a></li>
                  <li><a href="mailto:sortiqsolutions@gmail.com">Email</a></li>
                </ul>
              </div>
            </div>
            <div class="footer-newsletter">
              <h6>Profiles</h6>
              <div class="social" aria-label="Social links">
                <a href="https://www.facebook.com/SortiqSolutions" target="_blank" rel="noreferrer" aria-label="Facebook">
                  <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M13.5 21v-7h2.4l.4-3h-2.8V9.2c0-.9.3-1.5 1.6-1.5H16V5.1c-.2 0-1-.1-2-.1-2 0-3.4 1.2-3.4 3.5V11H8v3h2.6v7h2.9Z"></path>
                  </svg>
                </a>
                <a href="https://www.instagram.com/" target="_blank" rel="noreferrer" aria-label="Instagram">
                  <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M7.5 3h9A4.5 4.5 0 0 1 21 7.5v9a4.5 4.5 0 0 1-4.5 4.5h-9A4.5 4.5 0 0 1 3 16.5v-9A4.5 4.5 0 0 1 7.5 3Zm0 1.8A2.7 2.7 0 0 0 4.8 7.5v9a2.7 2.7 0 0 0 2.7 2.7h9a2.7 2.7 0 0 0 2.7-2.7v-9a2.7 2.7 0 0 0-2.7-2.7h-9Zm9.4 1.3a1.1 1.1 0 1 1 0 2.2 1.1 1.1 0 0 1 0-2.2ZM12 7.3A4.7 4.7 0 1 1 7.3 12 4.7 4.7 0 0 1 12 7.3Zm0 1.8A2.9 2.9 0 1 0 14.9 12 2.9 2.9 0 0 0 12 9.1Z"></path>
                  </svg>
                </a>
                <a href="https://www.linkedin.com/company/sortiqsolutions" target="_blank" rel="noreferrer" aria-label="LinkedIn">
                  <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M6.2 8.3A1.8 1.8 0 1 1 6.1 4.7a1.8 1.8 0 0 1 .1 3.6ZM4.8 9.8h2.8V19H4.8V9.8Zm4.6 0H12v1.3h.1c.4-.7 1.4-1.6 2.9-1.6 3 0 3.5 1.9 3.5 4.5V19h-2.8v-4.3c0-1 0-2.4-1.5-2.4s-1.7 1.1-1.7 2.3V19H9.4V9.8Z"></path>
                  </svg>
                </a>
              </div>
              <form class="form-footer my-3" action="#" method="post">
                <input type="text" placeholder="Enter your email" name="search" aria-label="Email address">
                <input type="button" value="Go">
              </form>
              <p>Get product updates, implementation tips, and campus digitization insights from the Sortiq team.</p>
            </div>
          </div>
        </div>
      </div>
      <div class="footer-bottom">
        <div class="footer-bottom-inner">
          <p>&copy; <span data-year></span> Sortiq Solutions. All rights reserved.</p>
          <div class="footer-bottom-actions">
            <span class="footer-badge">Cloud Ready</span>
            <span class="footer-badge">Campus ERP</span>
            <a class="footer-chat" href="${prefix}contact.html" aria-label="Contact us">Talk to Us</a>
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
