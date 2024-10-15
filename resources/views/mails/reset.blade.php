<!DOCTYPE html>
<html >
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=7">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Réinitialisation du mot de passe</title>
    <link rel="stylesheet" href="{{asset('style.css')}}">
</head>
<body>
    <div class="email">
        <h1 class="email_title">TZEYNI</h1>
        <div class="email_group">
            <p class="group_text">Salut {{$full_name}},</p>
            <p class="group_text">Votre mot de passe a été réinitialisé.</p>
            <p class="group_text text">Voici votre nouveau mot de passe :</p>
        </div>
        <span class="email_code">{{$password}}</span>
    </div>
</body>
</html>