<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $detail->name }}</title>
    @vite('resources/css/app.css')
</head>
<body>
    <x-navbar></x-navbar>
    <nav aria-label="Breadcrumb" class="mx-auto max-w-7xl px-4 ">
  <ol class="flex items-center gap-1 text-sm text-gray-700">
    <li>
      <a href="#" class="block transition-colors hover:text-gray-900" aria-label="Home">
        <svg
          aria-hidden="true"
          xmlns="http://www.w3.org/2000/svg"
          class="size-4"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
          />
        </svg>
      </a>
    </li>

    <li class="rtl:rotate-180">
      <svg
        aria-hidden="true"
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        stroke-width="1.5"
        stroke="currentColor"
        class="size-4"
      >
        <path stroke-linecap="round" stroke-linejoin="round" d="m9 20.247 6-16.5" />
      </svg>
    </li>

    <li>
      <a href="#" class="block transition-colors hover:text-gray-900"> Detail </a>
    </li>

    
  </ol>
</nav>
<input type="checkbox" id="nav-toggle" class="peer sr-only" />



<main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 sm:py-12 lg:px-8">
  
  <div class="mt-6 grid gap-10 lg:grid-cols-2 lg:gap-16">
    <div>
      <div class="overflow-hidden rounded-lg bg-gray-100">
        <img
          id="ProductGalleryImage"
          alt="Kestrel 2P Ultralight tent pitched on a ridgeline at dawn"
          src="{{ asset('gambars/'.$detail->medicine_image) }}"
          class="aspect-square w-full object-cover"
        />
      </div>


    </div>

    <div>
      <h1 class="text-2xl font-semibold text-gray-900 sm:text-3xl">{{ $detail->name}}</h1>

      <a href="#reviews" class="mt-2 flex items-center gap-2">
        <span class="flex items-center gap-0.5 text-amber-500">
          <span class="sr-only">Rated 4 out of 5 stars</span>
          <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 20 20"
            fill="currentColor"
            class="size-4"
            aria-hidden="true"
          >
            <path
              d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401Z"
            />
          </svg>
          <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 20 20"
            fill="currentColor"
            class="size-4"
            aria-hidden="true"
          >
            <path
              d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401Z"
            />
          </svg>
          <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 20 20"
            fill="currentColor"
            class="size-4"
            aria-hidden="true"
          >
            <path
              d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401Z"
            />
          </svg>
          <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 20 20"
            fill="currentColor"
            class="size-4"
            aria-hidden="true"
          >
            <path
              d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401Z"
            />
          </svg>
          <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 20 20"
            fill="currentColor"
            class="size-4 text-gray-200"
            aria-hidden="true"
          >
            <path
              d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401Z"
            />
          </svg>
        </span>
        <span class="text-sm text-gray-500 underline underline-offset-2">126 reviews</span>
      </a>

      <p class="mt-4 text-2xl font-medium text-gray-900">Rp. {{ number_format($detail->price,0,',', '.') }}</p>

      <p class="mt-4 text-gray-600">
        {{ $detail->description }}
      </p>



      <div class="mt-6 flex flex-wrap items-end gap-4">
        <div>
          <label for="Quantity" class="block text-sm font-medium text-gray-900">Quantity</label>
          <div class="mt-1 flex items-center gap-1">
            <button
              type="button"
              class="size-10 leading-10 text-gray-600 transition hover:opacity-75"
            >
              &minus;
            </button>
            <input
              type="number"
              id="Quantity"
              value="1"
              class="h-10 w-16 rounded-md border-gray-300 text-center sm:text-sm"
            />
            <button
              type="button"
              class="size-10 leading-10 text-gray-600 transition hover:opacity-75"
            >
              &plus;
            </button>
          </div>
        </div>

        <button
          type="button"
          class="flex-1 rounded-md bg-emerald-700 px-6 py-3 text-sm font-medium text-white transition hover:bg-emerald-800"
        >
          Add to cart
        </button>
      </div>

      <p>Expired date : {{Carbon\Carbon::parse($detail->expired)->format('d-m-Y')}}</p>
    </div>
  </div>



  
</main>


<script>
  const navToggleCheckbox = document.getElementById('nav-toggle')
  const navToggleLabel = document.getElementById('nav-toggle-label')

  navToggleCheckbox.addEventListener('change', () => {
    navToggleLabel.setAttribute('aria-expanded', String(navToggleCheckbox.checked))
  })

  const tabs = document.querySelectorAll('[role="tab"]')

  const activateTab = (tab) => {
    tabs.forEach((otherTab) => {
      const isSelected = otherTab === tab

      otherTab.setAttribute('aria-selected', String(isSelected))
      otherTab.tabIndex = isSelected ? 0 : -1

      document.getElementById(otherTab.getAttribute('aria-controls')).hidden = !isSelected
    })
  }

  tabs.forEach((tab, index) => {
    tab.addEventListener('click', () => {
      activateTab(tab)
      tab.focus()
    })

    tab.addEventListener('keydown', (event) => {
      const targetIndex = {
        ArrowLeft: (index - 1 + tabs.length) % tabs.length,
        ArrowRight: (index + 1) % tabs.length,
        Home: 0,
        End: tabs.length - 1,
      }[event.key]

      if (targetIndex === undefined) return

      event.preventDefault()
      activateTab(tabs[targetIndex])
      tabs[targetIndex].focus()
    })
  })

  document.querySelector('a[href="#reviews"]').addEventListener('click', () => {
    activateTab(document.getElementById('tab-reviews'))
  })

  const galleryImage = document.getElementById('ProductGalleryImage')
  const galleryButtons = document.querySelectorAll('[data-gallery-image]')

  galleryButtons.forEach((button) => {
    button.addEventListener('click', () => {
      galleryButtons.forEach((otherButton) => {
        otherButton.setAttribute('aria-pressed', String(otherButton === button))
      })

      galleryImage.src = button.dataset.galleryImage
      galleryImage.alt = button.dataset.galleryAlt
    })
  })
</script>
</body>
</html>