<?= loadPartial('head') ?>

<?= loadPartial('navbar') ?>

<?= loadPartial('top-banner') ?>

<!-- Post a Book Form Box -->
    <section class="flex justify-center items-center mt-20">
      <div class="bg-white p-8 rounded-lg shadow-md w-full md:w-600 mx-6">
        <h2 class="text-4xl text-center font-bold mb-4">Create a Book Presentation</h2>
        <!-- <div class="message bg-red-100 p-3 my-3">This is an error message.</div>
        <div class="message bg-green-100 p-3 my-3">
          This is a success message.
        </div> -->
        <form method="POST" action="../books">

          <br />
          <h2 class="text-2xl font-bold mb-6 text-center text-gray-500">
            Book Info
          </h2>

          <div class="mb-4">
            <input
              type="text"
              name="title"
              placeholder="Book Title"
              class="w-full px-4 py-2 border rounded focus:outline-none"
            />
          </div>
          <div class="mb-4">
            <textarea
              name="description"
              placeholder="Book Description"
              class="w-full px-4 py-2 border rounded focus:outline-none"
            ></textarea>
          </div>
          <div class="mb-4">
            <input
              type="text"
              name="year"
              placeholder="Release Year"
              class="w-full px-4 py-2 border rounded focus:outline-none"
            />
          </div>
          <div class="mb-4">
            <input
              type="text"
              name="isbn"
              placeholder="ISBN"
              class="w-full px-4 py-2 border rounded focus:outline-none"
            />
          </div>
          <div class="mb-4">
<!-- the novel type -->
            <select
              name="genre"
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
              name="company"
              class="w-full px-4 py-2 border rounded focus:outline-none"
            >
               <?php foreach($editors as $editor) : ?>
                   <option value="<?= $editor->EDITOR_ID ?>">
                   <?= $editor->EDITOR_NAME ?></option>
               <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-4">
            <select
              name="author"
              class="w-full px-4 py-2 border rounded focus:outline-none"
            >
                <?php foreach($authors as $author) : ?>
                  <option value="<?= $author->PERS_ID ?>">
                  <?= $author->AUTH_NAME ?></option>
                <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-4">
            <select
              name="collection"
              class="w-full px-4 py-2 border rounded focus:outline-none"
            >
            <?php foreach($collections as $collection) : ?>
                <option value="<?= $collection->collection_id ?>">
                <?= $collection->collection_name ?></option>
            <?php endforeach; ?>
            </select>
          </div>
          <button
            class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 my-3 rounded focus:outline-none"
          >
            Save
          </button>
          <a
            href="/"
            class="block text-center w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded focus:outline-none"
          >
            Cancel
          </a>
        </form>
      </div>
    </section>

<?= loadPartial('bottom-banner') ?>

<?= loadPartial('footer') ?>

