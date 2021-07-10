@component('mail::message')
# Account Creation

Hello User, thank you for signing up on the platform.

Below is your Login details

Email: {{$user->email}}<br>
Password: {{$password}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
