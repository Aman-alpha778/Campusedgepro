const toggle = document.querySelector("[data-menu-toggle]");
const links = document.querySelector("[data-nav-links]");

if (toggle && links) {
  toggle.addEventListener("click", () => {
    const isOpen = links.classList.toggle("open");
    toggle.setAttribute("aria-expanded", String(isOpen));
  });
}

const normalizeNavLayout = () => {
  const path = window.location.pathname.replace(/\\/g, "/");
  const nested = ["/blog/", "/modules/", "/docs/"].some((segment) => path.includes(segment));
  const prefix = nested ? "../" : "";

  const desiredNavItems = [
    { href: `${prefix}index.html`, label: "Home" },
    { href: `${prefix}about.html`, label: "About Us" },
    { href: `${prefix}modules.html`, label: "Product Modules" },
    { href: `${prefix}features.html`, label: "Product Features" },
    { href: `${prefix}pricing.html`, label: "Pricing" },
    { href: `${prefix}blog.html`, label: "Blogs" },
    { href: "/docs", label: "Documentation" },
    { href: `${prefix}contact.html`, label: "Contact Us" },
  ];

  const matchesNavItem = (anchor, desiredItem) => {
    const href = (anchor.getAttribute("href") || "").trim().toLowerCase();
    const text = (anchor.textContent || "").trim().toLowerCase();

    switch (desiredItem.label) {
      case "Home":
        return text === "home" || text === "main website" || /(^|\/)index\.html$/.test(href);
      case "About Us":
        return text === "about us" || /(^|\/)about\.html$/.test(href);
      case "Product Modules":
        return text === "modules" || text === "product modules" || /(^|\/)modules\.html$/.test(href);
      case "Product Features":
        return text === "features" || text === "product features" || /(^|\/)features\.html$/.test(href);
      case "Pricing":
        return text === "pricing" || text === "prising" || /(^|\/)pricing\.html$/.test(href);
      case "Blogs":
        return text === "blog" || text === "blogs" || /(^|\/)blog\.html$/.test(href);
      case "Documentation":
        return (
          text.includes("documentation") ||
          text.includes("docs") ||
          /\/docs\//.test(href) ||
          (path.includes("/docs/") && href === "index.html")
        );
      case "Contact Us":
        return text === "contact" || text === "contact us" || /(^|\/)contact\.html$/.test(href);
      default:
        return false;
    }
  };

  document.querySelectorAll(".nav").forEach((nav) => {
    const navLinks = nav.querySelector("[data-nav-links]") || nav.querySelector(".nav-links");
    if (!navLinks) {
      return;
    }

    const navAnchors = Array.from(navLinks.querySelectorAll("a:not(.button)"));
    const claimedAnchors = new Set();
    const orderedAnchors = desiredNavItems.map((desiredItem) => {
      const existingAnchor = navAnchors.find((anchor) => {
        if (claimedAnchors.has(anchor)) {
          return false;
        }

        return matchesNavItem(anchor, desiredItem);
      });

      const nextAnchor = existingAnchor || document.createElement("a");
      claimedAnchors.add(nextAnchor);
      nextAnchor.setAttribute("href", desiredItem.href);
      nextAnchor.textContent = desiredItem.label;
      return nextAnchor;
    });

    navAnchors.forEach((anchor) => {
      if (!claimedAnchors.has(anchor)) {
        anchor.remove();
      }
    });

    orderedAnchors.forEach((anchor) => navLinks.appendChild(anchor));

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
            <img class="brand-logo footer-logo" src="/assets/logt.png" alt="CampusEdgePro">
          </a>
          <h3>CampusEdgePro</h3>
          <p>A modern college ERP for admissions, academics, attendance, fees, and reporting in one connected platform.</p>
        </div>
        <div class="footer-columns">
          <div class="footer-column">
            <strong>Services</strong>
            <a href="${prefix}features.html">Product Features</a>
            <a href="${prefix}modules.html">Product Modules</a>
            <a href="${prefix}pricing.html">Pricing</a>
            <a href="${prefix}contact.html">Help & Support</a>
          </div>
          <div class="footer-column">
            <strong>About</strong>
            <a href="${prefix}about.html">About Us</a>
            <a href="/docs">Documentation</a>
            <a href="${prefix}contact.html">Contact Us</a>
            <a href="${prefix}blog.html">Blogs</a>
          </div>
          <div class="footer-column footer-column-social">
            <strong>Social</strong>
            <div class="footer-social-icons" aria-label="Social links">
              <a class="footer-social-icon" href="https://www.facebook.com/SortiqSolutions/" target="_blank" rel="noreferrer" aria-label="Facebook">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M13.5 21v-7h2.4l.4-2.9h-2.8V9.2c0-.8.2-1.4 1.4-1.4H16V5.2c-.2 0-.9-.1-1.8-.1-1.8 0-3 1.1-3 3.2v1.8H9v2.9h2.4v7z" fill="currentColor"/></svg>
              </a>
              <a class="footer-social-icon" href="https://www.linkedin.com/company/sortiq-solutions/" target="_blank" rel="noreferrer" aria-label="LinkedIn">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6.9 8.4a1.7 1.7 0 1 1 0-3.4 1.7 1.7 0 0 1 0 3.4zM5.4 9.7h3v9.9h-3zm4.7 0h2.9V11c.4-.8 1.4-1.7 2.9-1.7 3.1 0 3.7 2 3.7 4.7v5.6h-3v-5c0-1.2 0-2.8-1.7-2.8s-2 1.3-2 2.7v5.1h-3z" fill="currentColor"/></svg>
              </a>
              <a class="footer-social-icon" href="https://www.instagram.com/sortiqsolutions/" target="_blank" rel="noreferrer" aria-label="Instagram">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7.75 2h8.5A5.75 5.75 0 0 1 22 7.75v8.5A5.75 5.75 0 0 1 16.25 22h-8.5A5.75 5.75 0 0 1 2 16.25v-8.5A5.75 5.75 0 0 1 7.75 2m0 1.8A3.95 3.95 0 0 0 3.8 7.75v8.5a3.95 3.95 0 0 0 3.95 3.95h8.5a3.95 3.95 0 0 0 3.95-3.95v-8.5a3.95 3.95 0 0 0-3.95-3.95zm8.95 1.35a1.1 1.1 0 1 1 0 2.2 1.1 1.1 0 0 1 0-2.2M12 6.85A5.15 5.15 0 1 1 6.85 12 5.16 5.16 0 0 1 12 6.85m0 1.8A3.35 3.35 0 1 0 15.35 12 3.35 3.35 0 0 0 12 8.65" fill="currentColor"/></svg>
              </a>
              <a class="footer-social-icon" href="https://www.youtube.com/@SortiqSolutions" target="_blank" rel="noreferrer" aria-label="YouTube">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21.6 7.2a2.9 2.9 0 0 0-2-2C17.8 4.7 12 4.7 12 4.7s-5.8 0-7.6.5a2.9 2.9 0 0 0-2 2C2 9 2 12 2 12s0 3 .4 4.8a2.9 2.9 0 0 0 2 2c1.8.5 7.6.5 7.6.5s5.8 0 7.6-.5a2.9 2.9 0 0 0 2-2c.4-1.8.4-4.8.4-4.8s0-3-.4-4.8zM9.7 15.1V8.9l5.4 3.1z" fill="currentColor"/></svg>
              </a>
              <a class="footer-social-icon" href="mailto:sortiqsolutions@gmail.com" aria-label="Email">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6.5h16a1 1 0 0 1 1 1v9a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-9a1 1 0 0 1 1-1zm0 1.8v.2l8 5.2 8-5.2v-.2zm16 7.4v-5.1l-7.5 4.9a1 1 0 0 1-1.1 0L4 10.6v5.1z" fill="currentColor"/></svg>
              </a>
            </div>
          </div>
        </div>
      </div>
      <div class="footer-bottom">
        <div class="footer-bottom-inner">
          <p>&copy; <span data-year></span>Sortiq Solutions Pvt. Ltd. All rights reserved.</p>
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

document.querySelectorAll("[data-counter-target]").forEach((counter) => {
  const target = Number(counter.dataset.counterTarget || 0);
  const suffix = counter.dataset.counterSuffix || "";
  const duration = 2200;
  let started = false;
  let lastValue = -1;

  const renderValue = (value) => {
    if (value === lastValue) {
      return;
    }

    lastValue = value;
    counter.textContent = `${value}${suffix}`;
  };

  const startCounter = () => {
    if (started) {
      return;
    }

    started = true;
    const startTime = performance.now();

    const tick = (now) => {
      const progress = Math.min((now - startTime) / duration, 1);
      const eased = 1 - Math.pow(1 - progress, 4);
      const value = Math.round(target * eased);

      renderValue(value);

      if (progress < 1) {
        window.requestAnimationFrame(tick);
      }
    };

    renderValue(0);
    window.requestAnimationFrame(tick);
  };

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) {
          return;
        }

        startCounter();
        observer.disconnect();
      });
    },
    { threshold: 0.35 }
  );

  observer.observe(counter);
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
