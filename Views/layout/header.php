<!doctype html>
<html lang="ja">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Tailwind CSS -->
        <script src="https://unpkg.com/@tailwindcss/browser@4"></script>

        <title>Social Networking Service</title>
    </head>
    <body class="h-screen w-screen flex flex-col bg-white">
        <div class="flex justify-center w-full h-screen">
            <div class="h-full flex max-w-screen-lg w-screen">
                <div class="h-full w-60 flex flex-col border-r border-slate-200">
                    <!-- Logo -->
                    <?php include "Views/component/logo.php" ?>
                    <div class="flex-1 p-4 flex flex-col items-center space-y-5">
                        <!-- Navigation Item -->
                        <?php include "Views/component/navigation-item.php"  ?>
                    </div>
                    <!-- User Information -->
                    <?php include "Views/component/user-information.php" ?>
                </div>
                <main class="w-full h-full flex">

