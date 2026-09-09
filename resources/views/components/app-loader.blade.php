<style>
    #app-loader {
        position: fixed;
        inset: 0;
        z-index: 2000;
        display: grid;
        place-items: center;
        pointer-events: none;
        opacity: 0;
        visibility: hidden;
        background: rgba(248, 250, 252, .78);
        backdrop-filter: blur(7px);
        transition: opacity .18s ease, visibility .18s ease;
    }

    #app-loader.is-visible {
        pointer-events: all;
        opacity: 1;
        visibility: visible;
    }

    .app-loader__panel {
        display: grid;
        min-width: 9rem;
        gap: .75rem;
        justify-items: center;
        padding: 1.15rem 1.4rem;
        border: 1px solid rgba(30, 41, 59, .08);
        border-radius: 1rem;
        background: rgba(255, 255, 255, .94);
        box-shadow: 0 18px 50px rgba(30, 41, 59, .14);
    }

    .app-loader__mark {
        width: 2rem;
        height: 2rem;
        border: 3px solid #dbe5ff;
        border-top-color: #3867d6;
        border-right-color: #159a8c;
        border-radius: 50%;
        animation: app-loader-spin .7s linear infinite;
    }

    .app-loader__text {
        color: #536176;
        font-size: .75rem;
        font-weight: 700;
        letter-spacing: .02em;
    }

    @keyframes app-loader-spin {
        to { transform: rotate(360deg); }
    }

    @media (prefers-reduced-motion: reduce) {
        #app-loader { transition: none; }
        .app-loader__mark { animation-duration: 1.5s; }
    }
</style>

<div id="app-loader" class="is-visible" role="status" aria-live="polite" aria-label="Loading">
    <div class="app-loader__panel">
        <span class="app-loader__mark" aria-hidden="true"></span>
        <span class="app-loader__text">Loading</span>
    </div>
</div>

<script>
    (() => {
        const loader = document.getElementById('app-loader');
        if (!loader) return;

        let pendingRequests = 0;
        let hideTimer;

        const showLoader = () => {
            window.clearTimeout(hideTimer);
            loader.classList.add('is-visible');
        };

        const hideLoader = () => {
            window.clearTimeout(hideTimer);
            hideTimer = window.setTimeout(() => {
                if (pendingRequests === 0) loader.classList.remove('is-visible');
            }, 120);
        };

        document.addEventListener('DOMContentLoaded', hideLoader, { once: true });
        document.addEventListener('livewire:navigate', showLoader);
        document.addEventListener('livewire:navigated', hideLoader);
        document.addEventListener('click', (event) => {
            const link = event.target.closest('a[href]');
            if (!link || event.defaultPrevented || link.target === '_blank' || link.hasAttribute('download')) return;
            if (link.origin === window.location.origin && !link.hash) showLoader();
        }, true);
        document.addEventListener('submit', showLoader, true);

        document.addEventListener('livewire:init', () => {
            Livewire.hook('request', ({ succeed, fail }) => {
                pendingRequests++;
                showLoader();

                const finish = () => {
                    pendingRequests = Math.max(0, pendingRequests - 1);
                    hideLoader();
                };

                succeed(finish);
                fail(finish);
            });
        }, { once: true });
    })();
</script>
