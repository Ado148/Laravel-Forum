<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel 12 Tutorial</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-slate-100 dark:bg-slate-800">
    <x-navbar/>
    
    <div class="max-w-6xl mx-auto"> 
        {{ $slot }} <!-- Here will be rendered anything which is in the x-layout tags -->
    </div>
</body>
</html>