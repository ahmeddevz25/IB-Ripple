@component('mail::message')
# New Contact Message

**Name:** {{ $contact->name }}
**Email:** {{ $contact->email }}

**Message:**
> {!! nl2br(e($contact->message)) !!}

@component('mail::button', ['url' => config('app.url')])
Open Website
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
