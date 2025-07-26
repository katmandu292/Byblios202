<?= loadPartial('head') ?>

<?= loadPartial('navbar') ?>

<?= loadPartial('showcase-search') ?>

<?= loadPartial('top-banner') ?>

    <!-- Job Listings -->
    <section>
      <div class="container mx-auto p-4 mt-4">
        <div class="text-center text-3xl mb-4 font-bold border border-gray-300 p-3">Recent Books</div>

<?= loadPartial('flash') ?>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

        <?php foreach($books as $book) : ?>

          <!-- Book Listing N -->
          <div class="rounded-lg shadow-md bg-white">
            <div class="p-4">
              <h2 class="text-xl font-semibold"><?= $book->VOL_TITLE ?></h2>
              <p class="text-gray-700 text-lg mt-2">
                <?= substr($book->VOL_INFO,0,131) ?>...
              </p>
              <ul class="my-4 bg-gray-100 p-4 rounded">
                <li class="mb-2"><strong>Published By:</strong> <?= $book->EDITOR_NAME ?></li>
                <li class="mb-2">
                  <strong>Written By:</strong> <?= $book->AUTH_NAME ?>
                  <span
                    class="text-xs bg-blue-500 text-white rounded-full px-2 py-1 ml-2"
                    ><?= $book->LAUNCH_YEAR ?></span
                  >
                </li>
                <li class="mb-2">
                  <strong>Genre:</strong> <span>Development</span>
<!--             ,<span>Coding</span> -->
                </li>
              </ul>
              <a href="/byblios/book/show/<?= $book->VOLUME_ID ?>"
                class="block w-full text-center px-5 py-2.5 shadow-sm rounded border text-base font-medium text-indigo-700 bg-indigo-100 hover:bg-indigo-200"
              >
                Details
              </a>
            </div>
          </div>

        <?php endforeach; ?>
        </div>
      </div>

        <a href="/byblios/book" class="block text-xl text-center">
          <i class="fa fa-arrow-alt-circle-right"></i>
          Show All Books
        </a>
      </section>

<?= loadPartial('bottom-banner') ?>

<?= loadPartial('footer') ?>

