function toggleFaq(el) {
  const item = el.closest('.faq-item');
  const isOpen = item.classList.contains('open');
  document.querySelectorAll('.faq-item').forEach(i => {
    i.classList.remove('open');
    i.querySelector('.faq-q').setAttribute('aria-expanded', 'false');
  });
  if (!isOpen) {
    item.classList.add('open');
    el.setAttribute('aria-expanded', 'true');
  }
}

document.querySelectorAll('.faq-q').forEach(q => {
  q.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' || e.key === ' ') {
      e.preventDefault();
      toggleFaq(this);
    }
  });
});

function handleSubmit(e) {
  e.preventDefault();
  const btn = e.target.querySelector('.form-submit');
  btn.textContent = '\u2713 Request Sent! We\'ll call you shortly.';
  btn.style.background = '#2D6A4F';
  btn.disabled = true;
}

function showMobileForm() {
  const mf = document.getElementById('mobile-enquire');
  mf.style.display = 'block';
  setTimeout(() => {
    mf.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }, 100);
}

window.addEventListener('scroll', function () {
  const sticky = document.getElementById('sticky-mob');
  if (window.innerWidth <= 900) {
    sticky.style.display = window.scrollY > 200 ? 'flex' : 'none';
  }
});

if (window.innerWidth <= 900) {
  document.getElementById('sticky-mob').style.display = 'none';
}

// HAMBURGER TOGGLE
document.addEventListener('DOMContentLoaded', function () {
  const hamburger = document.getElementById('hamburger');
  const navLinks = document.getElementById('nav-links');

  if (hamburger && navLinks) {
    hamburger.addEventListener('click', function () {
      this.classList.toggle('open');
      navLinks.classList.toggle('open');
    });

    document.addEventListener('click', function (e) {
      if (!hamburger.contains(e.target) && !navLinks.contains(e.target)) {
        navLinks.classList.remove('open');
      }
    });
  }

  // STICKY HEADER
  const header = document.querySelector('.site-header');
  const topBar = document.querySelector('.top-bar');

  function updateSticky() {
    if (topBar) {
      const topBarBottom = topBar.getBoundingClientRect().bottom;
      header.classList.toggle('scrolled', topBarBottom <= 0);
    }
  }

  window.addEventListener('scroll', updateSticky, { passive: true });
  updateSticky();

  // SCROLL SPY
  const sections = document.querySelectorAll('section[id]');
  const navAnchors = document.querySelectorAll('.nav-links a');

  function updateActiveLink() {
    let current = '';
    const scrollPos = window.scrollY + 120;

    sections.forEach(section => {
      const sectionTop = section.offsetTop;
      const sectionBottom = sectionTop + section.offsetHeight;

      if (scrollPos >= sectionTop && scrollPos < sectionBottom) {
        current = section.getAttribute('id');
      }
    });

    navAnchors.forEach(anchor => {
      anchor.classList.toggle('active', anchor.getAttribute('href') === '#' + current);
    });
  }

  window.addEventListener('scroll', updateActiveLink, { passive: true });
  updateActiveLink();

  // COUNTER ANIMATION
  const counters = document.querySelectorAll('.count-num');
  if (counters.length) {
    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const el = entry.target;
          const target = parseFloat(el.dataset.target);
          const decimals = el.dataset.decimals !== undefined ? parseInt(el.dataset.decimals) : (target % 1 === 0 ? 0 : 2);
          const suffix = el.dataset.suffix || '';
          const duration = 2000;
          const start = performance.now();

          function animate(now) {
            const elapsed = now - start;
            const progress = Math.min(elapsed / duration, 1);
            const current = progress * target;
            el.textContent = current.toFixed(decimals) + suffix;
            if (progress < 1) {
              requestAnimationFrame(animate);
            }
          }

          requestAnimationFrame(animate);
          observer.unobserve(el);
        }
      });
    }, { threshold: 0.5 });

    counters.forEach(c => observer.observe(c));
  }
});
