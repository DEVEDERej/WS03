<?php loadPartial('head'); ?>
<?php loadPartial('navbar'); ?>

<!-- Registration Form Box -->
<div class="flex justify-center items-center mt-20">
  <div class="bg-white p-8 rounded-lg shadow-md w-full md:w-500 mx-6">
    <h2 class="text-4xl text-center font-bold mb-4">Register</h2>
    
    <?php if (isset($errors) && count($errors) > 0): ?>
      <div class="message bg-red-100 p-3 my-3">
        <ul>
          <?php foreach ($errors as $error): ?>
            <li class="text-red-700"><?= $error ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
    
    <?php if (isset($success)): ?>
      <div class="message bg-green-100 p-3 my-3 text-green-700">
        <?= $success ?>
      </div>
    <?php endif; ?>
    
    <form method="POST" action="/register">
      <div class="mb-4">
        <input
          type="text"
          name="name"
          placeholder="Full Name"
          value="<?= isset($data['name']) ? htmlspecialchars($data['name']) : '' ?>"
          class="w-full px-4 py-2 border rounded focus:outline-none"
          required
        />
      </div>
      <div class="mb-4">
        <input
          type="email"
          name="email"
          placeholder="Email Address"
          value="<?= isset($data['email']) ? htmlspecialchars($data['email']) : '' ?>"
          class="w-full px-4 py-2 border rounded focus:outline-none"
          required
        />
      </div>
      <div class="mb-4">
        <input
          type="text"
          name="city"
          placeholder="City"
          value="<?= isset($data['city']) ? htmlspecialchars($data['city']) : '' ?>"
          class="w-full px-4 py-2 border rounded focus:outline-none"
          required
        />
      </div>
      <div class="mb-4">
        <input
          type="text"
          name="state"
          placeholder="State"
          value="<?= isset($data['state']) ? htmlspecialchars($data['state']) : '' ?>"
          class="w-full px-4 py-2 border rounded focus:outline-none"
          required
        />
      </div>
      <div class="mb-4">
        <input
          type="password"
          name="password"
          placeholder="Password"
          class="w-full px-4 py-2 border rounded focus:outline-none"
          required
        />
      </div>
      <div class="mb-4">
        <input
          type="password"
          name="password_confirmation"
          placeholder="Confirm Password"
          class="w-full px-4 py-2 border rounded focus:outline-none"
          required
        />
      </div>
      <button
        type="submit" 
        class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded focus:outline-none"
      >
        Register
      </button>

      <p class="mt-4 text-gray-500">
        Already have an account?
        <a class="text-blue-900" href="/login">Login</a>
      </p>
    </form>
  </div>
</div>

<?php loadPartial('footer'); ?>
