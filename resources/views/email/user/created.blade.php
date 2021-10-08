@component('mail::message')
    # Login Details

    Hello User,

    Your login details are as follows:

    Email: {{ $user->email }}<br>
    Password: {{ $password }}

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
