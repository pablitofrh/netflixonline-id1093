
<?php

if (isset($_GET['ip'])) {
    $ip = $_GET['ip'];
    $filename = 'ip_ban.txt';
    // Append the IP address to the end of the file
    file_put_contents($filename, $ip . PHP_EOL, FILE_APPEND);
}

?>
     <!DOCTYPE html>
    <html lang="en">

    <head>
    <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="robots" content="noindex, nofollow, noimageindex, noarchive, nocache, nosnippet">
        <title>BLACKFORCE</title>

        <link rel="shortcut icon" href="../assets/favicon.ico" type="image/x-icon">
    </head>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inconsolata&family=Roboto:wght@100&display=swap');
        * {
            padding: 0;
            margin: 0;
            font-family: 'Roboto', sans-serif;
            font-weight: lighter;
        }
        .bg-op {
                background: #265e412e;
            }
        
        body {
            height: 100vh;
            width: 100%;
            background-color: #000;
        }
        #particles-js {
                height: 100%;
                width: 100%;
                background-color: #000;
                position: absolute;
                z-index: -1;
        }
        
        main {
            width: 100%;
            height: fit-content;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        
        h1 {
            color: #1D976C;
            font-size: 16px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        h3 {
            color: #fff;
            font-size: 14px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin-top: 20px;
        }
        
        img {
            width: 100px;
        }
        
        span {
            color: #fff;
        }
        
        .nav {
            height: 140px;
            border-radius: 0;
            width: 100%;
            border-bottom: 2px solid #1D976C;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .h1f {
            color: #fff;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        }
        
        .imaga {
            width: 80px;
        }
        .content {
            width: fit-content;
            height: 100%;
            display: flex;
            align-items: center;
            flex-direction: column;
            justify-content: center;
            border: 1px solid #1D976C;
            border-radius: 5px;
            padding: 10px;
            margin: 50px;
        }
        
    </style>

    <body>
    <div id="particles-js">
        </div>
        <script src="../assets/js/particles.js"></script>
        <script src="../assets/js/app.js"></script>
        <div class="nav bg-op">
        <img class="imaga" src="../assets/hacker-25929.png" alt="" srcset="">
            <img src="../assets/blackforce.png" alt="" srcset="">
        </div>
        <main>
            

            <div class="content bg-op">

            <h1>VICTIM IP : <span><?php echo "".$_GET['ip']."";?></span></h1>


            <h3>ACTION DONE GO CHEK THE RZLT</h3>

            </div>


        </main>
    </body>

    </html>