(function () {
    var DEFAULT_LANG = 'en';
    var SUPPORTED = ['km', 'en'];
    var STORAGE_KEY = 'chhankitek.lang';
    var cache = {};

    function currentLang() {
        var stored = localStorage.getItem(STORAGE_KEY);
        return SUPPORTED.indexOf(stored) !== -1 ? stored : DEFAULT_LANG;
    }

    function reveal() {
        document.documentElement.classList.remove('i18n-pending');
    }

    function applyTranslations(dict) {
        document.querySelectorAll('[data-i18n]').forEach(function (el) {
            var value = dict[el.getAttribute('data-i18n')];
            if (value != null) {
                el.textContent = value;
            }
        });

        document.querySelectorAll('[data-i18n-html]').forEach(function (el) {
            var value = dict[el.getAttribute('data-i18n-html')];
            if (value != null) {
                el.innerHTML = value;
            }
        });

        document.querySelectorAll('[data-i18n-attr]').forEach(function (el) {
            el.getAttribute('data-i18n-attr').split(';').forEach(function (pair) {
                var parts = pair.split(':');
                var attr = parts[0] && parts[0].trim();
                var value = parts[1] && dict[parts[1].trim()];
                if (attr && value != null) {
                    el.setAttribute(attr, value);
                }
            });
        });

        if (window.lucide && typeof window.lucide.createIcons === 'function') {
            window.lucide.createIcons();
        }
    }

    function toggleTranslationNotice(lang) {
        var notice = document.getElementById('ai-translation-notice');
        if (notice) {
            notice.classList.toggle('hidden', lang !== 'km');
        }
    }

    function setActiveButtons(lang) {
        document.querySelectorAll('[data-lang-btn]').forEach(function (btn) {
            var active = btn.getAttribute('data-lang-btn') === lang;
            btn.classList.toggle('bg-neutral-900', active);
            btn.classList.toggle('text-white', active);
            btn.classList.toggle('text-neutral-500', !active);
            btn.setAttribute('aria-pressed', String(active));
        });
    }

    function load(lang) {
        if (cache[lang]) {
            return Promise.resolve(cache[lang]);
        }

        return fetch('i18n/' + lang + '.json', { cache: 'no-cache' })
            .then(function (response) { return response.json(); })
            .then(function (dict) { cache[lang] = dict; return dict; });
    }

    function setLang(lang) {
        if (SUPPORTED.indexOf(lang) === -1) {
            lang = DEFAULT_LANG;
        }

        localStorage.setItem(STORAGE_KEY, lang);
        document.documentElement.lang = lang;
        setActiveButtons(lang);
        toggleTranslationNotice(lang);

        return load(lang).then(applyTranslations).then(reveal).catch(reveal);
    }

    function init() {
        document.querySelectorAll('[data-lang-btn]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                setLang(btn.getAttribute('data-lang-btn'));
            });
        });

        setLang(currentLang());
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    window.addEventListener('load', function () {
        setTimeout(reveal, 1500);
    });
})();
