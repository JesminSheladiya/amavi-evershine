function toggleFaq(el) {
  const item = el.closest('.faq-item');
  const wrap = item.querySelector('.faq-a-wrap');
  const isOpen = item.classList.contains('open');

  document.querySelectorAll('.faq-item.open').forEach(i => {
    if (i !== item) {
      i.classList.remove('open');
      i.querySelector('.faq-a-wrap').style.maxHeight = '0';
      i.querySelector('.faq-q').setAttribute('aria-expanded', 'false');
    }
  });

  if (isOpen) {
    item.classList.remove('open');
    wrap.style.maxHeight = '0';
    el.setAttribute('aria-expanded', 'false');
  } else {
    item.classList.add('open');
    wrap.style.maxHeight = wrap.scrollHeight + 'px';
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

  // IMAGE SLIDERS (scroll-snap)
  document.querySelectorAll('.plan-slider').forEach(slider => {
    const scroll = slider.querySelector('.slider-scroll');
    const images = scroll.querySelectorAll('img');
    const prevBtn = slider.querySelector('.slider-arrow-prev');
    const nextBtn = slider.querySelector('.slider-arrow-next');
    const dotsContainer = slider.querySelector('.slider-dots');
    const total = images.length;
    let current = 0;

    // Create dots
    images.forEach((_, i) => {
      const dot = document.createElement('button');
      dot.className = 'slider-dot' + (i === 0 ? ' active' : '');
      dot.setAttribute('aria-label', 'Go to image ' + (i + 1));
      dot.addEventListener('click', () => {
        current = i;
        scroll.scrollTo({ left: current * scroll.clientWidth, behavior: 'smooth' });
        updateDots();
      });
      dotsContainer.appendChild(dot);
    });

    const dots = dotsContainer.querySelectorAll('.slider-dot');

    function updateDots() {
      dots.forEach((d, i) => d.classList.toggle('active', i === current));
    }

    function snapTo(index) {
      current = (index + total) % total;
      scroll.scrollTo({ left: current * scroll.clientWidth, behavior: 'smooth' });
      updateDots();
    }

    prevBtn.addEventListener('click', () => snapTo(current - 1));
    nextBtn.addEventListener('click', () => snapTo(current + 1));

    // Track scroll position for dot sync
    let ticking = false;
    scroll.addEventListener('scroll', () => {
      if (!ticking) {
        requestAnimationFrame(() => {
          const idx = Math.round(scroll.scrollLeft / scroll.clientWidth);
          if (idx !== current && idx >= 0 && idx < total) {
            current = idx;
            updateDots();
          }
          ticking = false;
        });
        ticking = true;
      }
    });

    // Auto-play
    let auto = setInterval(() => snapTo(current + 1), 4000);
    slider.addEventListener('mouseenter', () => clearInterval(auto));
    slider.addEventListener('mouseleave', () => {
      auto = setInterval(() => snapTo(current + 1), 4000);
    });

    // Keyboard
    slider.addEventListener('keydown', e => {
      if (e.key === 'ArrowLeft') snapTo(current - 1);
      if (e.key === 'ArrowRight') snapTo(current + 1);
    });

    // Mouse drag
    let isDown = false;
    let startX = 0;
    let scrollLeft = 0;

    scroll.addEventListener('mousedown', e => {
      isDown = true;
      startX = e.pageX - scroll.offsetLeft;
      scrollLeft = scroll.scrollLeft;
    });

    scroll.addEventListener('mouseleave', () => {
      isDown = false;
      scroll.style.cursor = '';
    });

    scroll.addEventListener('mouseup', () => {
      isDown = false;
      scroll.style.cursor = '';
    });

    scroll.addEventListener('mousemove', e => {
      if (!isDown) return;
      e.preventDefault();
      const x = e.pageX - scroll.offsetLeft;
      const walk = (x - startX) * 1.5;
      scroll.scrollLeft = scrollLeft - walk;
    });
  });

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
            const formatted = current.toFixed(decimals);
            const parts = formatted.split('.');
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            el.textContent = parts.join('.') + suffix;
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

  // TESTIMONIALS TABS
  const testiData = [
    {
      img: '/amavi-images/flats/2BHK - DSC_7893.jpg',
      quote: 'The all-inclusive pricing was a game changer \u2014 no hidden costs. The 2BHK feels much bigger than the square footage suggests. Really happy with the quality of finishes.',
      name: 'Rajesh M.',
      role: '2BHK Owner, Phase 1',
      avatar: 'RM'
    },
    {
      img: '/amavi-images/flats/2BHK - DSC_7900.jpg',
      quote: 'We were looking for a 2BHK in Virar for months. Evershine Amavi stood out immediately \u2014 the club house, the kids play area, and the overall township feel make it worth every rupee. Possession was on time too.',
      name: 'Priya S.',
      role: '2BHK Owner, Phase 2',
      avatar: 'PS'
    },
    {
      img: '/amavi-images/flats/2BHK - DSC_7902.jpg',
      quote: 'RERA registration gave us confidence. The OC was already received when we visited. The 612 sq.ft. 2BHK is very spacious and the natural light is great. Evershine has been transparent throughout the process.',
      name: 'Anil K.',
      role: '2BHK Owner, Phase 3',
      avatar: 'AK'
    }
  ];

  const tabsContainer = document.getElementById('testi-tabs');
  const featureImg = document.getElementById('testi-img');
  const featureQuote = document.getElementById('testi-quote');
  const featureAvatar = document.getElementById('testi-avatar');
  const featureName = document.getElementById('testi-name');
  const featureRole = document.getElementById('testi-role');
  let currentTab = 0;

  if (tabsContainer) {
    function renderTabs() {
      tabsContainer.innerHTML = testiData.map((t, i) => `
        <div class="testi-tab${i === 0 ? ' active' : ''}" data-index="${i}">
          <div class="testi-tab-avatar">${t.avatar}</div>
          <div>
            <div class="testi-tab-name">${t.name}</div>
            <div class="testi-tab-role">${t.role}</div>
            <div class="testi-tab-preview">${t.quote.slice(0, 55)}...</div>
          </div>
        </div>
      `).join('');
    }

    function showTestimonial(index) {
      const t = testiData[index];
      const feature = document.getElementById('testi-feature');
      feature.classList.add('fading');
      const preloader = new Image();
      let loaded = false;
      let timedOut = false;
      preloader.onload = () => {
        loaded = true;
        if (timedOut) swap();
      };
      preloader.src = t.img;
      setTimeout(() => {
        timedOut = true;
        if (loaded) swap();
      }, 200);

      function swap() {
        featureImg.src = t.img;
        featureImg.alt = t.name;
        featureQuote.textContent = t.quote;
        featureAvatar.textContent = t.avatar;
        featureName.textContent = t.name;
        featureRole.textContent = t.role;
        feature.classList.remove('fading');
      }

      document.querySelectorAll('.testi-tab').forEach(el => el.classList.remove('active'));
      document.querySelector(`.testi-tab[data-index="${index}"]`).classList.add('active');
      currentTab = index;
    }

    renderTabs();
    showTestimonial(0);

    tabsContainer.addEventListener('click', e => {
      const tab = e.target.closest('.testi-tab');
      if (tab) {
        const idx = parseInt(tab.dataset.index);
        if (idx !== currentTab) showTestimonial(idx);
      }
    });
  }
});
