<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="192x192" href="/favicon-192.png">
    <link rel="apple-touch-icon" href="/favicon-192.png">
    <title>Pricing | CampusEdgePro</title>
    <meta name="description" content="CampusEdgePro pricing plans for colleges: Basic, Pro and Enterprise with custom quotes.">
    <link rel="stylesheet" href="/assets/styles.css">
  </head>
  <body class="pricing-page">
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
      <a href="/docs/index.html">Documentation</a>
      <a href="contact.html">Contact Us</a>
      <a class="button" href="demo.html">Book Demo</a>
    </div>
  </nav>
</header>

    <main>
      <section class="band pricing-band">
        <div class="section pricing-shell">
          <div class="pricing-hero">
            <p class="eyebrow">Pricing</p>
            <h1>Choose the right plan for your institute.</h1>
            <p class="lead">Three clear options for colleges that want dependable implementation, cleaner operations, and room to grow.</p>
          </div>

          <div class="pricing-intro">
            <h2>Start growing today</h2>
            <p>Choose a plan that fits your campus needs and gives your team the right level of rollout support, operational depth, and reporting power.</p>
          </div>

          <div class="grid three pricing-grid">
            <article class="card pricing-card pricing-basic">
              <div class="pricing-tier">Basic</div>
              <div class="price">&#8377;25,000 <span>/ month</span></div>
              <p>For colleges getting started with digital administration, attendance, and essential student records.</p>
              <a class="button secondary" href="{{ route('checkout', ['plan' => 'basic']) }}">Choose this plan</a>
              <ul class="check-list">
                <li>Student records</li>
                <li>Attendance tracking</li>
                <li>Basic fee reports</li>
                <li>Email support</li>
                <li>Up to 1,000 students</li>
                <li>Standard onboarding</li>
              </ul>
            </article>

            <article class="card pricing-card pricing-featured">
              <span class="pricing-badge">Best Value</span>
              <div class="pricing-tier">Professional</div>
              <div class="price">&#8377;50,000 <span>/ month</span></div>
              <p>For growing institutes that need connected academics, finance, CRM, and stronger management visibility.</p>
              <a class="button" href="{{ route('checkout', ['plan' => 'professional']) }}">Choose this plan</a>
              <ul class="check-list">
                <li>All Basic features</li>
                <li>Admissions CRM</li>
                <li>Exams and results</li>
                <li>Advanced reports</li>
                <li>Up to 3,000 students</li>
                <li>Priority onboarding</li>
              </ul>
            </article>

            <article class="card pricing-card pricing-enterprise">
              <div class="pricing-tier">Enterprise</div>
              <div class="price">&#8377;90,000 <span>/ month</span></div>
              <p>For multi-campus colleges needing custom workflows, integrations, API access, and priority success support.</p>
              <a class="button secondary" href="{{ route('checkout', ['plan' => 'enterprise']) }}">Choose this plan</a>
              <ul class="check-list">
                <li>All Professional features</li>
                <li>API access</li>
                <li>Custom roles</li>
                <li>Priority implementation</li>
                <li>Multi-campus support</li>
                <li>Dedicated success manager</li>
              </ul>
            </article>
          </div>
        </div>
      </section>
    </main>

    <footer class="footer"></footer>
    <script src="/assets/main.js"></script>
  </body>
</html>

