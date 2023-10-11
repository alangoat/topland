@if ($contact)
    <p>{{ trans('plugins/contact::contact.tables.time') }}: <i>{{ BaseHelper::formatDate($contact->created_at, 'd-m-Y H:i:s') }}</i></p>
    <p>{{ trans('plugins/contact::contact.tables.full_name') }}: <i>{{ $contact->name }}</i></p>
    <p>{{ trans('plugins/contact::contact.tables.email') }}: <i><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></i></p>
    <p>{{ trans('plugins/contact::contact.tables.phone') }}: <i>@if ($contact->phone) <a href="tel:{{ $contact->phone }}">{{ $contact->phone }}</a> @else N/A @endif</i></p>
    <p>{{ trans('plugins/contact::contact.tables.content') }}:</p>
    <pre class="message-content">{{ $contact->content ?: '...' }}</pre>
@endif
