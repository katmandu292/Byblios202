<?= loadPartial('head') ?>
<?= loadPartial('navbar') ?>

<div class="flex justify-center items-center mt-20">
  <div class="bg-white p-8 rounded-lg shadow-md w-full md:w-500 mx-6">
    <h2 class="text-4xl text-center font-bold mb-4">Pasword Reset</h2>
<?php if(isset($errors)) : ?>
    <?= loadPartial('errors', [
      'errors' => $errors ?? []
    ]) ?>
<?php endif; ?>
      <div class="mb-4">
        <p>&nbsp;</p>
        <p class="text-gray-700 text-lg mt-2">
           A reset token was generated, valid until <?= $expires ?>
        </p>
      </div>

      <p class="mt-4 text-gray-500">
        Don't have an account?
        <a class="text-blue-900" href="/byblios/auth/register">Register</a>
      </p>
  </div>
</div>

<?= loadPartial('footer') ?>
