<!doctype html>
<html lang="en">
  <head><meta charset="utf-8"><meta name="viewport" content="width=device-width,
   initial-scale=1"><link rel="icon" type="image/png" sizes="192x192" href="/favicon-192.png"><link rel="apple-touch-icon" href="/favicon-192.png"><title>Book Demo | CampusEdgePro</title><meta name="description" content="Book a live demo or explore CampusEdgePro screenshots for college ERP workflows.">@vite(['resources/css/app.css', 'resources/js/app.js'])</head>
  <body class="demo-page"><header class="topbar">
  <nav class="nav">
    <a class="brand" href="index.html"><img class="brand-logo" src="/assets/logt.png" alt="CampusEdgePro"></a>
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
    <main>
      <section class="band"><div class="section split demo-section">
        <section class="demo-video-card card">
          <div class="demo-video-head">
            <div>
            <p class="eyebrow">Product walkthrough</p>
            <h2>See the ERP in action.</h2>
            <p>Use this temporary overview preview to showcase the platform before the live guided session.</p>
          </div>
          <span class="demo-live-badge">Overview media</span>
        </div>
        <div class="video-box">
            <div class="video-box-inner demo-video-media">
              <img src="/assets/product-overview.gif" alt="CampusEdgePro product overview preview">
              <span class="video-play-chip">Product overview</span>
            </div>
          </div>
          <div class="demo-video-points">
            <span>Student management</span>
            <span>Admissions CRM</span>
            <span>Fees and reports</span>
          </div>
        </section>
        <form class="demo-form-card form" action="{{ route('demo-requests.store') }}" method="post">
          @csrf
          <div class="demo-form-head">
            <div>
              <h3>Book your live demo</h3>
              <p>Share your details and we tailor the walkthrough to your institutes workflow.</p>
            </div>
            <span class="demo-live-badge">Live session</span>
          </div>
          @if (session('demo_request_success'))
            <div style="margin: 0 24px 8px; padding: 12px 14px; background: #ecfdf3; color: #065f46; border-radius: 14px; border: 1px solid #a7f3d0;">
              {{ session('demo_request_success') }}
            </div>
          @endif
          @if ($errors->any())
            <div style="margin: 0 24px 8px; padding: 12px 14px; background: #fef2f2; color: #991b1b; border-radius: 14px; border: 1px solid #fecaca;">
              <ul style="margin: 0; padding-left: 18px;">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif
          <div class="demo-form-body">
            <label class="field">
              <span>Admin Name</span>
              <input name="admin_name" value="{{ old('admin_name') }}" required>
            </label>
            <label class="field">
              <span>Email</span>
              <input name="email" type="email" value="{{ old('email') }}" required>
            </label>
            <label class="field">
              <span>Phone</span>
              <input name="phone" value="{{ old('phone') }}" required>
            </label>
            <label class="field">
              <span>College Name</span>
              <input name="college_name" value="{{ old('college_name') }}" required>
            </label>
            <label class="field">
              <span>Student Strength</span>
              <input name="student_strength" value="{{ old('student_strength') }}" placeholder="e.g. 2500 students" required>
            </label>
            <label class="field">
              <span>Requirements</span>
              <textarea name="requirements" data-demo-requirements placeholder="Tell us the modules, workflows, or pain points you want covered in the demo.">{{ old('requirements') }}</textarea>
            </label>
            <label class="field">
              <span>Preferred focus area</span>
              <select onchange="this.form.querySelector('[data-demo-requirements]').value = this.value">
                <option value="">Select a focus area</option>
                <option value="Complete ERP demo covering admissions, academics, fees, faculty, and reports.">Complete ERP demo</option>
                <option value="Admissions CRM and lead conversion workflow review.">Admissions CRM</option>
                <option value="Fee management, collections, and dues tracking review.">Fee Management</option>
                <option value="Attendance workflow and class-level analytics review.">Attendance</option>
              </select>
            </label>
            <div class="demo-submit">
              <button class="button" type="submit">Book Live Demo</button>
            </div>
          </div>
        </form>
      </div>
    </section>

    <section class="section">
      <div class="section-head">
        <div>
          <p class="eyebrow">Screenshots</p>
          <h2>Preview the dashboard experience.</h2>
        </div>
      </div>
      <img src="/assets/hero-section-img2.png" alt="CampusEdgePro dashboard screenshot">
    </section>

  </main>

  <footer class="footer">
    <div class="footer-inner">
      <div>
        <a class="brand" href="index.html">
          <img class="brand-logo" src="/assets/logt.png" alt="CampusEdgePro">
        </a>
        <p>Smart College ERP by Sortiq Solutions.</p>
      </div>

      <div class="footer-links">
        <div><strong>Website</strong></div>

      </div>

    </div>

  </footer>

  

</body>
</html>
