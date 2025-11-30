<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Unauthorized</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #ffffff;
            margin: 0;
            text-align: center;
        }

        .error-container {
            font-family: Arial, sans-serif;
        }

        h1 {
            font-size: 6rem;
            font-weight: bold;
            color: #dc3545;
            margin: 0;
        }

        p {
            font-size: 1.5rem;
            color: #333;
        }

        .btn-home {
            margin-top: 20px;
            font-size: 1rem;
            padding: 10px 20px;
        }
    </style>
</head>

<body>
    <div class="error-container">
        <h1>403</h1>
        <p>Unauthorized Access</p>
        <a href="<?= base_url() ?>" class="btn btn-danger btn-home">Kembali ke Beranda</a>
    </div>
</body>

</html>