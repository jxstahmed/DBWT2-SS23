<!doctype html>
<html class="no-js" lang="DE">
<head>
    <meta charset="utf-8">
    <title>Ihre Abalo</title>
    <meta name="description" content="unused">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.2/css/bootstrap.min.css"
          integrity="sha512-CpIKUSyh9QX2+zSdfGP+eWLx23C8Dj9/XmHjZY2uDtfkdLGo0uY12jgcnkX9vXOgYajEKb/jiw67EYm+kBf+6g=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="css/abalo.css" type="text/css">
    <script src="js/abalo.js" type="text/javascript"></script>
    <meta name="theme-color" content="#dadada">

</head>
<body>

<div class="row p-2 py-4 abalo_cookies_consent abalo_shadow align-content-start" id="cookies_consent"
     style="display: none;">

    <div class="col-12">
        <p class="text-font-subtitle-less text-center font-weight-bold">We use cookies</p>
        <p class="text-font-caption text-center font-weight-semibold">Consent to get nothing, and decline to also get
            nothing. We just track everything! [cringe]</p>
    </div>

    <div class="col-12 text-center mt-2">
        <button onclick="cookiesResponse(true)" type="button"
                class="fill-width text-font-caption btn-block font-weight-bold btn btn-dark">Accept
        </button>
    </div>

    <div class="col-12 text-center mt-3">
        <button onclick="cookiesResponse(false)" type="button"
                class="fill-width text-font-caption btn-block font-weight-bold btn btn-light">Decline
        </button>
    </div>
</div>


<div class="container py-4">
    <div class="row">
        @if(empty(Request::get('message')) === false)
            <div class="col-12 mb-2">
                <div id="message_alert_box" class="alert alert-primary text-font-caption font-weight-bold alert-dismissible fade show" role="alert">
                    <div class="row">

                        <div class="col flex-grow-1 align-self-center">
                            {!! Request::get("message") !!}
                        </div>

                        <div class="col flex-grow-0 align-self-center">
                            <button type="button" class="close" id="message_alert" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>

                    <script>
                        "use strict"

                        let message_alert_box = document.getElementById("message_alert_box");
                        let message_alert = document.getElementById("message_alert");
                        if (message_alert_box && message_alert) {
                            message_alert.onclick = () => {
                                message_alert_box.style.display = 'none';
                                // delete the message property
                                let url = new URL(location.href);
                                url.searchParams.delete('message');
                                window.history.pushState({}, document.title, url);
                            }
                        }
                    </script>
                </div>
            </div>
        @endif

        <!-- header -->
        <div class="col-12 col-md-4 col-lg-3 col-xl-2">
            <ul id="nav" class="abalo_nav">
            </ul>
        </div>

        <!-- content -->
        <div class="col-12 col-md-8 col-lg-9 col-xl-10">
            <div class="row">
                @yield("content")
            </div>
        </div>
    </div>
</div>

<footer>
    <hr>
    <ul>
        <li>(c) Abalo GmbH</li>
        <li>Ahmed Jumaa &amp; Mohammad Hammado</li>
        <li id="impressum"><a href="#impressum">Impressum</a></li>
    </ul>
</footer>


@yield("js")
</body>

</html>
