<?= loadPartial('head') ?>

<?= loadPartial('navbar') ?>

<?= loadPartial('top-banner') ?>

    <!-- Book Listings -->
    <section>
      <div class="container mx-auto p-4 mt-4">
        <div class="text-center text-3xl mb-4 font-bold border border-gray-300 p-3">
           Recent Books
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

  <?php foreach($books as $book) : ?>

          <!-- Job Listing <?= $book->VOLUME_ID ?> -->
          <div class="rounded-lg shadow-md bg-white">
            <div class="p-4">
              <h2 class="text-xl font-semibold"><?= $book->VOL_TITLE ?></h2>
              <p class="text-gray-700 text-lg mt-2">
                <?= substr($book->VOL_INFO,1,132) ?>
              </p>
              <ul class="my-4 bg-gray-100 p-4 rounded">
                <li class="mb-2"><strong>Salary:</strong> $80,000</li>
                <li class="mb-2">
                  <strong>Location:</strong> New York
                  <span
                    class="text-xs bg-blue-500 text-white rounded-full px-2 py-1 ml-2"
                    ><?= $book->GENRE_LABEL ?></span
                  >
                </li>
                <li class="mb-2">
                  <strong>Tags:</strong> <span>Development</span>,
                  <span>Coding</span>
                </li>
              </ul>
              <a href="book/<?= $book->VOLUME_ID ?>"
                class="block w-full text-center px-5 py-2.5 shadow-sm rounded border text-base font-medium text-indigo-700 bg-indigo-100 hover:bg-indigo-200">
                Details
              </a>
            </div>
          </div>
<?php endforeach ?>

        </div>
      <div>
    </section>

<?= loadPartial('bottom-banner') ?>

<?= loadPartial('footer') ?>

