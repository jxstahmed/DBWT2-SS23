@extends("layout")

@if(empty(Request::get('message')) === false)
    <script>
        alert("{{Request::get("message")}}")
        // Remove the parameter
        window.history.replaceState({}, document.title, "/articles");
    </script>

@endif

@yield("header")

@section("content")

    <h3>Artikelsuche</h3>
    <form>
        <input required minlength="2" type="search" class="form-control" placeholder="Welcher Artikel?" name="search"
               value="{{ request('search') }}">
        <button type="submit">Suchen</button>
    </form>

    <h1>Artikelübersicht</h1>
    <table>
        <th>Name</th>
        <th>Price</th>
        <th>Description</th>
        <tbody>
        @foreach ($articles as $article)
            <tr>
                <td>{{ $article->ab_name }}</td>
                <td>{{ $article->ab_price / 1000}}€</td>
                <td>{{ $article->ab_description }}</td>
                @php($bild = glob("articlesimages/{$article->id}.*"))
                @if(empty($bild[0]) === false)
                    <td><img src="{{$bild[0]}}" width="50%"></td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

@section("js")
    <script>

    </script>
@endsection
