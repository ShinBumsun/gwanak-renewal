/* ==========================================================
   관악노인종합복지관 테마 스크립트 (의존성 없음)
   ========================================================== */
(function () {
    'use strict';

    var doc = document;
    var root = doc.documentElement;
    var hd = doc.getElementById('hd');
    var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    function $(sel, ctx) { return (ctx || doc).querySelector(sel); }
    function $$(sel, ctx) { return Array.prototype.slice.call((ctx || doc).querySelectorAll(sel)); }

    /* ---------- 글자크기 ---------- */
    var fsBtns = $$('.fs-ctrl [data-fs]');
    function setFs(v) {
        if (v === '0') root.removeAttribute('data-fs'); else root.setAttribute('data-fs', v);
        fsBtns.forEach(function (b) { b.setAttribute('aria-pressed', b.getAttribute('data-fs') === v ? 'true' : 'false'); });
        try { localStorage.setItem('gw-fs', v); } catch (e) {}
    }
    fsBtns.forEach(function (b) {
        b.addEventListener('click', function () { setFs(b.getAttribute('data-fs')); });
    });
    setFs(root.getAttribute('data-fs') || '0');

    /* ---------- 헤더 : 스크롤 · 메가메뉴 ---------- */
    if (hd) {
        var onScroll = function () { hd.classList.toggle('is-scrolled', window.pageYOffset > 10); };
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();

        var gnb = $('#gnb', hd);
        var gnbBg = $('.gnb-bg', hd);
        var closeTimer;
        var openMega = function () {
            clearTimeout(closeTimer);
            var h = 0;
            $$('.gnb-sub', hd).forEach(function (s) { h = Math.max(h, s.scrollHeight); });
            if (gnbBg) gnbBg.style.height = h + 'px';
            hd.classList.add('is-mega');
        };
        var closeMega = function () {
            hd.classList.remove('is-mega');
            if (gnbBg) gnbBg.style.height = '0px';
        };
        if (gnb) {
            gnb.addEventListener('mouseenter', openMega);
            gnb.addEventListener('focusin', openMega);
            hd.querySelector('.hd-main').addEventListener('mouseleave', function () { closeTimer = setTimeout(closeMega, 120); });
            gnb.addEventListener('focusout', function (e) { if (!gnb.contains(e.relatedTarget)) closeMega(); });
            doc.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeMega(); });
            if (gnbBg) gnbBg.addEventListener('mouseenter', function () { clearTimeout(closeTimer); });
        }

        /* 현재 메뉴 표시 */
        var here = location.href.split('#')[0];
        $$('.gnb-item', hd).forEach(function (li) {
            $$('a', li).forEach(function (a) { if (a.href === here) li.classList.add('is-current'); });
        });
    }

    /* ---------- 통합검색 ---------- */
    var search = $('#hd-search');
    var searchBtn = $('.js-search-open');
    function toggleSearch(open) {
        if (!search) return;
        search.hidden = !open;
        if (searchBtn) searchBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
        if (open) { var i = $('input[type="search"]', search); if (i) i.focus(); }
        else if (searchBtn) searchBtn.focus();
    }
    if (searchBtn) searchBtn.addEventListener('click', function () { toggleSearch(search.hidden); });
    $$('.js-search-close').forEach(function (b) { b.addEventListener('click', function () { toggleSearch(false); }); });
    doc.addEventListener('keydown', function (e) { if (e.key === 'Escape' && search && !search.hidden) toggleSearch(false); });

    /* ---------- 전체메뉴 드로어 ---------- */
    var drawer = $('#drawer');
    var drawerBtn = $('.js-drawer-open');
    function openDrawer() {
        drawer.hidden = false;
        root.classList.add('is-locked');
        drawerBtn.setAttribute('aria-expanded', 'true');
        requestAnimationFrame(function () { drawer.classList.add('is-open'); });
        var f = $('.drawer-head button', drawer); if (f) f.focus();
    }
    function closeDrawer() {
        drawer.classList.remove('is-open');
        root.classList.remove('is-locked');
        drawerBtn.setAttribute('aria-expanded', 'false');
        setTimeout(function () { drawer.hidden = true; }, reduceMotion ? 0 : 300);
        drawerBtn.focus();
    }
    if (drawer && drawerBtn) {
        drawerBtn.addEventListener('click', openDrawer);
        $$('.js-drawer-close', drawer).forEach(function (b) { b.addEventListener('click', closeDrawer); });
        drawer.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeDrawer();
            if (e.key !== 'Tab') return;
            var f = $$('a[href], button:not([disabled])', drawer).filter(function (el) { return el.offsetParent !== null; });
            if (!f.length) return;
            if (e.shiftKey && doc.activeElement === f[0]) { e.preventDefault(); f[f.length - 1].focus(); }
            else if (!e.shiftKey && doc.activeElement === f[f.length - 1]) { e.preventDefault(); f[0].focus(); }
        });
        $$('.drawer-tit', drawer).forEach(function (btn) {
            btn.addEventListener('click', function () {
                var open = btn.getAttribute('aria-expanded') === 'true';
                btn.setAttribute('aria-expanded', open ? 'false' : 'true');
                doc.getElementById(btn.getAttribute('aria-controls')).hidden = open;
            });
        });
    }

    /* ---------- 서브메뉴 탭 : 현재 메뉴가 보이도록 스크롤 ---------- */
    var subCur = $('.sub-nav-list .is-current');
    if (subCur) {
        var list = subCur.closest('.sub-nav-list');
        list.scrollLeft = subCur.offsetLeft - (list.clientWidth - subCur.offsetWidth) / 2;
    }

    /* ---------- 넓은 표 : 가로 스크롤 안내 ---------- */
    var wraps = $$('.gw-tbl-wrap');
    function markScroll() { wraps.forEach(function (w) { w.classList.toggle('is-scroll', w.scrollWidth > w.clientWidth + 2); }); }
    if (wraps.length) { markScroll(); window.addEventListener('resize', markScroll); }

    /* ---------- 관련기관 이동 ---------- */
    $$('.js-family').forEach(function (sel) {
        sel.addEventListener('change', function () {
            if (sel.value) window.open(sel.value, '_blank', 'noopener');
            sel.selectedIndex = 0;
        });
    });

    /* ---------- 맨 위로 ---------- */
    var topBtn = $('.js-to-top');
    if (topBtn) {
        window.addEventListener('scroll', function () { topBtn.classList.toggle('is-show', window.pageYOffset > 600); }, { passive: true });
        topBtn.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: reduceMotion ? 'auto' : 'smooth' });
            var skip = $('.skip'); if (skip) skip.focus();
        });
    }

    /* ---------- 메인 비주얼 슬라이더 ---------- */
    $$('[data-slider]').forEach(function (vis) {
        var slides = $$('.vis-slide', vis);
        var delay = parseInt(vis.getAttribute('data-autoplay'), 10) || 0;
        if (slides.length < 2) return;

        var cur = 0, timer = null;
        var paused = reduceMotion;          // 움직임 줄이기 설정 시 자동재생 안 함
        var hoverPause = false;
        var curEl = $('.js-vis-cur', vis);
        var toggle = $('.js-vis-toggle', vis);
        vis.style.setProperty('--vis-delay', delay + 'ms');

        function go(n) {
            slides[cur].classList.remove('is-active');
            slides[cur].setAttribute('aria-hidden', 'true');
            cur = (n + slides.length) % slides.length;
            slides[cur].classList.add('is-active');
            slides[cur].removeAttribute('aria-hidden');
            if (curEl) curEl.textContent = (cur < 9 ? '0' : '') + (cur + 1);
            restart();
        }
        function stop() { clearTimeout(timer); vis.classList.remove('is-running'); }
        function restart() {
            stop();
            if (paused || hoverPause || !delay) return;
            void vis.offsetWidth;            // 진행바 애니메이션 재시작
            vis.classList.add('is-running');
            timer = setTimeout(function () { go(cur + 1); }, delay);
        }
        function setPaused(p) {
            paused = p;
            vis.classList.toggle('is-paused', p);
            if (toggle) toggle.setAttribute('aria-label', p ? '자동 넘김 시작' : '자동 넘김 멈춤');
            restart();
        }

        var prev = $('.js-vis-prev', vis), next = $('.js-vis-next', vis);
        if (prev) prev.addEventListener('click', function () { go(cur - 1); });
        if (next) next.addEventListener('click', function () { go(cur + 1); });
        if (toggle) toggle.addEventListener('click', function () { setPaused(!paused); });

        vis.addEventListener('mouseenter', function () { hoverPause = true; restart(); });
        vis.addEventListener('mouseleave', function () { hoverPause = false; restart(); });
        vis.addEventListener('focusin', function () { hoverPause = true; restart(); });
        vis.addEventListener('focusout', function (e) { if (!vis.contains(e.relatedTarget)) { hoverPause = false; restart(); } });

        // 스와이프
        var sx = null;
        vis.addEventListener('touchstart', function (e) { sx = e.touches[0].clientX; }, { passive: true });
        vis.addEventListener('touchend', function (e) {
            if (sx === null) return;
            var dx = e.changedTouches[0].clientX - sx;
            if (Math.abs(dx) > 50) go(cur + (dx < 0 ? 1 : -1));
            sx = null;
        });

        setPaused(paused);
    });

    /* ---------- 탭 ---------- */
    $$('[data-tabs]').forEach(function (wrap) {
        var tabs = $$('[role="tab"]', wrap);
        function select(tab, focus) {
            tabs.forEach(function (t) {
                var on = t === tab;
                t.setAttribute('aria-selected', on ? 'true' : 'false');
                t.tabIndex = on ? 0 : -1;
                doc.getElementById(t.getAttribute('aria-controls')).hidden = !on;
            });
            if (focus) tab.focus();
        }
        tabs.forEach(function (t, i) {
            t.addEventListener('click', function () { select(t); });
            t.addEventListener('keydown', function (e) {
                var n = null;
                if (e.key === 'ArrowRight') n = (i + 1) % tabs.length;
                if (e.key === 'ArrowLeft') n = (i - 1 + tabs.length) % tabs.length;
                if (e.key === 'Home') n = 0;
                if (e.key === 'End') n = tabs.length - 1;
                if (n !== null) { e.preventDefault(); select(tabs[n], true); }
            });
        });
    });

    /* ---------- 갤러리 좌우 이동 ---------- */
    var track = $('#gal_track');
    if (track) {
        var gPrev = $('.js-gal-prev'), gNext = $('.js-gal-next');
        var step = function () { var it = $('.gal-item', track); return it ? it.offsetWidth + 24 : track.clientWidth; };
        var sync = function () {
            if (gPrev) gPrev.disabled = track.scrollLeft <= 2;
            if (gNext) gNext.disabled = track.scrollLeft + track.clientWidth >= track.scrollWidth - 2;
        };
        if (gPrev) gPrev.addEventListener('click', function () { track.scrollBy({ left: -step(), behavior: reduceMotion ? 'auto' : 'smooth' }); });
        if (gNext) gNext.addEventListener('click', function () { track.scrollBy({ left: step(), behavior: reduceMotion ? 'auto' : 'smooth' }); });
        track.addEventListener('scroll', sync, { passive: true });
        window.addEventListener('resize', sync);
        sync();
    }
})();
