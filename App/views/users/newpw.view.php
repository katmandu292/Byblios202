<?= loadPartial('head') ?>
<?= loadPartial('navbar') ?>

<div class="flex justify-center items-center mt-20">
  <div class="bg-white p-8 rounded-lg shadow-md w-full md:w-500 mx-6">
    <h2 class="text-4xl text-center font-bold mb-4">Password Reset</h2>
    <p>&nbsp;<br /></p>
<?php if(isset($errors)) : ?>
    <?= loadPartial('errors', [
      'errors' => $errors ?? []
    ]) ?>
<?php endif; ?>
    <form method="POST" action="/byblios/auth/change/<?= $token ?>">
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="UPDATED_AT" value="<?= date('Y-m-d H:i:s') ?>" />
      <div class="mb-4">
        <input type="password" name="password" placeholder="Password" class="w-full px-4 py-2 border rounded focus:outline-none" />
      </div>
      <div class="mb-4">
        <input type="password" name="password_confirmation" placeholder="Confirm Password" class="w-full px-4 py-2 border rounded focus:outline-none" />
      </div>
      <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded focus:outline-none">
        Reset Password
      </button>

      <p class="mt-4 text-gray-500">
        Don't have an account?
        <a class="text-blue-900" href="/byblios/auth/register">Register</a>
      </p>
    </form>
  </div>
</div>

<?= loadPartial('footer') ?>

