<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>Document</title>
</head>
<body class="bg-black-300 ">
<div
  class="flex items-center justify-between border-b border-gray-200 bg-gray-100 px-4 py-2 text-gray-900 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
>
  <span> </span>

  <p class="text-center font-medium">
    Obat Terbaik Di Jonggol
    <a href="#" class="inline-block underline"> Diskon 50% </a>
  </p>

  <button
    type="button"
    aria-label="Dismiss"
    class="rounded border border-gray-300 bg-white p-1.5 shadow-sm transition-colors hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-900 dark:hover:bg-gray-800"
  >
    <svg
      aria-hidden="true"
      xmlns="http://www.w3.org/2000/svg"
      fill="none"
      viewBox="0 0 24 24"
      stroke-width="1.5"
      stroke="currentColor"
      class="size-5"
    >
      <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
    </svg>
  </button>
</div>
    <x-navbar></x-navbar>
    <section class="bg-white lg:grid lg:h-screen lg:place-content-center dark:bg-gray-900">
  <div
    class="mx-auto w-screen max-w-7xl px-4 py-16 sm:px-6 sm:py-24 md:grid md:grid-cols-2 md:items-center md:gap-4 lg:px-8 lg:py-32"
  >
    <div class="max-w-prose text-left">
      <h1 class="text-4xl font-bold text-gray-900 sm:text-5xl dark:text-white">
        Understand user flow and
        <strong class="text-indigo-600"> increase </strong>
        conversions
      </h1>

      <p class="mt-4 text-base text-pretty text-gray-700 sm:text-lg/relaxed dark:text-gray-200">
        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eaque, nisi. Natus, provident
        accusamus impedit minima harum corporis iusto.
      </p>

      <div class="mt-4 flex gap-4 sm:mt-6">
        <a
          class="inline-block rounded border border-indigo-600 bg-indigo-600 px-5 py-3 font-medium text-white shadow-sm transition-colors hover:bg-indigo-700"
          href="#"
        >
          Get Started
        </a>

        <a
          class="inline-block rounded border border-gray-200 px-5 py-3 font-medium text-gray-700 shadow-sm transition-colors hover:bg-gray-50 hover:text-gray-900 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-800 dark:hover:text-white"
          href="#"
        >
          Learn More
        </a>
      </div>
    </div>

    <img src="/images/plant-cropped-transparent.svg" alt="" class="w-100 h-100">
  </div>
</section>

<section class="bg-gray-300 px-4 py-10">
    <div class="mx-auto grid max-w-7xl grid-cols-1 lg:grid-cols-3 md:grid-cols-2">

      @foreach($medicines as $medicines)
<a href="/obat/{{ $medicines->id}}" class="group block overflow-hidden">
  <img
    src="{{ asset('gambars/'.$medicines->medicine_image)}}"
    alt=""
    class="h-87.5 w-full object-cover sm:h-112.5"
  />
  <div class="p-4 sm:p-6">
    <h3 class="text-lg font-bold text-gray-900">{{ $medicines->name }}</h3>
    <p class="text-xl mt-2 text-indigo-600">Rp. {{ number_format($medicines->price,0,',', '.') }}</p>
    <p class="mt-3 text-sm heading-relaxed">{{ $medicines->description }}</p>
    <div class="mt-4 border-t border-gray-200 pt-3">
      <p>tanggal kadaluwarsa</p>
      <p>{{ \Carbon\Carbon::parse($medicines->expired) }}</p>
    </div>
  </div>
</a>
      @endforeach


    </div>
</section>
    <x-footer></x-footer>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  @if(session('sukses'))
    <script>
      Swal.fire({
        title: "berhasil",
        text:@json(session('sukses')),
        icon: "success"
      });
    </script>
  @endif

</body>
</html>