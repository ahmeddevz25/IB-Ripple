<!-- modernizr js -->
<script src="{{ asset('index') }}/assets/js/modernizr-2.8.3.min.js"></script>
<!-- Back to Top -->

<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<!-- Bootstrap 4 Removed -->
<script src="{{ asset('index') }}/assets/lib/easing/easing.min.js"></script>
<script src="{{ asset('index') }}/assets/lib/waypoints/waypoints.min.js"></script>
<script src="{{ asset('index') }}/assets/lib/counterup/counterup.min.js"></script>
<script src="{{ asset('index') }}/assets/lib/owlcarousel/owl.carousel.min.js"></script>

<!-- Template Javascript -->
<script src="{{ asset('index/assets/js/main.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
<script>
    Fancybox.bind("[data-fancybox]", {
        Images: {
            initialSize: "fit", // Ensure it fits in viewport
        },
        Toolbar: {
            display: {
                left: ["infobar"],
                middle: [],
                right: ["slideshow", "thumbs", "close"],
            },
        },
        Thumbs: {
            type: "classic",
        },
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
    </script>
<!-- Toastr Flash Messages -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error(@json($error));
            @endforeach
        @endif

        @if (session('error'))
            toastr.error(@json(session('error')));
        @endif

        @if (session('success'))
            toastr.success(@json(session('success')));
        @endif
    });
</script>
<script>
    const b = document.getElementById('topBtn');
    window.onscroll = () => b.style.display = window.scrollY > 300 ? 'block' : 'none';
    b.onclick = () => window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
</script>