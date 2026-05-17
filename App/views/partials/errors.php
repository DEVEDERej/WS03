<?php if (isset($errors) && !empty($errors)) : ?>
    <div class="message bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative my-4" role="alert">
        <ul class="list-disc list-inside">
            <?php foreach ($errors as $error) : ?>
                <li class="font-medium"><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
