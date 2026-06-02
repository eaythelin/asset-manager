@extends("layouts.pageslayout")
@section("content")
  <h1 class="font-bold text-2xl">Hi! this is a Placeholder page</h1>
  <p>Theres nothing here at the moment!</p>
@endsection

function makeCreatableSelect(selector, createUrl) {
    new TomSelect(selector, {
        create: function(input, callback) {
            fetch(createUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ name: input })
            })
            .then(res => res.json())
            .then(data => callback({ value: data.id, text: data.name }));
        },
        createOnBlur: true,
    });
}
