<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact | CampusEdgePro</title>
    <meta name="description" content="Contact Sortiq Solutions to request a CampusEdgePro demo, quote or free trial.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body>
    @if (session('contact_success'))
      <div class="site-notice is-visible" data-site-notice role="status" aria-live="polite">
        <div class="site-notice-card">
          <div class="site-notice-copy">
            <strong>Email Sent Successfully</strong>
            <p>{{ session('contact_success') }}</p>
          </div>
          <button type="button" class="site-notice-close" data-site-notice-close aria-label="Close notification">Ãƒâ€”</button>
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
          <button type="button" class="site-notice-close" data-site-notice-close aria-label="Close notification">Ãƒâ€”</button>
        </div>
      </div>
    @endif
    <header class="topbar">
      <nav class="nav">
        <a class="brand" href="index.html"><img class="brand-logo" src="/assets/cmplg.png" alt="CampusEdgePro"></a>
        <button class="menu-toggle" data-menu-toggle aria-label="Open menu">Ã¢ËœÂ°</button>
        <div class="nav-links" data-nav-links>
          <a href="features.html">Features</a>
          <a href="modules.html">Modules</a>
          <a href="demo.html">Demo</a>
          <a href="pricing.html">Pricing</a>
          <a href="blog.html">Blog</a>
          <a href="about.html">About Us</a><a href="contact.html">Contact</a>
          <a href="docs/index.html">Documentation</a>
          <a class="button" href="demo.html">Book Demo</a>
        </div>
      </nav>
    </header>
    <main class="contact-page">
      <section class="section contact-scene">
        <div class="contact-scene-heading">
          <h1>Get In Touch with Us!</h1>
          <p>We're here to assist</p>
          <span class="contact-scene-accent" aria-hidden="true"></span>
        </div>

        <div class="contact-scene-inner">
          <form class="contact-form-shell" action="{{ route('contact.submit', [], false) }}" method="post">
            @csrf
            <input type="hidden" name="last_name" value="{{ old('last_name') }}">
            <input type="hidden" name="country" value="{{ old('country') }}">
            <input type="hidden" name="inquiry_type" value="{{ old('inquiry_type', 'General') }}">

            @if ($errors->any())
              <p class="contact-form-note">Please correct the highlighted details and submit again.</p>
            @endif

            <div class="contact-form-grid">
              <label class="field">
                <span class="sr-only">Your Name</span>
                <input name="first_name" autocomplete="given-name" placeholder="Enter Your Name" value="{{ old('first_name') }}" required>
              </label>
              <label class="field">
                <span class="sr-only">Email</span>
                <input name="email" type="email" autocomplete="email" placeholder="Enter Your Email" value="{{ old('email') }}" required>
              </label>
              <label class="field">
                <span class="sr-only">Phone Number</span>
                <input name="phone" type="tel" autocomplete="tel" placeholder="Enter Your Phone Number" value="{{ old('phone') }}" required>
              </label>
              <label class="field">
                <span class="sr-only">Subject</span>
                <input name="subject" placeholder="Subject" value="{{ old('subject') }}">
              </label>
              <label class="field full">
                <span class="sr-only">Message</span>
                <textarea name="message" placeholder="Enter your message">{{ old('message') }}</textarea>
              </label>
              <label class="field full">
                <span class="sr-only">Institute or Organization</span>
                <input name="institute" autocomplete="organization" placeholder="Institute / Organization" value="{{ old('institute') }}">
              </label>
            </div>

            <label class="contact-check">
              <input type="checkbox" name="updates" value="1" {{ old('updates') ? 'checked' : '' }}>
              <span>I'd like to receive exclusive updates and product offers.</span>
            </label>

            <button class="contact-submit" type="submit">Send Message</button>
          </form>

          <div class="contact-details-panel" aria-label="Contact information">
            <div class="contact-info-stack">
              <div class="contact-info-row">
                <span class="contact-info-icon" aria-hidden="true">
                  <svg viewBox="0 0 24 24"><path d="M6.6 10.8c1.5 3 3.6 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1.1-.3 1.2.4 2.4.6 3.7.6.5 0 .8.3.8.8v3.5c0 .5-.3.8-.8.8C10.7 21 3 13.3 3 3.8 3 3.3 3.3 3 3.8 3h3.5c.5 0 .8.3.8.8 0 1.3.2 2.5.6 3.7.1.4 0 .8-.3 1.1z" fill="currentColor"/></svg>
                </span>
                <div>
                  <h3>CALL US</h3>
                  <p><a href="tel:+919646522110">+91 9646522110</a></p>
                </div>
              </div>

              <div class="contact-info-row">
                <span class="contact-info-icon" aria-hidden="true">
                  <svg viewBox="0 0 24 24"><path d="M4 6.5h16a1 1 0 0 1 1 1v9a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-9a1 1 0 0 1 1-1zm0 1.8v.2l8 5.2 8-5.2v-.2zm16 7.4v-5.1l-7.5 4.9a1 1 0 0 1-1.1 0L4 10.6v5.1z" fill="currentColor"/></svg>
                </span>
                <div>
                  <h3>WRITE US</h3>
                  
                  <p><a href="mailto:sortiqsolutions@gmail.com">sortiqsolutions@gmail.com</a></p>
                </div>
              </div>

              <div class="contact-info-row">
                <span class="contact-info-icon" aria-hidden="true">
                  <svg viewBox="0 0 24 24"><path d="M12 2a7 7 0 0 1 7 7c0 5.2-7 13-7 13S5 14.2 5 9a7 7 0 0 1 7-7zm0 9.5A2.5 2.5 0 1 0 12 6a2.5 2.5 0 0 0 0 5.5z" fill="currentColor"/></svg>
                </span>
                <div>
                  <h3>VISIT US</h3>
                  <p>E-51, Second Floor, Phase - 8, Industrial Area,</p>
                  <p>S.A.S. Nagar, Mohali, Punjab 160071</p>
                </div>
              </div>
            </div>

            <div class="contact-social-links contact-social-links-round" aria-label="Social links">
              <a href="https://www.facebook.com/SortiqSolutions/" target="_blank" rel="noreferrer" aria-label="Facebook">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M13.5 21v-7h2.35l.35-2.73H13.5V9.53c0-.79.22-1.33 1.35-1.33h1.45V5.76c-.25-.03-1.1-.1-2.1-.1-2.08 0-3.5 1.27-3.5 3.6v2.01H8.35V14h2.35v7z" fill="currentColor"/></svg>
              </a>
              <a href="https://www.linkedin.com/company/sortiq-solutions/" target="_blank" rel="noreferrer" aria-label="LinkedIn">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6.94 8.5H3.56V20h3.38zm.22-3.69A1.97 1.97 0 1 0 3.22 4.8a1.97 1.97 0 0 0 3.94 0M20.44 20h-3.38v-5.59c0-1.33-.03-3.05-1.86-3.05s-2.15 1.45-2.15 2.95V20H9.67V8.5h3.24v1.57h.05c.45-.85 1.55-1.74 3.19-1.74 3.41 0 4.04 2.24 4.04 5.16z" fill="currentColor"/></svg>
              </a>
              <a href="https://x.com/SortiqSolutions" target="_blank" rel="noreferrer" aria-label="Twitter">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18.9 7.2c.8-.1 1.5-.5 2-.9-.3.8-.9 1.4-1.7 1.8.8 0 1.4-.2 2-.5-.5.7-1.1 1.3-1.8 1.7v.5c0 5.4-4.1 11.6-11.6 11.6-2.3 0-4.4-.7-6.2-1.8.3 0 .7.1 1 .1 1.9 0 3.6-.6 5-1.8-1.7 0-3.2-1.2-3.7-2.8.3 0 .5.1.8.1.4 0 .8-.1 1.1-.2-1.8-.4-3.2-2-3.2-4v-.1c.5.3 1.1.5 1.8.5-1.1-.7-1.8-1.9-1.8-3.3 0-.7.2-1.4.6-2 2 2.5 5 4.1 8.4 4.3-.1-.3-.1-.6-.1-1 0-2.3 1.9-4.1 4.1-4.1 1.2 0 2.2.5 3 1.3z" fill="currentColor"/></svg>
              </a>
              <a href="https://www.instagram.com/sortiqsolutions/" target="_blank" rel="noreferrer" aria-label="Instagram">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7.75 2h8.5A5.75 5.75 0 0 1 22 7.75v8.5A5.75 5.75 0 0 1 16.25 22h-8.5A5.75 5.75 0 0 1 2 16.25v-8.5A5.75 5.75 0 0 1 7.75 2m0 1.8A3.95 3.95 0 0 0 3.8 7.75v8.5a3.95 3.95 0 0 0 3.95 3.95h8.5a3.95 3.95 0 0 0 3.95-3.95v-8.5a3.95 3.95 0 0 0-3.95-3.95zm8.95 1.35a1.1 1.1 0 1 1 0 2.2 1.1 1.1 0 0 1 0-2.2M12 6.85A5.15 5.15 0 1 1 6.85 12 5.16 5.16 0 0 1 12 6.85m0 1.8A3.35 3.35 0 1 0 15.35 12 3.35 3.35 0 0 0 12 8.65" fill="currentColor"/></svg>
              </a>
              <a href="https://www.youtube.com/@SortiqSolutions" target="_blank" rel="noreferrer" aria-label="YouTube">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21.6 7.2a2.9 2.9 0 0 0-2-2C17.8 4.7 12 4.7 12 4.7s-5.8 0-7.6.5a2.9 2.9 0 0 0-2 2C2 9 2 12 2 12s0 3 .4 4.8a2.9 2.9 0 0 0 2 2c1.8.5 7.6.5 7.6.5s5.8 0 7.6-.5a2.9 2.9 0 0 0 2-2c.4-1.8.4-4.8.4-4.8s0-3-.4-4.8zM9.7 15.1V8.9l5.4 3.1z" fill="currentColor"/></svg>
              </a>
              <a href="mailto:sortiqsolutions@gmail.com" aria-label="Email">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6.5h16a1 1 0 0 1 1 1v9a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-9a1 1 0 0 1 1-1zm0 1.8v.2l8 5.2 8-5.2v-.2zm16 7.4v-5.1l-7.5 4.9a1 1 0 0 1-1.1 0L4 10.6v5.1z" fill="currentColor"/></svg>
              </a>
            </div>
          </div>
        </div>
      </section>
    </main>
    <footer class="footer"></footer>
    
  </body>
</html>

