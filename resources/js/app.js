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
  const nested = ["/blog/", "/modules/", "/docs/", "/documentation/"].some((segment) => path.includes(segment));
  const prefix = nested ? "../" : "";

  const desiredNavItems = [
    { href: `${prefix}index.html`, label: "Home" },
    { href: `${prefix}about.html`, label: "About Us" },
    { href: `${prefix}modules.html`, label: "Product Modules" },
    { href: `${prefix}features.html`, label: "Product Features" },
    { href: `${prefix}pricing.html`, label: "Pricing" },
    { href: `${prefix}blog.html`, label: "Blogs" },
    { href: `${prefix}documentation.html`, label: "Documentation" },
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
          /\/documentation/.test(href) ||
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
  const nested = ["/blog/", "/modules/", "/docs/", "/documentation/"].some((segment) => path.includes(segment));
  const prefix = nested ? "../" : "";

  const footerMarkup = `
    <footer class="footer footer-reference">
      <div class="footer-inner footer-reference-inner">
        <div class="footer-brand-block">
          <a class="brand footer-brand" href="${prefix}index.html">
            <img class="brand-logo footer-logo" src="/assets/cmpus.png" alt="CampusEdgePro">
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
            <a href="${prefix}documentation.html">Documentation</a>
            <a href="${prefix}contact.html">Contact Us</a>
            <a href="${prefix}blog.html">Blogs</a>
          </div>
          <div class="footer-column footer-column-social">
            <strong>Social</strong>
            <div class="footer-social-icons" aria-label="Social links">
              <a class="footer-social-icon" href="https://www.facebook.com/SortiqSolutions" target="_blank" rel="noreferrer" aria-label="Facebook">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M13.5 21v-7h2.4l.4-2.9h-2.8V9.2c0-.8.2-1.4 1.4-1.4H16V5.2c-.2 0-.9-.1-1.8-.1-1.8 0-3 1.1-3 3.2v1.8H9v2.9h2.4v7z" fill="currentColor"/></svg>
              </a>
              <a class="footer-social-icon" href="https://twitter.com/SortiqSolutions" target="_blank" rel="noreferrer" aria-label="Twitter">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18.9 7.2c.8-.1 1.5-.5 2-.9-.3.8-.9 1.4-1.7 1.8.8 0 1.4-.2 2-.5-.5.7-1.1 1.3-1.8 1.7v.5c0 5.4-4.1 11.6-11.6 11.6-2.3 0-4.4-.7-6.2-1.8.3 0 .7.1 1 .1 1.9 0 3.6-.6 5-1.8-1.7 0-3.2-1.2-3.7-2.8.3 0 .5.1.8.1.4 0 .8-.1 1.1-.2-1.8-.4-3.2-2-3.2-4v-.1c.5.3 1.1.5 1.8.5-1.1-.7-1.8-1.9-1.8-3.3 0-.7.2-1.4.6-2 2 2.5 5 4.1 8.4 4.3-.1-.3-.1-.6-.1-1 0-2.3 1.9-4.1 4.1-4.1 1.2 0 2.2.5 3 1.3z" fill="currentColor"/></svg>
              </a>
              <a class="footer-social-icon" href="https://www.linkedin.com/company/sortiqsolutions" target="_blank" rel="noreferrer" aria-label="LinkedIn">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6.9 8.4a1.7 1.7 0 1 1 0-3.4 1.7 1.7 0 0 1 0 3.4zM5.4 9.7h3v9.9h-3zm4.7 0h2.9V11c.4-.8 1.4-1.7 2.9-1.7 3.1 0 3.7 2 3.7 4.7v5.6h-3v-5c0-1.2 0-2.8-1.7-2.8s-2 1.3-2 2.7v5.1h-3z" fill="currentColor"/></svg>
              </a>
              <a class="footer-social-icon" href="mailto:hello@sortiqsolutions.com" aria-label="Email">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6.5h16a1 1 0 0 1 1 1v9a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-9a1 1 0 0 1 1-1zm0 1.8v.2l8 5.2 8-5.2v-.2zm16 7.4v-5.1l-7.5 4.9a1 1 0 0 1-1.1 0L4 10.6v5.1z" fill="currentColor"/></svg>
              </a>
              <a class="footer-social-icon" href="https://www.youtube.com/" target="_blank" rel="noreferrer" aria-label="YouTube">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21.6 7.2a2.9 2.9 0 0 0-2-2C17.8 4.7 12 4.7 12 4.7s-5.8 0-7.6.5a2.9 2.9 0 0 0-2 2C2 9 2 12 2 12s0 3 .4 4.8a2.9 2.9 0 0 0 2 2c1.8.5 7.6.5 7.6.5s5.8 0 7.6-.5a2.9 2.9 0 0 0 2-2c.4-1.8.4-4.8.4-4.8s0-3-.4-4.8zM9.7 15.1V8.9l5.4 3.1z" fill="currentColor"/></svg>
              </a>
              <a class="footer-social-icon" href="https://in.pinterest.com/" target="_blank" rel="noreferrer" aria-label="Pinterest">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3C7 3 4 6.6 4 10.5c0 2.5 1.3 4.6 3.5 5.4.3.1.5 0 .5-.2.1-.2.3-1 .3-1.3 0-.2-.1-.3-.3-.5-.7-.8-1.2-1.9-1.2-3.4 0-2.8 2.1-5.4 5.9-5.4 3.2 0 5 2 5 4.7 0 3.5-1.6 6.5-3.8 6.5-1.3 0-2.2-1.1-1.9-2.3.4-1.5 1.1-3.2 1.1-4.3 0-1-.5-1.9-1.7-1.9-1.4 0-2.5 1.4-2.5 3.3 0 1.2.4 2 .4 2l-1.6 6.8c-.2.8 0 1.9.1 2.7.8.3 1.7.4 2.7.4 5 0 8.8-4.1 8.8-9.6C21 6.9 17.2 3 12 3z" fill="currentColor"/></svg>
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
