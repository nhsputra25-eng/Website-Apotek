<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>register</title>
    @vite('resources/css/app.css')
</head>
<body class="items-center flex justify-center min-h-screen">


<form
  action="/register/action"
  method="POST"
  class="mx-auto max-w-md w-full space-y-4 rounded-lg border border-gray-300 bg-gray-100 p-6 dark:border-gray-600 dark:bg-gray-800"
>
  @csrf
  <div>
    <label class="block text-sm font-medium text-gray-900 dark:text-white" for="name">
      Name
    </label>

    <input
      class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-900 dark:text-white"
      id="name"
      type="text"
      name="name"
      placeholder="Your name"
    />
  </div>

  <div>
    <label class="block text-sm font-medium text-gray-900 dark:text-white" for="email">
      Email
    </label>

    <input
      class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-900 dark:text-white"
      id="email"
      type="email"
      name="email"
      placeholder="Your email"
    />
  </div>

  <div>
    <label class="block text-sm font-medium text-gray-900 dark:text-white" for="password">
      Password
    </label>

    <input
      class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-900 dark:text-white"
      id="password"
      type="password"
      name="password"
      placeholder="Your password"
    />
  </div>

@if($errors->any())
    <span class="text-red-500">
      @foreach ($errors->all() as $items)|
        {{ $items }}|
      @endforeach
    </span>
@endif


  <button
    class="block w-full rounded-lg border border-indigo-600 bg-indigo-600 px-12 py-3 text-sm font-medium text-white transition-colors hover:bg-transparent hover:text-indigo-600 dark:hover:bg-indigo-700 dark:hover:text-white"
    type="submit"
  >
    Send Message
  </button>
</form>
</body>
</html>