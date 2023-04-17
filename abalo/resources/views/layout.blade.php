<!doctype html>
<html class="no-js" lang="DE">
<head>
    <meta charset="utf-8">
    <title>Ihre Abalo</title>
    <meta name="description" content="unused">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.2/css/bootstrap.min.css" integrity="sha512-CpIKUSyh9QX2+zSdfGP+eWLx23C8Dj9/XmHjZY2uDtfkdLGo0uY12jgcnkX9vXOgYajEKb/jiw67EYm+kBf+6g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/abalo.css" type="text/css">
    <script src="js/abalo.js" type="text/javascript"></script>
    <meta name="theme-color" content="#dadada">

</head>
<body>


<div class="container py-4">
  <div class="row">
    <!-- header -->
    <div class="col-12 col-md-4 col-lg-3 col-xl-2">
        @yield("header")
    </div>

    <!-- content -->
    <div class="col-12 col-md-8 col-lg-9 col-xl-10">
        @yield("main")
    </div>
  </div>
</div>



@yield("footer")
</body>

</html>
