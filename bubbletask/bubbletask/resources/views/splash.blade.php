<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Welcome to BubbleTask</title>
    <style>
        body, html {
            margin: 0; padding: 0; height: 100%;
            background-color: #C2E1FC; /* baby blue */
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            font-family: Arial, sans-serif;
        }
        img {
            width: 400px;   /* Ukuran diperbesar */
            height: auto;
            margin-bottom: 30px;
            object-fit: contain;
        }
        .btn-start {
            background-color: #3D3F91;
            color: white;
            padding: 15px 40px;
            border: none;
            border-radius: 8px;
            font-size: 1.25rem;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 5px 0 #2f3172;
            transition: all 0.2s ease;
        }
        .btn-start:active {
            transform: translateY(4px);
            box-shadow: 0 1px 0 #2f3172;
        }
        .btn-start:hover {
            background-color: #34387f;
        }
    </style>
</head>
<body>
    <img src="{{ asset('images/logo.png') }}" alt="Logo BubbleTask" />
    <button
        onclick="window.location.href='{{ route('login') }}'"
        class="btn-start"
    >
        Let's Start!
    </button>
</body>
</html>
