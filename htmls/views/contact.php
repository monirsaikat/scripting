<?php $this->layout('layouts/main', ['title' => $pageTitle]) ?>

<section class="page page--compact">
    <div class="form-card">
        <h1 class="form-card__title">Contact</h1>
        <p class="form-card__text">Send a message and the server will handle it with a normal PHP form flow.</p>

        <form class="form-grid" method="POST" <?= up_form_attrs() ?>>
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

            <div class="form-field">
                <label for="name" class="form-field__label">Name</label>
                <input type="text" name="name" value="<?= old('name') ?>" class="form-control" id="name" placeholder="John Doe" autofocus>
            </div>

            <div class="form-field">
                <label for="email" class="form-field__label">Email</label>
                <input type="email" name="email" value="<?= old('email') ?>" class="form-control" id="email" placeholder="name@example.com">
            </div>

            <div class="form-field">
                <label for="message" class="form-field__label">Message</label>
                <textarea class="form-control" name="message" id="message" rows="4" placeholder="How can we help?"></textarea>
            </div>

            <div class="form-actions">
                <button class="button button--primary">Submit</button>
            </div>
        </form>
    </div>
</section>
