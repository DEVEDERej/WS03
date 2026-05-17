<?php loadPartial('head'); ?>
<?php loadPartial('navbar'); ?>

<style>
/* Error page specific overrides */
.error-page-container {
    background-color: #f3f4f6 !important;
    min-height: calc(100vh - 200px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 3rem 1rem;
}

.error-card {
    background-color: #ffffff !important;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 3rem 2rem;
    text-align: center;
    max-width: 600px;
    width: 100%;
}

.error-code {
    font-size: 6rem;
    font-weight: 700;
    color: #dc3545 !important;
    margin-bottom: 1rem;
    line-height: 1;
}

.error-title {
    font-size: 2rem;
    font-weight: 600;
    color: #1f2937 !important;
    margin-bottom: 1rem;
}

.error-description {
    font-size: 1rem;
    color: #6b7280 !important;
    margin-bottom: 0.5rem;
    line-height: 1.5;
}

.error-btn {
    display: inline-block;
    padding: 0.75rem 1.5rem;
    background-color: #3b82f6 !important;
    color: #ffffff !important;
    font-weight: 600;
    border-radius: 6px;
    text-decoration: none;
    margin-top: 1.5rem;
    transition: background-color 0.3s ease;
}

.error-btn:hover {
    background-color: #2563eb !important;
    opacity: 1 !important;
}

.error-btn i {
    margin-right: 0.5rem;
}
</style>

<div class="error-page-container">
    <div class="error-card">
        <h1 class="error-code"><?= htmlspecialchars($status) ?></h1>
        <h2 class="error-title">Page Not Found</h2>
        <p class="error-description">Sorry, the page you are looking for could not be found.</p>
        <p class="error-description">The page may have been moved, deleted, or never existed.</p>
        <a href="<?= url('/') ?>" class="error-btn">
            <i class="fa fa-home"></i>Go Back Home
        </a>
    </div>
</div>

<?php loadPartial('footer'); ?>
