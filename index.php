
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Développeur PHP Freelance</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #000;
        }

        .container {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .background-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .text-overlay {
            position: absolute;
            bottom: 20px;
            left: 70%;
            transform: translateX(-50%);
            font-family: 'Cinzel', serif;
            font-size: 70px;
            font-weight: 950;
            color: lightcyan;
            text-shadow: 4px 4px 8px rgba(0, 0, 0, 0.5);
            background: rgba(0, 0, 0, 0.5);
            padding: 10px 20px;
            border-radius: 5px;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <img src="capt.png" alt="Background Image" class="background-image">
        <div class="text-overlay">Développeur PHP Freelance</div>
    </div>
</body>
</html>
