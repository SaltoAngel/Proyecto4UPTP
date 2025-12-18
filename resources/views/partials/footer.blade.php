{{--
    Partial: Footer (pie de página Material)
    Propósito: Mostrar el copyright.
--}}
<footer class="footer py-4">
    <div class="container-fluid">
        <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6 mb-lg-0 mb-4">
                <div class="copyright text-center text-sm text-muted text-lg-start">
                        (c) <script>document.write(new Date().getFullYear());</script>
                        Hecho con <i class="material-icons text-danger" style="font-size:1.1rem;">favorite</i> para {{ config('app.name') }}.
                </div>
            </div>
        </div>
    </div>
</footer>
