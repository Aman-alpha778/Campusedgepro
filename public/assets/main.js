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
      <div class="footer-subscribe-band">
        <form class="footer-subscribe-form" aria-label="Subscribe form">
          <input type="email" placeholder="Enter email address" aria-label="Email address">
          <button type="button">Subscribe</button>
        </form>
      </div>
      <div class="footer-inner footer-reference-inner">
        <div class="footer-brand-block">
          <a class="brand footer-brand" href="${prefix}index.html">
            <img class="brand-logo footer-logo" src="/assets/footer-logo.png" alt="CampusEdgePro">
          </a>
          <p class="footer-kicker">CampusEdgePro by Sortiq Solutions</p>
          <h3>CampusEdgePro</h3>
          <p>A modern college ERP for admissions, academics, attendance, fees, and reporting in one connected platform.</p>
          <a class="footer-readmore" href="${prefix}about.html">Read more</a>
        </div>
        <div class="footer-columns">
          <div class="footer-column">
            <strong>Discover</strong>
            <a href="${prefix}features.html">Features</a>
            <a href="${prefix}modules.html">Modules</a>
            <a href="${prefix}pricing.html">Pricing</a>
            <a href="${prefix}contact.html">Help & Support</a>
          </div>
          <div class="footer-column">
            <strong>About</strong>
            <a href="${prefix}about.html">About Us</a>
            <a href="${prefix}modules/staff-management.html">Team</a>
            <a href="${prefix}contact.html">Contact</a>
            <a href="${prefix}blog.html">Blog</a>
          </div>
          <div class="footer-column">
            <strong>Resources</strong>
            <a href="/docs/index.html">Documentation</a>
            <a href="/docs/faq.html">Help Center</a>
            <a href="${prefix}blog.html">Insights</a>
            <a href="${prefix}pricing.html">Plans</a>
          </div>
          <div class="footer-column">
            <strong>Social</strong>
            <a href="https://www.facebook.com/SortiqSolutions" target="_blank" rel="noreferrer">Facebook</a>
            <a href="https://twitter.com/SortiqSolutions" target="_blank" rel="noreferrer">Twitter</a>
            <a href="https://www.linkedin.com/company/sortiqsolutions" target="_blank" rel="noreferrer">LinkedIn</a>
            <a href="mailto:hello@sortiqsolutions.com">Email</a>
          </div>
        </div>
      </div>
      <div class="footer-bottom">
        <div class="footer-bottom-inner">
          <p>&copy; <span data-year></span> Sortiq Solutions Pvt. Ltd. All rights reserved.</p>
          <div class="footer-bottom-actions">
            <a href="/docs/index.html">Terms</a>
            <a href="/docs/faq.html">Privacy</a>
            <a href="${prefix}contact.html">Compliances</a>
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
