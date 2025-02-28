<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - <?= htmlspecialchars($errorCode) ?></title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f9fafb;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
            box-sizing: border-box;
        }
        .error-container {
            background-color: #ffffff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 700px;
            text-align: center;
        }
        h1 {
            color: #e74c3c;
            font-size: 60px;
            margin: 0;
        }
        .error-code {
            font-size: 80px;
            font-weight: 700;
            margin: 10px 0;
            color: #e74c3c;
        }
        .error-message {
            font-size: 20px;
            color: #555;
            margin-bottom: 20px;
        }
        .error-details {
            background-color: #fbe9e7;
            color: #9e2a2f;
            padding: 12px;
            border-radius: 6px;
            margin-top: 20px;
            font-size: 14px;
            text-align: left;
            display: block;
            max-width: 100%;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .button {
            margin-top: 25px;
            padding: 12px 25px;
            background-color: #3498db;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: background-color 0.3s ease;
        }
        .button:hover {
            background-color: #2980b9;
        }
        @media (max-width: 600px) {
            h1 {
                font-size: 45px;
            }
            .error-code {
                font-size: 60px;
            }
            .error-message {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>

    <div class="error-container">
        <h1>Oops!</h1>
        <div class="error-code"><?= htmlspecialchars($errorCode) ?></div>
        <div class="error-message"><?= htmlspecialchars($errorMessage) ?></div>

        <?php if (!empty($errorData)): ?>
            <div class="error-details">
                <strong>Error Details:</strong>
                <pre><?= htmlspecialchars(print_r($errorData, true)) ?></pre>
            </div>
        <?php endif; ?>

    </div>

</body>
</html>
