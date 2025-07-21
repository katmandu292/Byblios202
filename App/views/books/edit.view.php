<?= loadPartial('head') ?>

<?= loadPartial('navbar') ?>

<?= loadPartial('top-banner') ?>

<!-- Post a Book Form Box -->
    <section class="flex justify-center items-center mt-20">
      <div class="bg-white p-8 rounded-lg shadow-md w-full md:w-600 mx-6">
        <h2 class="text-4xl text-center font-bold mb-4">Edit a Book Presentation</h2>

<?= loadPartial('flash') ?>

        <form method="POST" action="/byblios/book/update/<?= $bookData->VOLUME_ID ?>">
          <input type="hidden" name="_method" value="PUT">
          <br />
          <h2 class="text-2xl font-bold mb-6 text-center text-gray-500">
            Book Info
          </h2>

          <div class="mb-4">
            <input
              type="text"
              name="VOL_TITLE"
              placeholder="Book Title"
              value="<?= $bookData->VOL_TITLE ?? '' ?>"
              class="w-full px-4 py-2 border rounded focus:outline-none"
            />
          </div>
          <div class="mb-4">
            <textarea
              name="VOL_INFO"
              placeholder="Book Description"
              class="w-full px-4 py-2 border rounded focus:outline-none"
            ><?= $bookData->VOL_INFO ?? '' ?></textarea>
          </div>
          <div class="mb-4">
            <input
              type="text"
              name="LAUNCH_YEAR"
              placeholder="Release Year"
              value="<?= $bookData->LAUNCH_YEAR ?? '' ?>"
              class="w-full px-4 py-2 border rounded focus:outline-none"
            />
          </div>
          <div class="mb-4">
            <input
              type="text"
              name="ISBN"
              placeholder="ISBN"
              value="<?= $bookData->ISBN ?? '' ?>"
              class="w-full px-4 py-2 border rounded focus:outline-none"
            />
          </div>
          <div class="mb-4">
            <select
              name="AUTHOR_ID"
              class="w-full px-4 py-2 border rounded focus:outline-none"
            >
<?php foreach($authors as $author) : ?>
                  <option value="<?= $author->PERS_ID ?>"><?= $author->AUTH_NAME ?></option>
<?php endforeach; ?>
            </select>
          </div>
          <div class="mb-4">
<!-- the novel type -->
            <select
              name="GENRE_ID"
              class="w-full px-4 py-2 border rounded focus:outline-none"
            >
               <?php foreach($genres as $genre) : ?>
                 <option value="<?= $genre->GENRE_ID ?>">
                 <?= $genre->GENRE_LABEL ?></option>
               <?php endforeach; ?>
            </select>
          </div>

          <br />
          <h2 class="text-2xl font-bold mb-6 text-center text-gray-500">
            Publisher Info
          </h2>

          <div class="mb-4">
<!-- the Publishing Company -->
            <select
              name="LAUNCHED_BY"
              class="w-full px-4 py-2 border rounded focus:outline-none"
            >
<?php foreach($editors as $editor) : ?>
                   <option value="<?= $editor->EDITOR_ID ?>"><?= $editor->EDITOR_NAME ?></option>
<?php endforeach; ?>
            </select>
          </div>

          <div class="mb-4">
            <select
              name="COLLECT_ID"
              class="w-full px-4 py-2 border rounded focus:outline-none"
            >
<?php foreach($collections as $collection) : ?>
                <option value="<?= $collection->collection_id ?>"><?= $collection->collection_name ?></option>
<?php endforeach; ?>
            </select>
          </div>
          <button
            class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 my-3 rounded focus:outline-none"
          >
            Save
          </button>
          <a
            href="/byblios/book/edit/<?= $bookData->VOLUME_ID ?>"
            class="block text-center w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded focus:outline-none"
          >
            Cancel
          </a>
        </form>
      </div>
    </section>

<?= loadPartial('bottom-banner') ?>

<?= loadPartial('footer') ?>

