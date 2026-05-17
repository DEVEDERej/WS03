<?php 
use Framework\Session;
?>
<!-- Nav -->
<header class="bg-blue-900 text-white p-4">
    <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-3xl font-semibold">
            <a href="<?= url('/') ?>">Prosple</a>
        </h1>
        <nav class="space-x-4">
            <?php if (Session::has('user')) : ?>
                <div class="flex justify-between items-center gap-4">
<div>Welcome <strong><?= Session::get('user')['name'] ?></strong></div>
                    <form method="POST" action="<?= url('/logout') ?>" class="inline">
                        <button type="submit" class="text-white hover:underline">Logout</button>
                    </form>
                    <a
                        href="<?= url('/listings/create') ?>"
                        class="bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-2 rounded hover:shadow-md transition duration-300" style="color: #ffffff !important;"><i class="fa fa-edit"></i> Post a Job</a>
                    <button id="theme-toggle" class="text-white hover:bg-blue-800 px-3 py-2 rounded transition duration-300" aria-label="Toggle theme">
                        <i class="fas fa-sun" id="theme-icon"></i>
                    </button>
                </div>
            <?php else : ?>
                <a href="<?= url('/login') ?>" class="text-white hover:underline">Login</a>
                <a href="<?= url('/register') ?>" class="text-white hover:underline">Register</a>
                <a
                    href="<?= url('/listings/create') ?>"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded hover:shadow-md transition duration-300" style="color: #ffffff !important;"><i class="fa fa-edit"> </i> Post a Job</a>
                <button id="theme-toggle" class="text-white hover:bg-blue-800 px-3 py-2 rounded transition duration-300" aria-label="Toggle theme">
                    <i class="fas fa-sun" id="theme-icon"></i>
                </button>
            <?php endif; ?>
        </nav>
    </div>
</header>
