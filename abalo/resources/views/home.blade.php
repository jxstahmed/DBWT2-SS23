@extends("layout")

@if(empty(Request::get('message')) === false)
    <script>
        "use strict"

        alert("{{Request::get("message")}}")
        // Remove the parameter
        window.history.replaceState({}, document.title, "/");
    </script>

@endif

@section("content")
    <div id="main">
        <p>Me main amigo</p>
    </div>
@endsection
