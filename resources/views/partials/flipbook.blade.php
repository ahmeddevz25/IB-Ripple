{{-- FlipBook Partial View --}}
@if (isset($documents) && $documents->count())
    <div class="row justify-content-center gallery d-flex">
        @foreach ($documents as $doc)
            <div class="col-md-6 col-sm-12 d-flex justify-content-center mb-4">
                <div class="card shadow-sm text-center flip-card">
                    <a href="#" class="flipbook-trigger" data-pdf="{{ asset('storage/' . $doc->file_path) }}"
                        data-cover="{{ asset('storage/' . $doc->thumbnail) }}">
                        <img src="{{ asset('storage/' . $doc->thumbnail) }}" class="img-fluid flip-thumb" alt="PDF">
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@endif

{{-- FlipBook Modal Structure --}}
<div class="fb3d-modal">
    <span class="cmd-close fas fa-times"></span>
    <div class="mount-container"></div>
</div>

{{-- Styles for Modal --}}
<style>
    .fb3d-modal { position: fixed; width: 100%; height: 100%; left: 0; top: 0; background-color: rgba(0, 0, 0, 0.9); z-index: 100000; pointer-events: none; visibility: hidden; opacity: 0; transition: opacity 0.5s, visibility 0.5s step-end; display: block !important; }
    .fb3d-modal.visible { pointer-events: all; visibility: visible; opacity: 1; transition: opacity 0.5s; }
    .fb3d-modal .mount-container { position: absolute; z-index: 1; width: 100%; height: 100%; left: 0; top: 0; }
    .fb3d-modal .cmd-close { font-size: 32px; color: #fff; position: absolute; right: 20px; top: 20px; z-index: 2; cursor: pointer; opacity: 0.8; }
    .fb3d-modal .cmd-close:hover { opacity: 1; }
</style>

{{-- FlipBook Scripts --}}
<script>
    window.FLIPBOOK_ASSETS_BASE = '{{ asset('assets/3d-flip-book') }}/';
    window.PDFJS_LOCALE = {
        pdfJsWorker: window.FLIPBOOK_ASSETS_BASE + 'js/pdf.worker.js',
        pdfJsCMapUrl: 'https://cdn.3dflipbook.net/cmaps'
    };
</script>

<script src="{{ asset('assets/3d-flip-book/js/libs/three.min.js') }}"></script>
<script src="{{ asset('assets/3d-flip-book/js/libs/pdf.min.js') }}"></script>
<script src="{{ asset('assets/3d-flip-book/js/dist/3dflipbook.min.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof jQuery === 'undefined') return console.error('jQuery is missing! FlipBook cannot initialize.');

        var $ = jQuery;
        var modal = $('.fb3d-modal');
        var container = $('.fb3d-modal .mount-container');
        var instance = { scene: undefined };

        // Reusable FlipBook Template Configuration
        var fbTemplate = {
            html: window.FLIPBOOK_ASSETS_BASE + 'templates/default-book-view.html',
            styles: [window.FLIPBOOK_ASSETS_BASE + 'css/short-white-book-view.css'],
            links: [{ rel: 'stylesheet', href: window.FLIPBOOK_ASSETS_BASE + 'css/font-awesome.min.css' }],
            script: window.FLIPBOOK_ASSETS_BASE + 'js/default-book-view.js',
            sounds: {
                startFlip: window.FLIPBOOK_ASSETS_BASE + 'sounds/start-flip.mp3',
                endFlip: window.FLIPBOOK_ASSETS_BASE + 'sounds/end-flip.mp3'
            }
        };

        // Modal Initializer
        $('.flipbook-trigger').on('click', function(e) {
            e.preventDefault();
            var pdfUrl = $(this).data('pdf');
            if (!pdfUrl) return;

            modal.addClass('visible');
            setTimeout(function() {
                try {
                    instance.scene = container.FlipBook({ pdf: pdfUrl, template: fbTemplate });
                } catch (err) {
                    console.error("FlipBook modal init error:", err);
                }
            }, 100);
        });

        // Modal Close Logic
        $('.cmd-close').on('click', function() {
            modal.removeClass('visible');
            if (instance.scene) instance.scene = undefined;
            container.empty();
        });

        // Embedded Container Logic
        $('.custom-flipbook-container').each(function() {
            var $bookContainer = $(this);
            var src = $bookContainer.attr('src') || $bookContainer.data('src');
            
            if (!src) return console.warn('FlipBook: No source found for container', this);

            if ($bookContainer.height() < 50) {
                $bookContainer.css({ 'min-height': '500px', 'height': '80vh', 'width': '100%', 'position': 'relative', 'display': 'block' });
            }

            setTimeout(function() {
                $bookContainer.FlipBook({
                    pdf: src,
                    template: fbTemplate,
                    assets: { base: window.FLIPBOOK_ASSETS_BASE },
                    pdfJsWorker: window.FLIPBOOK_ASSETS_BASE + 'js/pdf.worker.js'
                });
            }, 100);
        });
    });
</script>
