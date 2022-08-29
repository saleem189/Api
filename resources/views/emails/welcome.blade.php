
@component('mail::message')
Hello {{$user->name}}
 
Thank you for create an account. Please verify your email using this button:
 
@component('mail::button', ['url' => route('verify', $user->verification_token) ])
Verify Account
@endcomponent
 
Thanks,<br>
{{ config('app.name') }}
@endcomponent




<!-- 
    Simple Code
Hello {{$user->name}}
 
You changed your email, so we need to verify this new addres. Please use the link below:
    {{route('verify', $user->verification_token)}}

 -->