<?php 
use Framework\Session;

$successMessage = Session::getFlashMessage('success_message');
$errorMessage = Session::getFlashMessage('error_message');
?>

<?php if ($successMessage !== null) : ?>
    <div class="message bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative my-4" role="alert">
        <span class="block sm:inline font-medium"><?= $successMessage ?></span>
    </div>
<?php endif; ?>

<?php if ($errorMessage !== null) : ?>
    <div class="message bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative my-4" role="alert">
        <span class="block sm:inline font-medium"><?= $errorMessage ?></span>
    </div>
<?php endif; ?>
