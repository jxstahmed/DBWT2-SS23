<!doctype html>
<html lang="de">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <title>Artikel Suche</title>
</head>
<body>
<div class="container my-5 py-5 px-5 mx-5">
    <h3>Artikelsuche</h3>
    <form>
        <input required minlength="2" type="search" class="form-control" placeholder="Welcher Artikel?" name="search" value="{{ request('search') }}">
        <button type="submit">Suchen</button>
    </form>

    <h1>Artikelübersicht</h1>
    <table >
        <th>Name</th><th>Price</th><th>Description</th>
        <tbody>
        @foreach ($articles as $article)
            <tr>
                <td>{{ $article->ab_name }}</td>
                <td>{{ $article->ab_price / 1000}}€</td>
                <td>{{ $article->ab_description }}</td>
                @php($bild = glob("articlesimages/{$article->id}.*"))
                <td><img src="{{$bild[0]}}" width="10%"></td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
