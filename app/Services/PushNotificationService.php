<?php

namespace App\Services;

use App\Models\PushSubscription;
use App\Models\User;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class PushNotificationService
{
    private WebPush $webPush;

    public function __construct()
    {
        $auth = [
            'VAPID' => [
                'subject'    => config('app.url'),
                'publicKey'  => config('webpush.vapid.public_key'),
                'privateKey' => config('webpush.vapid.private_key'),
            ],
        ];

        $this->webPush = new WebPush($auth);
        $this->webPush->setDefaultOptions(['TTL' => 300]);
    }

    /**
     * Send a push notification to all subscriptions of a given user.
     */
    public function sendToUser(User $user, string $title, string $body, string $url = '/'): void
    {
        $subscriptions = PushSubscription::where('user_id', $user->id)->get();

        if ($subscriptions->isEmpty()) {
            return;
        }

        $payload = json_encode([
            'title' => $title,
            'body'  => $body,
            'url'   => $url,
            'icon'  => '/images/icons/icon-192.png',
            'badge' => '/images/icons/icon-96x96.png',
        ]);

        foreach ($subscriptions as $sub) {
            $subscription = Subscription::create([
                'endpoint'        => $sub->endpoint,
                'publicKey'       => $sub->public_key,
                'authToken'       => $sub->auth_token,
                'contentEncoding' => 'aesgcm',
            ]);

            $this->webPush->queueNotification($subscription, $payload);
        }

        // Send all queued notifications and clean up expired/invalid subscriptions
        foreach ($this->webPush->flush() as $report) {
            if (! $report->isSuccess()) {
                // 404 / 410 = subscription expired or unsubscribed on client
                $statusCode = $report->getResponse()?->getStatusCode();
                if (in_array($statusCode, [404, 410])) {
                    PushSubscription::where('endpoint', $report->getEndpoint())
                        ->delete();
                }
            }
        }
    }
}
