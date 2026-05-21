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
        <a class="brand" href="index.html"><img class="brand-logo" src="/assets/camplogo.png" alt="CampusEdgePro"></a>
        <button class="menu-toggle" data-menu-toggle aria-label="Open menu">Ã¢ËœÂ°</button>
        <div class="nav-links" data-nav-links>
          <a href="features.html">Features</a>
          <a href="modules.html">Modules</a>
          <a href="demo.html">Demo</a>
          <a href="pricing.html">Pricing</a>
          <a href="blog.html">Blog</a>
          <a href="about.html">About Us</a><a href="contact.html">Contact</a>
          <a href="/docs/index.html">Documentation</a>
          <a class="button" href="demo.html">Book Demo</a>
        </div>
      </nav>
    </header>
    <main class="contact-page">
      <section class="section contact-scene">
        <div class="contact-scene-inner">
          <div class="contact-scene-copy">
            <h1>You have questions,<br>We have answers</h1>
            <p class="lead">Discover the right CampusEdgePro setup for your institute. Thoughtfully designed workflows, practical guidance, and implementation support are ready for your team.</p>

            <div class="contact-scene-meta">
              <div class="contact-info-box">
                <h3>Location</h3>
                <p>Sortiq Solutions Pvt Ltd</p>
                <p>E-51, Second Floor , Phase - 8, Industrial Area,
                    S.A.S. Nagar, Mohali, Punjab 160071 </p>
                <small>Monday - Saturday | 10:00 - 19:00</small>
              </div>
              <div class="contact-info-box">
                <h3>Social Media</h3>
                <div class="contact-social-links">
                  <a href="https://www.linkedin.com/company/sortiqsolutions" target="_blank" rel="noreferrer" aria-label="LinkedIn">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M6.94 8.5H3.56V20h3.38zm.22-3.69A1.97 1.97 0 1 0 3.22 4.8a1.97 1.97 0 0 0 3.94 0M20.44 20h-3.38v-5.59c0-1.33-.03-3.05-1.86-3.05s-2.15 1.45-2.15 2.95V20H9.67V8.5h3.24v1.57h.05c.45-.85 1.55-1.74 3.19-1.74 3.41 0 4.04 2.24 4.04 5.16z"/></svg>
                    <span>LinkedIn</span>
                  </a>
                  <a href="https://www.facebook.com/SortiqSolutions" target="_blank" rel="noreferrer" aria-label="Facebook">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M13.5 21v-7h2.35l.35-2.73H13.5V9.53c0-.79.22-1.33 1.35-1.33h1.45V5.76c-.25-.03-1.1-.1-2.1-.1-2.08 0-3.5 1.27-3.5 3.6v2.01H8.35V14h2.35v7z"/></svg>
                    <span>Facebook</span>
                  </a>
                  <a href="https://www.instagram.com/" target="_blank" rel="noreferrer" aria-label="Instagram">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M7.75 2h8.5A5.75 5.75 0 0 1 22 7.75v8.5A5.75 5.75 0 0 1 16.25 22h-8.5A5.75 5.75 0 0 1 2 16.25v-8.5A5.75 5.75 0 0 1 7.75 2m0 1.8A3.95 3.95 0 0 0 3.8 7.75v8.5a3.95 3.95 0 0 0 3.95 3.95h8.5a3.95 3.95 0 0 0 3.95-3.95v-8.5a3.95 3.95 0 0 0-3.95-3.95zm8.95 1.35a1.1 1.1 0 1 1 0 2.2 1.1 1.1 0 0 1 0-2.2M12 6.85A5.15 5.15 0 1 1 6.85 12 5.16 5.16 0 0 1 12 6.85m0 1.8A3.35 3.35 0 1 0 15.35 12 3.35 3.35 0 0 0 12 8.65"/></svg>
                    <span>Instagram</span>
                  </a>
                </div>
              </div>
              <div class="contact-info-box">
                <h3>Email</h3>
                <p>sortiqsolutions@gmail.com</p>
              </div>
              <div class="contact-info-box">
                <h3>Contact</h3>
                <p>+91 9646522110</p>
              </div>
            </div>
          </div>

          <form class="contact-form-shell" action="{{ route('contact.submit', [], false) }}" method="post">
          @csrf
          <div class="contact-form-head">
            <h2>Tell us what you need</h2>
            <p>Our team is ready to assist you with every detail, big or small.</p>
            @if ($errors->any())
              <p>Please correct the highlighted details and submit again.</p>
            @endif
          </div>

          <div class="contact-form-grid">
            <label class="field"><span>First Name</span><input name="first_name" autocomplete="given-name" value="{{ old('first_name') }}" required></label>
            <label class="field"><span>Last Name</span><input name="last_name" autocomplete="family-name" value="{{ old('last_name') }}"></label>
            <label class="field"><span>Country</span><input name="country" autocomplete="country-name" value="{{ old('country') }}"></label>
            <label class="field"><span>Phone Number</span><input name="phone" type="tel" autocomplete="tel" value="{{ old('phone') }}" required></label>
            <label class="field full"><span>Email Address</span><input name="email" type="email" autocomplete="email" value="{{ old('email') }}" required></label>
          </div>

          <div class="contact-inquiry-type">
            <span>Type of Inquiry</span>
            <input type="hidden" name="inquiry_type" value="{{ old('inquiry_type', 'General') }}" data-inquiry-input>
            <div class="contact-pill-row">
              <button type="button" class="contact-pill {{ old('inquiry_type', 'General') === 'General' ? 'active' : '' }}" data-inquiry-option="General" aria-pressed="{{ old('inquiry_type', 'General') === 'General' ? 'true' : 'false' }}">General</button>
              <button type="button" class="contact-pill {{ old('inquiry_type') === 'Booking' ? 'active' : '' }}" data-inquiry-option="Booking" aria-pressed="{{ old('inquiry_type') === 'Booking' ? 'true' : 'false' }}">Booking</button>
              <button type="button" class="contact-pill {{ old('inquiry_type') === 'Pricing' ? 'active' : '' }}" data-inquiry-option="Pricing" aria-pressed="{{ old('inquiry_type') === 'Pricing' ? 'true' : 'false' }}">Pricing</button>
              <button type="button" class="contact-pill {{ old('inquiry_type') === 'Modules' ? 'active' : '' }}" data-inquiry-option="Modules" aria-pressed="{{ old('inquiry_type') === 'Modules' ? 'true' : 'false' }}">Modules</button>
              <button type="button" class="contact-pill {{ old('inquiry_type') === 'Others' ? 'active' : '' }}" data-inquiry-option="Others" aria-pressed="{{ old('inquiry_type') === 'Others' ? 'true' : 'false' }}">Others</button>
            </div>
          </div>

          <label class="field"><span>Message</span><textarea name="message" placeholder="Tell us about your institute and requirements">{{ old('message') }}</textarea></label>

          <label class="contact-check">
            <input type="checkbox" name="updates" value="1" {{ old('updates') ? 'checked' : '' }}>
            <span>I'd like to receive exclusive updates and product offers.</span>
          </label>

          <button class="contact-submit" type="submit">Submit</button>
          </form>
        </div>
      </section>
    </main>
    <footer class="footer"></footer>
    <script src="/assets/main.js"></script>
  </body>
</html>


