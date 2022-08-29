
@component('mail::message')
Hello {{$user->name}}
 
You changed your email, so we need to verify this new addres. Please use the link below:
 
@component('mail::button', ['url' => route('verify', $user->verification_token) ])
Verify Account
@endcomponent
 
Thanks,<br>
{{ config('app.name') }}
@endcomponent

<!-- 
    this code is for simple mail template

Hello {{$user->name}}
 
You changed your email, so we need to verify this new addres. Please use the link below:
    {{route('verify', $user->verification_token)}}


 -->