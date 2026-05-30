<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="192x192" href="/favicon-192.png">
    <link rel="apple-touch-icon" href="/favicon-192.png">
    <title>Secure Checkout | CampusEdgePro</title>
    <meta name="description" content="Secure payment gateway for CampusEdgePro subscription plans.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body class="checkout-page">
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

    <main class="checkout-main">
      <section class="section checkout-shell">
        <div class="checkout-intro">
          <p class="eyebrow">Secure Payment Gateway</p>
          <h1>Complete your CampusEdgePro subscription with confidence.</h1>
          <p class="lead">Encrypted billing, verified payment rails, and a clean institute-ready checkout experience for your selected plan.</p>
          <div class="checkout-trust-strip">
            <span>256-bit SSL secured</span>
            <span>Instant invoice issue</span>
            <span>Dedicated onboarding after payment</span>
          </div>
        </div>

        <div class="checkout-grid">
          <section class="checkout-payment-panel">
            <div class="checkout-panel-head">
              <div>
                <span class="checkout-kicker">Payment Details</span>
                <h2>Institution Billing</h2>
              </div>
              <div class="checkout-method-badges">
                <span>Visa</span>
                <span>Mastercard</span>
                <span>UPI</span>
              </div>
            </div>

            <form class="checkout-form">
              <div class="checkout-input-grid">
                <label class="field">
                  <span>Institute Name</span>
                  <input type="text" placeholder="Campus / college name">
                </label>
                <label class="field">
                  <span>Billing Email</span>
                  <input type="email" placeholder="accounts@college.edu">
                </label>
                <label class="field full">
                  <span>Cardholder Name</span>
                  <input type="text" placeholder="Authorized payer name">
                </label>
                <label class="field full">
                  <span>Card Number</span>
                  <input type="text" inputmode="numeric" placeholder="1234 5678 9012 3456">
                </label>
                <label class="field">
                  <span>Expiry Date</span>
                  <input type="text" inputmode="numeric" placeholder="MM / YY">
                </label>
                <label class="field">
                  <span>CVV</span>
                  <input type="text" inputmode="numeric" placeholder="123">
                </label>
                <label class="field full">
                  <span>GST / Tax ID</span>
                  <input type="text" placeholder="Optional for invoice">
                </label>
              </div>

              <div class="checkout-methods">
                <button type="button" class="checkout-method active">Credit or Debit Card</button>
                <button type="button" class="checkout-method">UPI Transfer</button>
                <button type="button" class="checkout-method">Net Banking</button>
              </div>

              <div class="checkout-pay-actions">
                <button class="button checkout-pay-button" type="button">Pay ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¹{{ $plan['price'] }} Securely</button>
                <p>Your payment is protected and your implementation team is notified immediately after confirmation.</p>
              </div>
            </form>
          </section>

          <aside class="checkout-summary-panel {{ $plan['accent'] }}">
            <div class="checkout-summary-top">
              <span class="checkout-summary-label">Selected Plan</span>
              <h2>{{ $plan['name'] }}</h2>
              <div class="checkout-price">ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¹{{ $plan['price'] }} <span>/ month</span></div>
              <p>{{ $plan['summary'] }}</p>
            </div>

            <div class="checkout-summary-card">
              <div class="checkout-summary-row">
                <span>Campus capacity</span>
                <strong>{{ $plan['students'] }}</strong>
              </div>
              <div class="checkout-summary-row">
                <span>Platform setup</span>
                <strong>Included</strong>
              </div>
              <div class="checkout-summary-row">
                <span>Onboarding window</span>
                <strong>7-14 business days</strong>
              </div>
            </div>

            <div class="checkout-feature-list">
              @foreach ($plan['features'] as $feature)
                <div class="checkout-feature-item">{{ $feature }}</div>
              @endforeach
            </div>

            <div class="checkout-plan-switcher">
              <span>Switch plan</span>
              <div class="checkout-plan-links">
                @foreach ($plans as $key => $switchPlan)
                  <a class="{{ $planKey === $key ? 'active' : '' }}" href="{{ route('checkout', ['plan' => $key]) }}">{{ $switchPlan['name'] }}</a>
                @endforeach
              </div>
            </div>

            <div class="checkout-security-note">
              <strong>Secure processing</strong>
              <p>Payment sessions are isolated, encrypted, and logged for institutional billing records.</p>
            </div>
          </aside>
        </div>
      </section>
    </main>

    <footer class="footer"></footer>
    
  </body>
</html>

