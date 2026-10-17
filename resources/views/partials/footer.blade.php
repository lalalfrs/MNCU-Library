{{-- Footer Perpustakaan MNC University --}}
<footer class="mt-8 border-t border-line bg-white py-5 text-center text-[13px] text-muted lg:text-base">
    &copy; <span id="footer-year">{{ date('Y') }}</span> Perpustakaan MNC University. Hak Cipta Dilindungi.
    <script>
        (function () {
            var el = document.getElementById('footer-year');
            if (el) el.textContent = new Date().getFullYear();
        })();
    </script>
</footer>
