<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Company Registration</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body
  class="bg-gradient-to-br from-green-100 via-green-200 to-green-300 min-h-screen flex items-center justify-center px-4 py-8">

  <div class="w-full max-w-3xl bg-white rounded-xl shadow-lg p-8 space-y-6">
    <div class="text-center">
      <h1 class="text-4xl font-bold text-green-700 mb-2">Company Registration</h1>
      <p class="text-sm text-gray-500">Register your company and admin account</p>
    </div>

    <form method="POST" action="registration2.php" class="space-y-6" enctype="multipart/form-data">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Company Details -->
        <div class="space-y-4">
          <h3 class="text-xl font-semibold text-gray-700">🏢 Company Details</h3>

          <div>
            <label class="block text-sm font-medium text-gray-700">Company Name</label>
            <input type="text" name="company_name" required
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Logo</label>
            <input type="file" name="logo" id="logo" accept=".jpg,.jpeg,.png">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="company_description" required
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500"></textarea>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Industry</label>
            <input type="text" name="company_industry" required
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Website</label>
            <input type="url" name="company_website"
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Address</label>
            <textarea name="company_address" required
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500"></textarea>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Contact Email</label>
            <input type="email" name="company_contact_email" required
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Contact Phone</label>
            <input type="text" name="company_contact_phone" required
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500" />
          </div>
        </div>

        <!-- Admin Details -->
        <div class="space-y-4">
          <h3 class="text-xl font-semibold text-gray-700">👤 Admin Details</h3>

          <div>
            <label class="block text-sm font-medium text-gray-700">Admin Name</label>
            <input type="text" name="admin_name" required
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Admin Email</label>
            <input type="email" name="admin_email" required
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" name="admin_password" required
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500" />
          </div>
        </div>
      </div>

      <button type="submit"
        class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-md transition duration-300 shadow">
        ✅ Register
      </button>

      <div class="text-center pt-4 border-t">
        <p class="text-sm text-gray-600">
          Already have an account?
          <a href="login.php" class="text-green-700 font-medium hover:underline">Login here</a>
        </p>
      </div>
    </form>
  </div>

</body>

</html>