<?= loadPartial('head') ?>
<?= loadPartial('navbar') ?>
<?= loadPartial('top-banner') ?>
<?php
  use Framework\Authorization;
?>

<section class="container mx-auto p-4 mt-4">
  <div class="rounded-lg shadow-md bg-white p-3">
<?=  loadPartial('flash') ?>
    <div class="flex justify-between items-center">
      <a class="block p-4 text-blue-700" href="/byblios">
        <i class="fa fa-arrow-alt-circle-left"></i>
        Back To Book List
      </a>
<?php   if (Authorization::isOwner($book->OWNER_ID)) : ?>
        <div class="flex space-x-4 ml-4">
          <a href="/byblios/book/edit/<?= $book->VOLUME_ID ?>" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded">Edit</a>
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
      <h2 class="text-xl font-semibold"><?= $book->VOL_TITLE ?></h2>
      <p class="text-gray-700 text-lg mt-2">
        <?= $book->VOL_INFO ?>
      </p>
      <ul class="my-4 bg-gray-100 p-4">
        <li class="mb-2"><strong>Genre:</strong> <?= $book->GENRE_LABEL ?></li>
        <li class="mb-2">
          <strong>Author:</strong> <?= $book->AUTH_NAME ?>
          <!-- <span class="text-xs bg-blue-500 text-white rounded-full px-2 py-1 ml-2">Local</span> -->
        </li>
        <li class="mb-2">
          <strong>Published By:</strong> <?= $book->EDITOR_NAME ?>
        </li>
      </ul>
    </div>
  </div>
</section>

<section class="container mx-auto p-4">
  <h2 class="text-xl font-semibold mb-4">Book Details</h2>
  <div class="rounded-lg shadow-md bg-white p-4">
    <h3 class="text-lg font-semibold mb-2 text-blue-500">
      Release Year
    </h3>
    <p>
      <?= $book->LAUNCH_YEAR ?>
    </p>
    <h3 class="text-lg font-semibold mt-4 mb-2 text-blue-500">ISBN</h3>
    <p><?= $book->ISBN ?></p>
  </div>
  <p class="my-5">
    Put "AUTHOR_ID" as the subject of your email and attach your resume.
  </p>
  <a href="mailto:<?= $book->AUTHOR_ID ?>" class="block w-full text-center px-5 py-2.5 shadow-sm rounded border text-base font-medium cursor-pointer text-indigo-700 bg-indigo-100 hover:bg-indigo-200">
    Apply Now
  </a>
</section>

<?= loadPartial('bottom-banner') ?>
<?= loadPartial('footer') ?>

