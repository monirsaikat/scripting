<?php $this->layout('layouts/admin', ['title' => $pageTitle]) ?>

<section class="py-5">
    <div class="mb-3 d-flex align-items-center justify-content-between">
        <h3>Users/Staff List</h3>

        <div class="d-flex  gap-2 align-items-center">
            <form method="get" action="<?= url('/staffs') ?>" <?= up_form_attrs() ?>>
                <input type="text" placeholder="Search.." name="search" class="form-control form-control-sm">
            </form>
            <button class="btn btn-primary btn-sm addBtn">Add New</button>
        </div>
    </div>

    <table class="table table-sm">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">First Name</th>
                <th scope="col">Last Name</th>
                <th scope="col">Email</th>
                <th scope="col">Phone</th>
                <th scope="col">Adress</th>
                <th scope="col">Created At</th>
                <th scope="col" class="text-end">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users->data as $index => $user): ?>
                <tr>
                    <th scope="row"><?= $index + 1 ?></th>
                    <td><?= $user->first_name ?></td>
                    <td><?= $user->last_name ?></td>
                    <td><?= $user->email ?></td>
                    <td><?= $user->phone ?></td>
                    <td><?= $user->address ?></td>
                    <td>
                        <?= timing($user->created_at)->format('d/m/Y h:i a') ?>
                        <br>
                        <strong>
                            <?= timing($user->created_at)->diffForHumans() ?>
                        </strong>
                    </td>
                    <td class="text-end">
                        <button 
                            class="btn btn-primary btn-sm btnEdit" 
                            data-url="<?= router()->route('staff.update', ['id' => $user->id]) ?>"
                            data-first-name="<?= $user->first_name ?>" 
                            data-last-name="<?= $user->last_name ?>" 
                            data-email="<?= $user->email ?>" 
                            data-phone="<?= $user->phone ?>" 
                            data-address="<?= $user->address ?>" 
                            data-id="<?= $user->id ?>">edit</button>
                        <button class="btn btn-danger btn-sm hasConfirmation" data-url="<?= router()->route('staff.delete', ['id' => $user->id]) ?>">delete</button>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>

    <div class="d-flex justify-content-end">
        <?php echo $users->links ?>
    </div>
</section>

<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Modal</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="<?= url('/staffs') ?>" data-create-url="<?= url('/staffs') ?>" <?= up_form_attrs() ?>>
                <!-- csrf token must be added here. -->
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

                <div class="modal-body">
                    <div class="form-group">
                        <label for="first_name" class="form-label">First Name</label>
                        <input class="form-control" placeholder="Enter your first name" id="first_name" name="first_name" />
                    </div>
                    <div class="form-group my-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input class="form-control" placeholder="Enter your last name" id="last_name" name="last_name" />
                    </div>
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" placeholder="Enter your email" id="email" name="email" />
                    </div>
                    <div class="form-group my-3">
                        <label for="phone_number" class="form-label">Phone Number</label>
                        <input class="form-control" placeholder="Enter your phone number" id="phone_number" name="phone" />
                    </div>
                    <div class="form-group">
                        <label for="address" class="form-label">Address</label>
                        <input class="form-control" placeholder="Enter your address" id="address" name="address" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

