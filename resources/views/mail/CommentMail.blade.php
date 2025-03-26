<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeni Yorum</title>
</head>

<body>
    <h1>Yeni Yorum Geldi!</h1>
    <p><strong>Yorum Yapan:</strong> {{ $commentData['name']??"" }}</p>
    <p><strong>Email:</strong> {{ $commentData['email']??"" }}</p>
    <p><strong>Yorum:</strong> {{ $commentData['message']??"" }}</p>
</body>

</html>
