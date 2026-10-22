/* ============================================================
   NUTRIMATE — Global Script (shared.js + landing + foodDatabase)
   ------------------------------------------------------------
   - Sidebar mobile/tablet & navbar scroll shadow
   - Modal autentikasi (Masuk & Daftar)
   - Password visibility toggle
   - IntersectionObserver scroll reveal animation
   - Food Database detail modal popup & filter pills
   ============================================================ */
(function () {
    'use strict';

    /* ---------- Sidebar (mobile & tablet) ---------- */
    function setSidebar(open) {
        var menuBtn = document.getElementById('menuBtn');
        var sidebar = document.getElementById('sidebar');
        if (!menuBtn || !sidebar) return;

        document.body.classList.toggle('sidebar-open', open);
        menuBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
        sidebar.setAttribute('aria-hidden', open ? 'false' : 'true');
    }

    window.NutriMate = window.NutriMate || {};
    window.NutriMate.closeSidebar = function () { setSidebar(false); };
    window.NutriMate.isSidebarOpen = function () {
        return document.body.classList.contains('sidebar-open');
    };

    function initNutriMate() {
        var body = document.body;
        var navbar = document.getElementById('navbar');
        var menuBtn = document.getElementById('menuBtn');
        var overlay = document.getElementById('overlay');
        var sidebar = document.getElementById('sidebar');

        if (menuBtn && overlay && sidebar) {
            menuBtn.addEventListener('click', function () {
                setSidebar(!window.NutriMate.isSidebarOpen());
            });

            overlay.addEventListener('click', function () {
                setSidebar(false);
            });

            sidebar.querySelectorAll('a').forEach(function (link) {
                link.addEventListener('click', function () {
                    setSidebar(false);
                });
            });

            window.addEventListener('resize', function () {
                if (window.innerWidth >= 1024) setSidebar(false);
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') setSidebar(false);
            });
        }

        /* ---------- Bayangan navbar saat di-scroll ---------- */
        if (navbar) {
            var onScroll = function () {
                navbar.classList.toggle('scrolled', window.scrollY > 10);
            };
            window.addEventListener('scroll', onScroll, { passive: true });
            onScroll();
        }

        /* ---------- Pop up Masuk / Daftar ---------- */
        var authModal = document.getElementById('authModal');
        var lastFocus = null;

        function firstAuthInput() {
            if (!authModal) return null;
            return authModal.querySelector('.auth-panel:not([hidden]) input');
        }

        function showAuthPanel(name) {
            if (!authModal) return;
            authModal.querySelectorAll('.auth-panel').forEach(function (panel) {
                panel.hidden = panel.getAttribute('data-panel') !== name;
            });
        }

        function isAuthModalOpen() {
            return authModal && authModal.classList.contains('is-open');
        }

        function openAuthModal(name) {
            if (!authModal) return;
            var alreadyOpen = isAuthModalOpen();
            if (!alreadyOpen) lastFocus = document.activeElement;

            if (window.NutriMate) window.NutriMate.closeSidebar();
            showAuthPanel(name);
            authModal.classList.add('is-open');
            authModal.setAttribute('aria-hidden', 'false');
            body.classList.add('modal-open');

            setTimeout(function () {
                var input = firstAuthInput();
                if (input) input.focus();
            }, 60);
        }

        function closeAuthModal() {
            if (!isAuthModalOpen()) return;
            authModal.classList.remove('is-open');
            authModal.setAttribute('aria-hidden', 'true');
            body.classList.remove('modal-open');
            if (lastFocus && lastFocus.focus) lastFocus.focus();
        }

        if (authModal) {
            document.querySelectorAll('[data-open-modal]').forEach(function (el) {
                el.addEventListener('click', function (e) {
                    e.preventDefault();
                    openAuthModal(el.getAttribute('data-open-modal'));
                });
            });

            authModal.querySelectorAll('[data-close-modal]').forEach(function (el) {
                el.addEventListener('click', closeAuthModal);
            });

            authModal.addEventListener('click', function (e) {
                if (e.target === authModal) closeAuthModal();
            });

            authModal.querySelectorAll('.pw-toggle').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var input = btn.parentElement.querySelector('input');
                    var show = input.type === 'password';
                    input.type = show ? 'text' : 'password';
                    btn.classList.toggle('on', show);
                    btn.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Tampilkan password');
                });
            });

            if (isAuthModalOpen()) {
                var input = firstAuthInput();
                if (input) input.focus();
            }
        }

        /* ---------- Pop up Detail Makanan (Food Database) ---------- */
        var foodModal = document.getElementById('modalOverlay');
        var btnCloseFoodModal = document.getElementById('btnCloseModal');
        var modalImg = document.getElementById('modalImg');

        function isFoodModalOpen() {
            return foodModal && foodModal.classList.contains('is-open');
        }

        function openFoodModal(btn) {
            if (!foodModal) return;
            lastFocus = document.activeElement;

            var name = btn.getAttribute('data-food-name') || '-';
            var category = btn.getAttribute('data-food-category') || 'Lainnya';
            var price = btn.getAttribute('data-food-price') || 'Rp 0';
            var unit = btn.getAttribute('data-food-unit') || '';
            var desc = btn.getAttribute('data-food-desc') || 'Tidak ada deskripsi rinci.';
            var cal = btn.getAttribute('data-food-cal') || '0 kcal';
            var protein = btn.getAttribute('data-food-protein') || '0g';
            var carbs = btn.getAttribute('data-food-carbs') || '0g';
            var fat = btn.getAttribute('data-food-fat') || '0g';
            var image = btn.getAttribute('data-food-image') || '';
            var editUrl = btn.getAttribute('data-food-edit');

            document.getElementById('modalBadge').textContent = category;
            document.getElementById('modalTitle').textContent = name;
            document.getElementById('modalPrice').textContent = price;
            document.getElementById('modalUnit').textContent = unit;
            document.getElementById('modalDesc').textContent = desc;
            document.getElementById('modalCal').textContent = cal;
            document.getElementById('modalProtein').textContent = protein;
            document.getElementById('modalCarbs').textContent = carbs;
            document.getElementById('modalFat').textContent = fat;

            var modalEditLink = document.getElementById('modalEditLink');
            if (modalEditLink && editUrl) {
                modalEditLink.href = editUrl;
            }

            if (modalImg) {
                if (image && image.trim() !== '') {
                    modalImg.src = image;
                    modalImg.alt = name;
                    modalImg.style.display = 'block';
                } else {
                    modalImg.removeAttribute('src');
                    modalImg.alt = '';
                    modalImg.style.display = 'none';
                }
            }

            foodModal.classList.add('is-open');
            foodModal.setAttribute('aria-hidden', 'false');
            body.classList.add('modal-open');
            if (btnCloseFoodModal) btnCloseFoodModal.focus();
        }

        function closeFoodModal() {
            if (!isFoodModalOpen()) return;
            foodModal.classList.remove('is-open');
            foodModal.setAttribute('aria-hidden', 'true');
            body.classList.remove('modal-open');
            if (lastFocus && lastFocus.focus) lastFocus.focus();
            lastFocus = null;
        }

        if (foodModal) {
            document.querySelectorAll('.btn-detail').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    openFoodModal(btn);
                });
            });

            if (btnCloseFoodModal) {
                btnCloseFoodModal.addEventListener('click', closeFoodModal);
            }

            foodModal.addEventListener('click', function (e) {
                if (e.target === foodModal) closeFoodModal();
            });

            var btnAddPlanner = document.getElementById('btnAddPlanner');
            if (btnAddPlanner) {
                btnAddPlanner.addEventListener('click', function () {
                    alert('Menu telah ditambahkan ke rencana makan harian Anda!');
                    closeFoodModal();
                });
            }
        }

        /* ---------- Filter Form & Category Pills Interaction ---------- */
        var foodFilterForm = document.getElementById('foodFilterForm');
        var categoryInput = document.getElementById('categoryInput');
        var sortSelect = document.getElementById('sortSelect');
        var searchInput = document.getElementById('searchInput');

        if (foodFilterForm) {
            document.querySelectorAll('.category-pills .pill').forEach(function (pill) {
                pill.addEventListener('click', function () {
                    document.querySelectorAll('.category-pills .pill').forEach(function (p) {
                        p.classList.remove('active');
                    });
                    pill.classList.add('active');
                    if (categoryInput) {
                        categoryInput.value = pill.getAttribute('data-category');
                    }
                    foodFilterForm.submit();
                });
            });

            if (sortSelect) {
                sortSelect.addEventListener('change', function () {
                    foodFilterForm.submit();
                });
            }
        }

        /* ---------- Keyboard: Esc & Tab di Modal ---------- */
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                if (isAuthModalOpen()) closeAuthModal();
                if (isFoodModalOpen()) closeFoodModal();
            }

            var activeModal = isAuthModalOpen() ? authModal : (isFoodModalOpen() ? foodModal : null);
            if (e.key === 'Tab' && activeModal) {
                var focusable = activeModal.querySelectorAll(
                    'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
                );
                if (!focusable.length) return;
                var first = focusable[0];
                var last = focusable[focusable.length - 1];
                if (e.shiftKey && document.activeElement === first) {
                    e.preventDefault();
                    last.focus();
                } else if (!e.shiftKey && document.activeElement === last) {
                    e.preventDefault();
                    first.focus();
                }
            }
        });

        /* ---------- Animasi muncul untuk kartu fitur & CTA ---------- */
        var items = document.querySelectorAll('.reveal');
        if (items.length > 0) {
            if ('IntersectionObserver' in window) {
                var observer = new IntersectionObserver(function (entries) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('visible');
                            observer.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.15 });

                items.forEach(function (el, i) {
                    el.style.transitionDelay = (i % 4) * 80 + 'ms';
                    observer.observe(el);
                });
            } else {
                items.forEach(function (el) { el.classList.add('visible'); });
            }
        }

        /* ---------- Password toggle (halaman profil & lainnya) ---------- */
        document.querySelectorAll('.pw-wrap .pw-toggle').forEach(function (btn) {
            // skip tombol di dalam auth modal (sudah ditangani di atas)
            if (authModal && authModal.contains(btn)) return;
            btn.addEventListener('click', function () {
                var input = btn.parentElement.querySelector('input');
                if (!input) return;
                var show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                btn.classList.toggle('on', show);
                btn.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Tampilkan password');
            });
        });

        /* ---------- Flash success — tutup & auto-dismiss ---------- */
        var flash = document.getElementById('flash');
        if (flash) {
            var closeBtn = flash.querySelector('.flash-close');
            if (closeBtn) {
                closeBtn.addEventListener('click', function () {
                    flash.style.transition = 'opacity 0.3s';
                    flash.style.opacity = '0';
                    setTimeout(function () { flash.remove(); }, 320);
                });
            }
            // auto-dismiss setelah 5 detik
            setTimeout(function () {
                if (!flash.parentNode) return;
                flash.style.transition = 'opacity 0.5s';
                flash.style.opacity = '0';
                setTimeout(function () { if (flash.parentNode) flash.remove(); }, 520);
            }, 5000);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initNutriMate);
    } else {
        initNutriMate();
    }
})();
