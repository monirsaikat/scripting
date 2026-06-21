<?php $this->layout('layouts/main', ['title' => $pageTitle]) ?>

<section class="py-5">
    <h1>Contact</h1>

    <form method="POST" <?= up_form_attrs() ?>>
        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" value="<?= old('name') ?>" class="form-control" id="name" placeholder="john doe" autofocus>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" value="<?= old('email') ?>"  class="form-control" id="email" placeholder="name@example.com">
        </div>

        <div class="mb-3">
            <label for="message" class="form-label">Message</label>
            <textarea class="form-control"  name="message" id="message" rows="3"></textarea>
        </div>

        <div class="d-flex justify-content-end">
            <button class="btn btn-primary">Submit</button>
        </div>
    </form>
</section>
