<?= loadPartial('head') ?>

<?= loadPartial('navbar') ?>

<?= loadPartial('top-banner') ?>

<!-- Post a Novelist Form Box -->
    <section class="flex justify-center items-center mt-20">
      <div class="bg-white p-8 rounded-lg shadow-md w-full md:w-600 mx-6">
        <h2 class="text-4xl text-center font-bold mb-4">Create a Writer&#39;s Profile</h2>

<?= loadPartial('flash') ?>

        <form method="POST" action="/byblios/authors">

          <br />
          <h2 class="text-2xl font-bold mb-6 text-center text-gray-500">
            Editor Info
          </h2>

<?php if(isset($errors)) : ?>
   <?php foreach ($errors as $error) : ?>
          <div class="message bg-red-100 my-3"><?= $error ?></div>
   <?php endforeach; ?>
<?php endif; ?>
          <div class="mb-4">
            <input
              type="text"
              name="AUTH_NAME"
              placeholder="Full Name"
              value="<?= $authorData->AUTH_NAME ?? '' ?>"
              class="w-full px-4 py-2 border rounded focus:outline-none"
            />
          </div>
          <div class="mb-4">
            <input
              type="text"
              name="BIRTH_YEAR"
              placeholder="Year of Birth"
              value="<?= $authorData->BIRTH_YEAR ?? '' ?>"
              class="w-full px-4 py-2 border rounded focus:outline-none"
            />
          </div>

          <br /><br />
          <h2 class="text-2xl font-bold mb-6 text-center text-gray-500">
            Author's Biography
          </h2>

          <div class="mb-4">
            <textarea
              name="AUTH_BIO"
              placeholder="Author Biography"
              class="w-full px-4 py-2 border rounded focus:outline-none"
             ><?= $authorData->AUTH_BIO ?? '' ?></textarea>
          </div>

          <button
            class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 my-3 rounded focus:outline-none"
          >
            Save
          </button>
          <a
            href="/byblios/authors"
            class="block text-center w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded focus:outline-none"
          >
            Cancel
          </a>
        </form>
      </div>
    </section>

<?= loadPartial('bottom-banner') ?>

<?= loadPartial('footer') ?>

