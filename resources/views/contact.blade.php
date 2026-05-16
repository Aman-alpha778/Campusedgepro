<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
          <button type="button" class="site-notice-close" data-site-notice-close aria-label="Close notification">×</button>
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
          <button type="button" class="site-notice-close" data-site-notice-close aria-label="Close notification">×</button>
        </div>
      </div>
    @endif
    <header class="topbar">
      <nav class="nav">
        <a class="brand" href="index.html"><span class="brand-mark">CE</span><span>CampusEdgePro</span></a>
        <button class="menu-toggle" data-menu-toggle aria-label="Open menu">☰</button>
        <div class="nav-links" data-nav-links>
          <a href="features.html">Features</a>
          <a href="modules.html">Modules</a>
          <a href="demo.html">Demo</a>
          <a href="pricing.html">Pricing</a>
          <a href="blog.html">Blog</a>
          <a href="contact.html">Contact</a>
          <a href="docs/index.html">Documentation</a>
          <a class="button" href="demo.html">Book Demo</a>
        </div>
      </nav>
    </header>
    <main class="contact-page">
      <section class="section contact-scene">
        <div class="contact-scene-inner">
          <div class="contact-scene-copy">
            <p class="eyebrow">Contact</p>
            <h1>You have questions,<br>We have answers</h1>
            <p class="lead">Discover the right CampusEdgePro setup for your institute. Thoughtfully designed workflows, practical guidance, and implementation support are ready for your team.</p>

            <div class="contact-scene-meta">
              <div class="contact-info-box">
                <h3>Location</h3>
                <p>Sortiq Solutions Pvt Ltd</p>
                <p>India</p>
                <small>Monday - Saturday | 09:00 - 18:00</small>
              </div>
              <div class="contact-info-box">
                <h3>Social Media</h3>
                <p>Instagram</p>
                <p>LinkedIn</p>
                <p>Facebook</p>
              </div>
              <div class="contact-info-box">
                <h3>Email</h3>
                <p>hello@sortiqsolutions.com</p>
              </div>
              <div class="contact-info-box">
                <h3>Contact</h3>
                <p>+91 90000 00000</p>
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
            <span>I’d like to receive exclusive updates and product offers.</span>
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

