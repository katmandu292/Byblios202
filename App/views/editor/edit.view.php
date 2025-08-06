<?= loadPartial('head') ?>

<?= loadPartial('navbar') ?>

<?= loadPartial('top-banner') ?>

<!-- Edit a Book Publisher Box -->
    <section class="flex justify-center items-center mt-20">
      <div class="bg-white p-8 rounded-lg shadow-md w-full md:w-600 mx-6">
        <h2 class="text-4xl text-center font-bold mb-4">Edit a Publisher</h2>

<?= loadPartial('flash') ?>

        <form method="POST" action="/byblios/editor/update/<?= $publisherData->EDITOR_ID ?>">
          <input type="hidden" name="_method" value="PUT">
          <input type="hidden" name="UPDATED_AT" value="<?= date('Y-m-d H:i:s') ?>">
          <br />
          <h2 class="text-2xl font-bold mb-6 text-center text-gray-500">
            Editor Info
          </h2>

          <div class="mb-4">
            <input
              type="text"
              name="EDITOR_NAME"
              placeholder="Editor's Brand"
              value="<?= $publisherData->EDITOR_NAME ?? '' ?>"
              class="w-full px-4 py-2 border rounded focus:outline-none"
            />
          </div>
          <div class="mb-4">
            <input
              type="text"
              name="ADDRESS"
              placeholder="Location"
              value="<?= $publisherData->ADDRESS ?? '' ?>"
              class="w-full px-4 py-2 border rounded focus:outline-none"
            />
          </div>

          <br /><br />
          <h2 class="text-2xl font-bold mb-6 text-center text-gray-500">
            Publisher Info
          </h2>

          <div class="mb-4">
             <textarea
              name="EDITOR_INFO"
              placeholder="Editor Description"
              class="w-full px-4 py-2 border rounded focus:outline-none"
             ><?= $publisherData->EDITOR_INFO ?? '' ?></textarea>            
          </div>
          <button
            class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 my-3 rounded focus:outline-none"
          >
            Save
          </button>
          <a
            href="/byblios/book/edit/<?= $publisherData->EDITOR_ID ?>"
            class="block text-center w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded focus:outline-none"
          >
            Cancel
          </a>
        </form>
      </div>
    </section>

<?= loadPartial('bottom-banner') ?>

<?= loadPartial('footer') ?>

