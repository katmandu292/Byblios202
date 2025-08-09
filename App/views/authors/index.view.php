<?= loadPartial('head') ?>

<?= loadPartial('navbar') ?>

<?= loadPartial('top-banner') ?>

    <!-- Authors list -->
    <section>
      <div class="container mx-auto p-4 mt-4">
        <div class="text-center text-3xl mb-4 font-bold border border-gray-300 p-3">
           All Authors
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
  <?php foreach($authors as $writer) : ?>
          <!-- Writer <?= $writer->PERS_ID ?> Listing -->
          <div class="rounded-lg shadow-md bg-white">
            <div class="p-4">
              <h2 class="text-xl font-semibold"><?= $writer->AUTH_NAME ?></h2>
              <p class="text-gray-700 text-lg mt-2">
                <?= substr($writer->AUTH_BIO,0,132) ?>
              </p>
              <ul class="my-4 bg-gray-100 p-4 rounded">
                <li class="mb-2"><strong>Owned by:</strong> <?= $writer->OWNER_ID ?></li>
                <li class="mb-2">
                  <strong>Submitted By:</strong> <?= $writer->USER_FULLNAME ?>
                  <span
                    class="text-xs bg-blue-500 text-white rounded-full px-2 py-1 ml-2"
                    ><?= $writer->OWNER_ID ?></span
                  >
                </li>
                <li class="mb-2">
                  <strong>Location:</strong> <span><?= $writer->ADDRESS ?></span>
<!--             ,<span>Coding</span  -->
                </li>
              </ul>
              <a href="/byblios/authors/show/<?= $writer->AUTH_BIO ?>"
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
