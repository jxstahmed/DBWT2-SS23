@extends("layout")

@if(empty(Request::get('message')) === false)
    <script>
        alert("{{Request::get("message")}}")
        // Remove the parameter
        window.history.replaceState({}, document.title, "/");
    </script>

@endif

@section("header")
    <ul id="nav" class="abalo nav">
    </ul>
@endsection

@section("main")
    <div id="main">
        <p>Me main amigo</p>
    </div>
@endsection


@section("footer")
    <footer>
        <hr>
        <ul>
            <li>(c) Abalo GmbH</li>
            <li>Ahmed Jumaa &amp; Mohammad Hammado</li>
            <li id="impressum"><a href="#impressum">Impressum</a></li>
        </ul>
    </footer>
@endsection
