@component('mail::message')
# 🎉 Welcome to Doonates, {{ $user->name }}!

Your account has been **approved** by our admin team. You can now log in and start using the platform.

---

@if($user->organization_name)
**Organization:** {{ $user->organization_name }}
@endif
**Email:** {{ $user->email }}
**Role:** {{ ucfirst($user->role) }}

---

@component('mail::button', ['url' => $loginUrl, 'color' => 'success'])
Login to Doonates
@endcomponent

Once logged in, you can:

@if($user->role === 'organization')
- ✅ Post food donations
- ✅ Manage incoming requests
- ✅ Set pickup locations
@else
- ✅ Browse available donations
- ✅ Request food pickups
- ✅ Track your requests
@endif

If you have any questions, contact us at [admin@doonates.com](mailto:admin@doonates.com).

Thank you for being part of the Doonates community!

Regards,
**The Doonates Team**

@component('mail::subcopy')
If you did not register for a Doonates account, you can safely ignore this email.
@endcomponent
@endcomponent
