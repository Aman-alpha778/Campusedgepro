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
            <img class="brand-logo" src="/assets/cmpus.png" alt="CampusEdgePro">
          </a>
          <span class="footer-kicker">Campus Management, Simplified</span>
          <h3>CampusEdgePro</h3>
          <p>CampusEdgePro helps institutions run admissions, academics, finance, and communication from one dependable ERP platform.</p>
          <div class="footer-highlights" aria-label="Platform highlights">
            <span>Admissions to alumni in one system</span>
            <span>Built for schools, colleges, and training institutes</span>
          </div>
          <div class="footer-cta-row">
            <a class="footer-primary-cta" href="${prefix}contact.html">Book a demo</a>
            <a class="footer-secondary-link" href="${prefix}docs/index.html">View documentation</a>
          </div>
          <div class="footer-socials" aria-label="Social links">
            <a href="https://www.facebook.com/SortiqSolutions" target="_blank" rel="noreferrer" aria-label="Facebook">Fb</a>
            <a href="https://twitter.com/SortiqSolutions" target="_blank" rel="noreferrer" aria-label="X">X</a>
            <a href="https://www.linkedin.com/company/sortiqsolutions" target="_blank" rel="noreferrer" aria-label="LinkedIn">In</a>
            <a href="mailto:sortiqsolutions@gmail.com" aria-label="Email">Mail</a>
          </div>
        </div>
        <div class="footer-columns">
          <div class="footer-column">
            <strong>Product</strong>
            <a href="${prefix}features.html">Features</a>
            <a href="${prefix}pricing.html">Pricing</a>
            <a href="${prefix}modules.html">Modules</a>
            <a href="${prefix}demo.html">Book Demo</a>
            <a href="${prefix}blog.html">Product Updates</a>
          </div>
          <div class="footer-column">
            <strong>Company</strong>
            <a href="${prefix}about.html">About Us</a>
            <a href="${prefix}blog.html">Blog</a>
            <a href="${prefix}contact.html">Contact</a>
            <a href="${prefix}ads.html">Campaigns</a>
          </div>
          <div class="footer-column">
            <strong>Resources</strong>
            <a href="${prefix}docs/index.html">Documentation</a>
            <a href="${prefix}docs/faq.html">Help Center</a>
            <a href="${prefix}docs/admin-guide.html">Admin Guide</a>
            <a href="${prefix}docs/api.html">API Reference</a>
          </div>
          <div class="footer-column footer-subscribe">
            <strong>Talk to our team</strong>
            <p>Need help choosing modules or planning rollout for your institution? We are happy to guide you.</p>
            <div class="footer-contact-list" aria-label="Contact information">
              <a href="mailto:sortiqsolutions@gmail.com">sortiqsolutions@gmail.com</a>
              <a href="${prefix}contact.html">Request a consultation</a>
              <a href="${prefix}demo.html">Schedule a live walkthrough</a>
            </div>
            <form class="footer-subscribe-form" action="#" method="post">
              <input type="email" placeholder="Email Address" aria-label="Email address">
              <button type="button">Subscribe</button>
            </form>
          </div>
        </div>
      </div>
      <div class="footer-bottom">
        <div class="footer-bottom-inner">
          <p>&copy; <span data-year></span> Sortiq Solutions. All rights reserved.</p>
          <div class="footer-bottom-actions">
            <span class="footer-badge">Cloud Ready</span>
            <span class="footer-badge">Role-Based Access</span>
            <a class="footer-chat" href="${prefix}contact.html" aria-label="Contact us">Talk</a>
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
