<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="192x192" href="/favicon-192.png">
    <link rel="apple-touch-icon" href="/favicon-192.png">
    <title>Contact | CampusEdgePro</title>
    <meta name="description" content="Contact Sortiq Solutions to request a CampusEdgePro demo, quote or free trial.">
    <link rel="stylesheet" href="/assets/styles.css">
  </head>
  <body>
    @if (session('contact_success'))
      <div class="site-notice is-visible" data-site-notice role="status" aria-live="polite">
        <div class="site-notice-card">
          <div class="site-notice-copy">
            <strong>Email Sent Successfully</strong>
            <p>{{ session('contact_success') }}</p>
          </div>
          <button type="button" class="site-notice-close" data-site-notice-close aria-label="Close notification">ÃƒÆ’Ã¢â‚¬â€</button>
        </div>
      </div>
    @endif
    @if (session('contact_error'))
      <div class="site-notice is-visible" data-site-notice role="alert" aria-live="assertive">
        <div class="site-notice-card">
          <div class="site-notice-copy">
            <strong>We Couldn't Send The Email</strong>
            <p>{{ session('contact_error') }}</p>
            @if (session('contact_error_details'))
              <p><strong>Mail Error:</strong> {{ session('contact_error_details') }}</p>
            @endif
          </div>
          <button type="button" class="site-notice-close" data-site-notice-close aria-label="Close notification">ÃƒÆ’Ã¢â‚¬â€</button>
        </div>
      </div>
    @endif
    <header class="topbar">
  <nav class="nav">
    <a class="brand" href="index.html"><img class="brand-logo" src="/assets/camplogo.png" alt="CampusEdgePro"></a>
    <button class="menu-toggle" data-menu-toggle aria-label="Open menu">&#9776;</button>
    <div class="nav-links" data-nav-links>
      <a href="index.html">Home</a>
      <a href="about.html">About Us</a>
      <a href="modules.html">Product Modules</a>
      <a href="features.html">Product Features</a>
      <a href="pricing.html">Pricing</a>
      <a href="blog.html">Blogs</a>
      <a href="/docs">Documentation</a>
      <a href="contact.html">Contact Us</a>
      <a class="button" href="demo.html">Book Demo</a>
    </div>
  </nav>
    </header>
    <main class="contact-page">
      <section class="section contact-scene">
        <div class="contact-scene-heading">
          <h1>Get In Touch with Us!</h1>
          <p>We're here to assist</p>
          <span class="contact-scene-accent"></span>
        </div>

        <div class="contact-scene-inner">
          <form class="contact-form-shell" action="{{ route('contact.submit', [], false) }}" method="post">
            @csrf
            <input type="hidden" name="last_name" value="{{ old('last_name') }}">
            <input type="hidden" name="country" value="{{ old('country') }}">
            <input type="hidden" name="inquiry_type" value="{{ old('inquiry_type', 'General') }}">
            <div class="contact-form-grid contact-form-grid-visual">
              <label class="field">
                <span class="sr-only">Enter Your Name</span>
                <input name="first_name" autocomplete="name" placeholder="Enter Your Name" value="{{ old('first_name') }}" required>
              </label>
              <label class="field">
                <span class="sr-only">Enter Your Email</span>
                <input name="email" type="email" autocomplete="email" placeholder="Enter Your Email" value="{{ old('email') }}" required>
              </label>
              <label class="field">
                <span class="sr-only">Enter Your Phone Number</span>
                <input name="phone" type="tel" autocomplete="tel" placeholder="Enter Your Phone Number" value="{{ old('phone') }}" required>
              </label>
              <label class="field">
                <span class="sr-only">Subject</span>
                <input name="subject" placeholder="Subject" value="{{ old('subject') }}">
              </label>
              <label class="field full">
                <span class="sr-only">Enter your message</span>
                <textarea name="message" placeholder="Enter your message">{{ old('message') }}</textarea>
              </label>
              <label class="field full">
                <span class="sr-only">Institute</span>
                <input name="institute" placeholder="Institute / Organization" value="{{ old('institute') }}">
              </label>
            </div>

            @if ($errors->any())
              <p class="contact-form-note">Please correct the highlighted details and submit again.</p>
            @endif

            <label class="contact-check">
              <input type="checkbox" name="updates" value="1" {{ old('updates') ? 'checked' : '' }}>
              <span>I'd like to receive exclusive updates and product offers.</span>
            </label>

            <button class="contact-submit" type="submit">Send Message</button>
          </form>

          <aside class="contact-scene-copy">
            <div class="contact-info-stack">
              <article class="contact-info-row">
                <span class="contact-info-icon" aria-hidden="true">
                  <svg viewBox="0 0 24 24"><path fill="currentColor" d="M6.62 10.79a15.05 15.05 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1-.24 11.2 11.2 0 0 0 3.52.56 1 1 0 0 1 1 1V20a1 1 0 0 1-1 1A17 17 0 0 1 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1 11.2 11.2 0 0 0 .56 3.52 1 1 0 0 1-.24 1z"/></svg>
                </span>
                <div>
                  <h3>CALL US</h3>
                  <p>+91 9646522110</p>
                </div>
              </article>
              <article class="contact-info-row">
                <span class="contact-info-icon" aria-hidden="true">
                  <svg viewBox="0 0 24 24"><path fill="currentColor" d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2m0 4-8 5L4 8V6l8 5 8-5z"/></svg>
                </span>
                <div>
                  <h3>WRITE US</h3>
                  <p>info@sortiqsolutions.com</p>
                  <p>sortiqsolutions@gmail.com</p>
                </div>
              </article>
              <article class="contact-info-row">
                <span class="contact-info-icon" aria-hidden="true">
                  <svg viewBox="0 0 24 24"><path fill="currentColor" d="M12 2a7 7 0 0 0-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 0 0-7-7m0 9.5A2.5 2.5 0 1 1 14.5 9 2.5 2.5 0 0 1 12 11.5"/></svg>
                </span>
                <div>
                  <h3>VISIT US</h3>
                  <p>E-51, Second Floor, Phase - 8, Industrial Area,</p>
                  <p>S.A.S. Nagar, Mohali, Punjab 160071</p>
                </div>
              </article>
            </div>

            <div class="contact-social-links contact-social-links-round">
              <a href="https://www.facebook.com/SortiqSolutions" target="_blank" rel="noreferrer" aria-label="Facebook">
                <svg viewBox="0 0 24 24"><path fill="currentColor" d="M13.5 21v-7h2.35l.35-2.73H13.5V9.53c0-.79.22-1.33 1.35-1.33h1.45V5.76c-.25-.03-1.1-.1-2.1-.1-2.08 0-3.5 1.27-3.5 3.6v2.01H8.35V14h2.35v7z"/></svg>
              </a>
              <a href="https://www.linkedin.com/company/sortiqsolutions" target="_blank" rel="noreferrer" aria-label="LinkedIn">
                <svg viewBox="0 0 24 24"><path fill="currentColor" d="M6.94 8.5H3.56V20h3.38zm.22-3.69A1.97 1.97 0 1 0 3.22 4.8a1.97 1.97 0 0 0 3.94 0M20.44 20h-3.38v-5.59c0-1.33-.03-3.05-1.86-3.05s-2.15 1.45-2.15 2.95V20H9.67V8.5h3.24v1.57h.05c.45-.85 1.55-1.74 3.19-1.74 3.41 0 4.04 2.24 4.04 5.16z"/></svg>
              </a>
              <a href="https://www.instagram.com/" target="_blank" rel="noreferrer" aria-label="Instagram">
                <svg viewBox="0 0 24 24"><path fill="currentColor" d="M7.75 2h8.5A5.75 5.75 0 0 1 22 7.75v8.5A5.75 5.75 0 0 1 16.25 22h-8.5A5.75 5.75 0 0 1 2 16.25v-8.5A5.75 5.75 0 0 1 7.75 2m0 1.8A3.95 3.95 0 0 0 3.8 7.75v8.5a3.95 3.95 0 0 0 3.95 3.95h8.5a3.95 3.95 0 0 0 3.95-3.95v-8.5a3.95 3.95 0 0 0-3.95-3.95zm8.95 1.35a1.1 1.1 0 1 1 0 2.2 1.1 1.1 0 0 1 0-2.2M12 6.85A5.15 5.15 0 1 1 6.85 12 5.16 5.16 0 0 1 12 6.85m0 1.8A3.35 3.35 0 1 0 15.35 12 3.35 3.35 0 0 0 12 8.65"/></svg>
              </a>
              <a href="https://www.youtube.com/" target="_blank" rel="noreferrer" aria-label="YouTube">
                <svg viewBox="0 0 24 24"><path fill="currentColor" d="M21.6 7.2a2.9 2.9 0 0 0-2-2C17.8 4.7 12 4.7 12 4.7s-5.8 0-7.6.5a2.9 2.9 0 0 0-2 2C2 9 2 12 2 12s0 3 .4 4.8a2.9 2.9 0 0 0 2 2c1.8.5 7.6.5 7.6.5s5.8 0 7.6-.5a2.9 2.9 0 0 0 2-2c.4-1.8.4-4.8.4-4.8s0-3-.4-4.8zM9.7 15.1V8.9l5.4 3.1z"/></svg>
              </a>
              <a href="mailto:info@sortiqsolutions.com" aria-label="Email">
                <svg viewBox="0 0 24 24"><path fill="currentColor" d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2m0 4-8 5L4 8V6l8 5 8-5z"/></svg>
              </a>
            </div>
          </aside>
        </div>
      </section>
    </main>
    <footer class="footer"></footer>
    <script src="/assets/main.js"></script>
  </body>
</html>



