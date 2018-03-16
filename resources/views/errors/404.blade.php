<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Page Not Found</title>
    <!-- Styles -->
    <style>
        html, body {
            background: linear-gradient(to top right, #69489c 10%, #69489c 65%, rgba(56, 52, 40, 0.9) 125%);
            color: #fff;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 36px;
            padding: 20px;
        }
        .title span{
            display: block;
            margin-bottom: 30px;
        }
        .home-btn{
            color: #fff;
            text-decoration: none;
            border: 2px solid;
            border-radius: 31px;
            font-size: 20px;
            padding: 10px 40px;
        }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height">
    <div class="content">
        <div class="title">
            <span style="font-size: 75px">
                404
            </span>

            <span>
                Sorry, the page you are looking for could not be found.
            </span>

            <a class="home-btn" href="{{url("/")}}">
                <small>Back to Home</small>
            </a>

        </div>

    </div>
</div>
</body>
</html>
