import './bootstrap';
import '../css/app.css';

import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// ─── Service Worker (single canonical SW) ─────────────────────────────────────
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/serviceworker.js', { scope: '/' })
            .then(reg => {
                console.log('Doonates SW registered, scope:', reg.scope);
            })
            .catch(err => console.error('Doonates SW registration failed:', err));
    });
}

// ─── Push Subscription Helper (called from Blade layouts after permission) ────
window.DoonautesPush = {

    /**
     * Subscribe this device to push notifications.
     * Called once after the user grants Notification permission.
     */
    async subscribe(vapidPublicKey) {
        if (!('serviceWorker' in navigator) || !('PushManager' in window)) return;

        try {
            const registration = await navigator.serviceWorker.ready;

            // Check if already subscribed
            let subscription = await registration.pushManager.getSubscription();

            if (!subscription) {
                subscription = await registration.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: DoonautesPush._urlBase64ToUint8Array(vapidPublicKey),
                });
            }

            // Send subscription to server
            await DoonautesPush._sendToServer('/push/subscribe', {
                endpoint:   subscription.endpoint,
                public_key: DoonautesPush._arrayBufferToBase64(subscription.getKey('p256dh')),
                auth_token: DoonautesPush._arrayBufferToBase64(subscription.getKey('auth')),
            });

        } catch (err) {
            console.error('Push subscription failed:', err);
        }
    },

    /**
     * Request notification permission, then subscribe if granted.
     * Only called from the layout — never on every page load.
     */
    async requestPermissionAndSubscribe(vapidPublicKey) {
        if (!('Notification' in window)) return;

        // Already granted — just make sure we're subscribed
        if (Notification.permission === 'granted') {
            await DoonautesPush.subscribe(vapidPublicKey);
            return;
        }

        // Already denied — don't ask again
        if (Notification.permission === 'denied') return;

        // Ask once
        const permission = await Notification.requestPermission();
        if (permission === 'granted') {
            await DoonautesPush.subscribe(vapidPublicKey);
        }
    },

    // ── Private helpers ──────────────────────────────────────────────────────

    async _sendToServer(url, data) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
        await fetch(url, {
            method:  'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify(data),
        });
    },

    _urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64  = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
        const rawData = atob(base64);
        return Uint8Array.from([...rawData].map(c => c.charCodeAt(0)));
    },

    _arrayBufferToBase64(buffer) {
        return btoa(String.fromCharCode(...new Uint8Array(buffer)));
    },
};
