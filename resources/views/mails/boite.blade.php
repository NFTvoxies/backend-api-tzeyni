<!DOCTYPE html>
<html >
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=7">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Vérification de l'e-mail</title>
    <link rel="stylesheet" href="{{asset('style.css')}}">
</head>
<body>
    <div class="email">
        <h1 class="email_title">TZEYNI</h1>
        <div class="email_group">
            <p class="group_text">Salut {{$full_name}}</p>
            <p class="group_text">Veuillez vérifier votre adresse e-mail.</p>
            <p class="group_text text">Des offres incroyables, des mises à jour, des actualités intéressantes directement dans votre boîte de réception</p>
        </div>
        <span class="email_code">{{$code}}</span>
    </div>
</body>
</html>