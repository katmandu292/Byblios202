<?= loadPartial('head') ?>
<?= loadPartial('navbar') ?>
<?= loadPartial('top-banner') ?>
<?php
  use Framework\Session;
  use Framework\Authorization;
?>

<section class="container mx-auto p-4 mt-4">
  <div class="rounded-lg shadow-md bg-white p-3">
<?=  loadPartial('flash') ?>
    <div class="flex justify-between items-center">
      <a class="block p-4 text-blue-700" href="/byblios/authors">
        <i class="fa fa-arrow-alt-circle-left"></i>
        Back To Authors List
      </a>
<?php   if (Authorization::isOwner($author->OWNER_ID)) : ?>
        <div class="flex space-x-4 ml-4">
          <a href="/byblios/authors/edit/<?= $author->PERS_ID ?>" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded">Edit</a>
          <form method="POST">
          <!-- Here Delete Form -->
            <input type="hidden" name="_method" value="DELETE">
            <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded">Delete</button>
          <!-- End Delete Form -->
          </form>
        </div>
<?php  endif; ?>
    </div>
    <div class="p-4">
      <h2 class="text-xl font-semibold"><?= $author->AUTH_NAME ?></h2>
      <p class="text-gray-700 text-lg mt-2">
        <?= $author->PERS_ID ?>
      </p>
      <ul class="my-4 bg-gray-100 p-4">
        <li class="mb-2">
          <strong>Owned By:</strong> <?= $author->USER_NAME ?>
          <!-- <span class="text-xs bg-blue-500 text-white rounded-full px-2 py-1 ml-2">Local</span> -->
        </li>
        <li class="mb-2">
          <strong>Submitted By:</strong> <?= $author->USER_FULLNAME ?>
        </li>
      </ul>
    </div>
  </div>
</section>

<section class="container mx-auto p-4">
  <h2 class="text-xl font-semibold mb-4">Publisher Details</h2>
  <div class="rounded-lg shadow-md bg-white p-4">
    <h3 class="text-lg font-semibold mt-4 mb-2 text-blue-500">Short Bio</h3>
    <p><?= $author->AUTH_BIO ?></p>
  </div>
  <p class="my-5">
    User from Session: <?= var_dump(Session::get('user')['id']) ?>, Editor Owner: <?= var_dump($author->OWNER_ID) ?>
  </p>
  <a href="mailto:<?= $author->OWNER_ID ?>" class="block w-full text-center px-5 py-2.5 shadow-sm rounded border text-base font-medium cursor-pointer text-indigo-700 bg-indigo-100 hover:bg-indigo-200">
    Apply Now
  </a>
</section>

<?= loadPartial('bottom-banner') ?>
<?= loadPartial('footer') ?>

