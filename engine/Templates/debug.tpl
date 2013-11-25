<!DOCTYPE html>
<html>
<head>
    <title>Atome Core Error</title>
    <style type="text/css">
        html {
            padding: 0;
            margin: 0;
        }

        a {
            color: #333333;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        body {
            padding: 30px;
            margin: 0 auto;
            font: 16px;
            font-family: sans-serif;
            background: #dddddd;
            width: 900px;
        }

        .well {
            text-align: left;
            padding: 30px;
            margin: 5px;
            background: #eeeeee;
            border: 1px solid #bebebe;
            -webkit-box-shadow: 0 0 5px 0 #bfbfbf;
            box-shadow: 0 0 5px 0 #bfbfbf;
        }

        .well-title {
            font-size: 22px;
            margin-top: -30px;
            margin-bottom: 10px;
            background: #333333;
            padding: 10px;
            padding-left: 30px;
            font-weight: bolder;
            border-bottom: 3px solid #282828;
            color: #ffffff;
            text-shadow: 1px 1px 1px #000;
            filter: dropshadow(color=#000, offx=1, offy=1);
        }

        .copy {
            color: #333333;
            padding-left: 40px;
            font-size: 32px;
            text-shadow: 1px 1px 1px #808080;
            filter: dropshadow(color=#808080, offx=1, offy=1);
        }
    </style>
</head>
<body>
<div class="copy">
    Atome Core Error
</div>
<div class="well">
    <div class="well-title">Error {code} in {file}:{line}</div>
    <p><b>Error code:</b> {code}</p>

    <p><b>Error file:</b> {file}</p>

    <p><b>Error line:</b> {line}</p>

    <p><b>Error message:</b> {message}</p>
    <hr>
    <p><b>Trace:</b></p>
    <pre>{trace}</pre>
</div>
<p style="text-align: center;">
    Please report this error on <a target="_blank" href="http://www.atome.su">www.atome.su</a>
</p>
</body>
</html>