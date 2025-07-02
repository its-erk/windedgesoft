<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Home | Windedgesoft</title>

  <!-- Favicon -->
  <link rel="icon" href="assets/img/favicon_io/favicon.ico" type="image/x-icon">

  <!-- Bootstrap 5.3.7 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">

  <!-- FontAwesome (locally hosted) -->
  <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">

  <!-- Timeline CSS (custom — make sure it's Bootstrap 5 friendly) -->
  <link rel="stylesheet" href="plugins/how-we-work-timeline/css/style.css">

  <!-- Custom Theme -->
  <link rel="stylesheet" href="assets/dist/css/windedgesoft.css">
</head>

<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm fixed-top" id="mainNav">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="assets/img/logo_icon.png" alt="Logo" width="30" height="30" class="me-2">
      <span class="fw-semibold">Windedgesoft</span>
    </a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="about.php">About</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Peek
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="wBlog/">wBlog</a></li>
            <li><a class="dropdown-item" href="#">Events</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">a Game?</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Contact Us</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Hero -->
<section class="hero vh-100 d-flex align-items-center justify-content-center text-center bg-dark text-light">
  <div>
    <h1 class="display-4 fw-bold">We Code Like It’s Magic</h1>
    <p class="lead">Websites, Systems & Apps - Built smart, shipped fast, and just a little smug about it;</p>

    <a href="#services" class="btn btn-primary btn-lg mt-3 rounded-pill px-4">Explore Services</a>
  </div>
</section>
<!-- /. hero -->

<!-- Services -->
<section id="services" class="py-5 text-center">
  <div class="container">
    <h2 class="fw-bold mb-4">What We Build (And Why You'll Love It)</h2>
    <p class="text-muted mb-5">We specialize in crafting efficient, good-looking, and dangerously addictive digital tools.</p>

    <div class="row g-4">
      <div class="col-md-4">
        <div class="card h-100 shadow-sm fancy-card">
          <div class="card-body text-center">
            <i class="fas fa-window-restore fa-2x text-primary mb-3"></i>
            <h5 class="card-title">Web Applications</h5>
            <p class="card-text">Custom platforms, portals, and dashboards - designed to make your business smarter, not harder.</p>
          </div>
        </div>

      </div>

      <div class="col-md-4">
        <div class="card h-100 shadow-sm fancy-card">
          <div class="card-body">
            <i class="fas fa-mobile-screen-button fa-2x text-primary mb-3"></i>
            <h5 class="card-title">Mobile Systems</h5>
            <p class="card-text">From MVPs to production-ready apps, we put your brand in your user's pocket (and keep it there).</p>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card h-100 shadow-sm fancy-card">
          <div class="card-body">
            <i class="fas fa-server fa-2x text-primary mb-3"></i>
            <h5 class="card-title">Backend & APIs</h5>
            <p class="card-text">The invisible muscle. Fast, scalable, and solid - whether it’s for your app or other devs.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- How we work -->
<section class="py-5 bg-light text-center" id="how-we-work">
  <div class="container">
    <h2 class="fw-bold mb-4">How We Work</h2>
    <p class="text-muted mb-5">We turn your idea into a fully functioning solution with minimal fuss and maximum polish.</p>

    <div class="timeline position-relative">

      <!-- Step 1 -->
      <div class="row timeline-item">
        <div class="col-md-6 text-md-end text-center">
          <div class="timeline-content p-3 rounded bg-white shadow-sm d-inline-block">
            <h5>1. Discover</h5>
            <p>We listen, ask smart questions, and get to know your goals. No buzzwords. Just clarity.</p>
          </div>
        </div>
        <div class="col-md-6 position-relative text-center">
          <span class="timeline-icon">
            <i class="fas fa-lightbulb"></i>
          </span>
        </div>
      </div>

      <!-- Step 2 -->
      <div class="row timeline-item flex-md-row-reverse">
        <div class="col-md-6 text-md-start text-center">
          <div class="timeline-content p-3 rounded bg-white shadow-sm d-inline-block">
            <h5>2. Design</h5>
            <p>We sketch wireframes, map user journeys, and draft slick interfaces you'll actually like using.</p>
          </div>
        </div>
        <div class="col-md-6 position-relative text-center">
          <span class="timeline-icon">
            <i class="fas fa-pencil-ruler"></i>
          </span>
        </div>
      </div>

      <!-- Step 3 -->
      <div class="row timeline-item">
        <div class="col-md-6 text-md-end text-center">
          <div class="timeline-content p-3 rounded bg-white shadow-sm d-inline-block">
            <h5>3. Develop</h5>
            <p>Here comes the code — clean, secure, and built to scale. We keep you in the loop as we ship fast.</p>
          </div>
        </div>
        <div class="col-md-6 position-relative text-center">
          <span class="timeline-icon">
            <i class="fas fa-code"></i>
          </span>
        </div>
      </div>

      <!-- Step 4 -->
      <div class="row timeline-item flex-md-row-reverse">
        <div class="col-md-6 text-md-start text-center">
          <div class="timeline-content p-3 rounded bg-white shadow-sm d-inline-block">
            <h5>4. Deliver</h5>
            <p>Launch time. We test, deploy, and support. Your users are happy — and so are you.</p>
          </div>
        </div>
        <div class="col-md-6 position-relative text-center">
          <span class="timeline-icon">
            <i class="fas fa-rocket"></i>
          </span>
        </div>
      </div>

      <!-- Center Line -->
      <div class="timeline-line d-none d-md-block"></div>
    </div>
  </div>
</section>


<!-- Testimonials -->
<section class="py-5 text-center">
  <div class="container">
    <h2 class="fw-bold mb-4">What Our Clients Say</h2>
    <p class="text-muted mb-5">A few words from the people who trust us with their tech.</p>

    <div class="row g-4">
      <div class="col-md-4">
        <div class="card h-100 bg-light border-0">
          <div class="card-body">
            <i class="fas fa-user-alt fa-3x text-muted"></i>
            <p class="card-text">“Working with Windedgesoft felt like having an in-house team. Fast, focused, and full of smart ideas.”</p>
            <h6 class="card-title mt-3 mb-0">Brian M., Operations Lead</h6>
            <p class="text-muted small">Nairobi Retail Systems</p>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card h-100 bg-light border-0">
          <div class="card-body">
            <i class="fas fa-user-alt fa-3x text-muted"></i>
            <p class="card-text">“They took our half-baked concept and turned it into a beautiful, working platform. Clients love it.”</p>
            <h6 class="card-title mt-3 mb-0">Cynthia O., Co-Founder</h6>
            <p class="text-muted small">VibeTicket Africa</p>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card h-100 bg-light border-0">
          <div class="card-body">
            <i class="fas fa-user-alt fa-3x text-muted"></i>
            <p class="card-text">“Solid backend, clean UI, delivered on time. Can’t ask for more.”</p>
            <h6 class="card-title mt-3 mb-0">David K., CTO</h6>
            <p class="text-muted small">LogiStack Solutions</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- /.testimonials -->

<!-- Stuff We've Built -->
<section class="py-5 bg-light text-center" id="projects">
  <div class="container">
    <h2 class="fw-bold mb-4">Projects We’re Proud Of</h2>
    <p class="text-muted mb-5">Here’s a quick look at some of our recent work — designed with care, built to perform.</p>

    <div class="row g-4">
      <div class="col-md-4">
        <div class="card shadow-sm h-100">
          <img src="assets/img/cover.png" class="card-img-top" alt="Project 1">
          <div class="card-body text-start">
            <h5 class="card-title">ITS</h5>
            <p class="card-text">A web based ticketing system built for Intellitech Limited for computer and accessories work orders. Fast, scalable, and secure.</p>
          </div>
          <div class="card-footer bg-white d-flex border-0">
           <a href="#" class="btn btn-sm btn-outline-primary rounded-pill">See How It Works</a>
         </div>
       </div>
     </div>

     <div class="col-md-4">
      <div class="card shadow-sm h-100">
        <img src="assets/img/cover.png" class="card-img-top" alt="Project 2">
        <div class="card-body text-start">
          <h5 class="card-title">AviziTrack</h5>
          <p class="card-text">Custom business dashboard for <a href="https://aviziinvestment.co.ke" target="_blank">Avizi Investment</a>. Real-time reports, share, loans, and withdrawal insights. User-friendly UI. Built with PHP + Bootsrap.</p>
        </div>
        <div class="card-footer bg-white d-flex border-0">
          <a href="#" class="btn btn-sm btn-outline-primary rounded-pill">Explore Project</a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card shadow-sm h-100">
        <img src="assets/img/cover.png" class="card-img-top" alt="Project 3">
        <div class="card-body text-start">
          <h5 class="card-title">MyJibaba</h5>
          <p class="card-text">An upcoming online shop for Jibaba Solutions. Multi-platform web-based application to be built in Bootsrap, PHP, and MySQLi.</p>
        </div>
        <div class="card-footer bg-white d-flex border-0">
          <a href="#" class="btn btn-sm btn-outline-primary rounded-pill">Read Case Study</a>
        </div>
      </div>
    </div>
  </div>
</div>
</section>
<!-- /.stuff we've built -->

<section id="contact" class="py-5 text-center bg-white">
  <div class="container">
    <h2 class="fw-bold mb-4">Let’s Build Something Brilliant</h2>
    <p class="mb-4 text-muted">Got a project idea, startup dream, or just curious about how we can help? Drop us a line.</p>

    <div class="d-flex justify-content-center gap-3 flex-wrap">
      <a href="mailto:hello@windedgesoft.com" class="btn btn-outline-primary rounded-pill px-4"><i class="fas fa-envelope"></i> Email Us</a>
      <a href="https://wa.me/2547XXXXXXXX" class="btn btn-outline-success rounded-pill px-4" target="_blank"><i class="fab fa-whatsapp"></i> WhatsApp</a>
      <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#messageModal"><i class="fas fas fa-paper-plane"></i> Send a Quick Message</button>
    </div>
  </div>
</section>

<!-- Footer -->
<footer class="footer bg-black small text-center text-white-50">
  <div class="container px-4 px-lg-5 py-3">&copy; 2025 Windedgesoft</div>
</footer>
<!-- /.footer -->

<!-- Message Modal -->
<div class="modal fade" id="messageModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="messageModalLabel">Message Us</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div class="mb-3">
            <label for="messageName" class="form-label">Name</label>
            <input type="email" class="form-control" id="messageName" placeholder="e.g., John Doe">
          </div>
          <div class="mb-3">
            <label for="messageEmailAddress" class="form-label">Email address</label>
            <input type="email" class="form-control" id="messageEmailAddress" placeholder="name@example.com">
          </div>
          <div class="mb-3">
            <label for="messageContent" class="form-label">Message</label>
            <textarea class="form-control" id="messageContent" rows="3"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer d-flex justify-content-between">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary">Send</button>
      </div>
    </div>
  </div>
</div>
<!-- /.message modal -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
<!-- Windedgesoft.js -->
<script src="assets/dist/js/windedgesoft.js"></script>
</body>
</html>
