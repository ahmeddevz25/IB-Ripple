<footer class="footer-section text-center py-4 position-relative">
        <div class="container">
                <!-- Newsletter Section -->
                <h2 class="newsletter-title text-center">Join our mailing list for updates on<br>publications and events
                </h2>

                <div class="row justify-content-center">
                        <div class="col-lg-6">
                                <div class="text-left mb-2" style="text-align: left;">
                                        <label class="newsletter-label">Enter your email here *</label>
                                </div>
                                <form action="{{ route('newsletter.submit') }}" method="POST">
                                        @csrf
                                        <div class="input-group mb-5">
                                                <input type="email" name="email"
                                                        class="form-control form-control-newsletter"
                                                        placeholder="Enter your email here"
                                                        aria-label="Enter your email here" required>
                                                <button class="btn btn-newsletter" type="submit">JOIN</button>
                                        </div>
                                </form>
                        </div>
                </div>

                <!-- Footer Contact Info -->
                <p class="footer-contact-info mt-5 mb-5">
                        332/1, J Block, DHA Phase VIII, Lahore, Pakistan. |
                        <a href="mailto:info@ibripple.com">info@ibripple.com</a> |
                        <a href="https://www.lainternational.edu.pk" target="_blank">www.lainternational.edu.pk</a> |
                        +92-42-111-66-66-33
                </p>

                <!-- Back to Top Button -->
        </div>
</footer>
<button id="topBtn" class="btn btn-dark rounded-circle position-fixed bottom-0 end-0 m-4"
        style="display:none; z-index: 99999 !important;">
        <i class="bi bi-arrow-up"></i>
</button>