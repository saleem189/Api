Hello {{$user->name}}
You changed your email, so we need to verify this new addres. Please use the link below:
{{route('verify', $user->verification_token)}}    