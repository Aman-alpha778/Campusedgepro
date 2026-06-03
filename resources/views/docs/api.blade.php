<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="192x192" href="/favicon-192.png">
    <link rel="apple-touch-icon" href="/favicon-192.png">
    <title>API Docs | CampusEdgePro</title>
    <meta name="description" content="CampusEdgePro API documentation for authentication, endpoints and integration guidance.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
  <body class="docs-page">
    <header class="topbar docs-topbar">
  <nav class="nav">
    <a class="brand" href="../index.html">
      <img class="brand-logo" src="/assets/sortiqsolutions-logo.png" alt="Sortiq Solutions">
    </a>
    <button class="menu-toggle" data-menu-toggle aria-label="Open menu">&#9776;</button>
    <div class="nav-links" data-nav-links>
      <a href="../index.html">Home</a>
      <a href="../about.html">About Us</a>
      <a href="../modules.html">Product Modules</a>
      <a href="../features.html">Product Features</a>
      <a href="../pricing.html">Pricing</a>
      <a href="../blog.html">Blogs</a>
      <a href="/documentation.html">Documentation</a>
      <a href="../contact.html">Contact Us</a>
      <a class="button" href="../demo.html">Book Demo</a>
    </div>
  </nav>
</header>

    <main>
      <section class="docs-page-hero">
        <div class="docs-container docs-page-hero-shell">
          <div class="docs-page-hero-copy">
            <span class="docs-kicker">API Docs</span>
            <h1>Secure integrations for admissions, payments and reporting.</h1>
            <p class="lead">Use CampusEdgePro APIs to connect portals, websites, payment gateways and analytics systems with role-scoped tokens and clear endpoint structure.</p>
            <div class="docs-page-meta">
              <span class="docs-pill">REST endpoints</span>
              <span class="docs-pill">Token based authentication</span>
              <span class="docs-pill">Integration guidance</span>
            </div>
          </div>
          <div class="docs-page-hero-art">
            <img src="/assets/hero-section-img2.png" alt="API documentation hero image">
          </div>
        </div>
      </section>

      <section class="docs-section">
        <div class="docs-container docs-content-layout">
          <aside class="docs-anchor-nav">
            <h3>On this page</h3>
            <a href="#auth">Authentication</a>
            <a href="#endpoints">Endpoints</a>
            <a href="#notes">Integration notes</a>
          </aside>

          <div class="docs-article">
            <article id="auth" class="docs-code-card">
              <span class="docs-kicker">Authentication</span>
              <h2>Authorize every request</h2>
              <p>All API traffic should include a secure bearer token issued to an approved institute or integration partner.</p>
              <pre>Authorization: Bearer YOUR_API_TOKEN</pre>
            </article>

            <article id="endpoints" class="docs-code-card">
              <span class="docs-kicker">Endpoints</span>
              <h2>Core API routes</h2>
              <pre>GET  /api/v1/students
POST /api/v1/students
GET  /api/v1/attendance
POST /api/v1/fees/payments
GET  /api/v1/reports/summary
POST /api/v1/admissions/leads</pre>
            </article>

            <article id="notes" class="docs-list-card">
              <span class="docs-card-tag">Integration Notes</span>
              <h3>Recommended implementation practices</h3>
              <ul>
                <li>Use role-scoped tokens instead of shared global credentials</li>
                <li>Validate institute and batch identifiers before creating records</li>
                <li>Separate production and test integrations clearly</li>
                <li>Log integration failures for finance and admissions workflows</li>
              </ul>
            </article>
          </div>
        </div>
      </section>
    </main>

    
  </body>
</html>


