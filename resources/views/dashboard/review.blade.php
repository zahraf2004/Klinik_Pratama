<section class="py-14 bg-white">
  <div class="max-w-5xl mx-auto text-center mb-10">
    <h2 class="text-2xl font-bold">Ulasan Dari Pasien Kami</h2>
    <p class="text-gray-500 mt-2">
      Cerita nyata dari pasien yang mempercayakan kesehatannya kepada kami.
    </p>
  </div>

  <div class="relative max-w-6xl mx-auto">

    <!-- Tombol Kiri -->
    <button id="prev" class="absolute left-0 top-1/2 -translate-y-1/2 z-10 
        bg-white shadow p-3 rounded-full hover:bg-gray-100">
      <span class="text-xl">‹</span>
    </button>

    <!-- Container Carousel -->
    <div id="carousel"
      class="flex gap-6 overflow-x-auto scroll-smooth px-2 no-scrollbar pb-4">

      @forelse($reviews as $review)
      <!-- Review Card -->
      <div class="min-w-[300px] h-[280px] bg-white shadow-md rounded-xl p-6 border 
            flex flex-col justify-between">

            <!-- Rating -->
            <div class="flex gap-1 text-yellow-400 mb-2">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= $review->rating)
                        <span>★</span>
                    @else
                        <span class="text-gray-300">★</span>
                    @endif
                @endfor
            </div>

            <!-- Review Text -->
            <p class="text-gray-600 flex-grow leading-relaxed">
                {{ $review->review_content }}
            </p>

            <!-- Profile -->
            <div class="flex items-center gap-3 mt-4">
                @if($review->user && $review->user->avatar)
                    <img src="{{ $review->user->avatar }}" 
                    class="w-12 h-12 rounded-full border shadow object-cover" />
                @else
                    <img src="https://i.pravatar.cc/80?u={{ $review->reviewer_name }}"
                    class="w-12 h-12 rounded-full border shadow" />
                @endif
                <div>
                    <h4 class="font-semibold">{{ $review->reviewer_name }}</h4>
                    <p class="text-sm text-gray-500">{{ $review->created_at->format('d M Y') }}</p>
                </div>
            </div>

        </div>

      @empty
      <!-- Default Card jika tidak ada review -->
      <div class="min-w-[350px] bg-white shadow-md rounded-xl p-6 border">
        <div class="flex gap-1 text-yellow-400 mb-3">
          <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
        </div>
        <p class="text-gray-600">
          Belum ada review dari pasien. Jadilah yang pertama memberikan review!
        </p>

        <div class="flex items-center gap-3 mt-5">
          <img src="https://i.pravatar.cc/80?img=1"
            class="w-12 h-12 rounded-full border shadow" />
          <div>
            <h4 class="font-semibold">Klinik Sehat</h4>
            <p class="text-sm text-gray-500">Admin</p>
          </div>
        </div>
      </div>
      @endforelse

    </div>

    <!-- Tombol Kanan -->
    <button id="next" class="absolute right-0 top-1/2 -translate-y-1/2 z-10 
        bg-white shadow p-3 rounded-full hover:bg-gray-100">
      <span class="text-xl">›</span>
    </button>

  </div>
</section>
<style>
  .no-scrollbar::-webkit-scrollbar {
    display: none;
  }
  .no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
  }
</style>

<script>
  const carousel = document.getElementById("carousel");
  const next = document.getElementById("next");
  const prev = document.getElementById("prev");
  let autoScrollInterval;

  // Manual navigation
  next.addEventListener("click", () => {
    carousel.scrollBy({ left: 380, behavior: "smooth" });
    resetAutoScroll(); // Reset timer setelah manual click
  });

  prev.addEventListener("click", () => {
    carousel.scrollBy({ left: -380, behavior: "smooth" });
    resetAutoScroll(); // Reset timer setelah manual click
  });

  // Auto scroll function
  function autoScroll() {
    const maxScrollLeft = carousel.scrollWidth - carousel.clientWidth;
    
    if (carousel.scrollLeft >= maxScrollLeft) {
      // Jika sudah di akhir, kembali ke awal
      carousel.scrollTo({ left: 0, behavior: "smooth" });
    } else {
      // Scroll ke kanan
      carousel.scrollBy({ left: 380, behavior: "smooth" });
    }
  }

  // Start auto scroll
  function startAutoScroll() {
    autoScrollInterval = setInterval(autoScroll, 3000); // 3 detik
  }

  // Reset auto scroll (stop dan start lagi)
  function resetAutoScroll() {
    clearInterval(autoScrollInterval);
    startAutoScroll();
  }

  // Pause auto scroll saat hover
  carousel.addEventListener("mouseenter", () => {
    clearInterval(autoScrollInterval);
  });

  // Resume auto scroll saat mouse leave
  carousel.addEventListener("mouseleave", () => {
    startAutoScroll();
  });

  // Start auto scroll saat halaman load
  startAutoScroll();
</script>
