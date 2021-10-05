<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="favicon.ico" type="image/x-icon" />

    <!-- bootstrap -->

    <link rel="stylesheet" href="/bootstrap/5.0.2/bootstrap.min.css">
    <script src="/bootstrap/5.0.2/bootstrap.bundle.min.js"></script>

    <!-- if you wanna use separate scripts for popper and bootstrap -->

    <!-- <script src="/bootstrap/5.0.2/bootstrap.min.js"></script> -->
    <!-- <script src="/bootstrap/5.0.2/popper.min.js"></script> -->

    <!-- /bootstrap -->

    <link rel="stylesheet" href="{ mix('css/admin.css') }}">

    <title>Library SDU - Admin Panel</title>
</head>

<body>
    <div id="app">
        <app></app>
    </div>
    <script src="{{ mix('js/main.js') }}"></script>
</body>

</html>