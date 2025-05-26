<footer class="bg-dark text-white py-4 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-3 mb-md-0">
                <h5>Sistema Productores</h5>
                <p class="text-muted">Plataforma para gestión de productores agrícolas.</p>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <h5>Enlaces útiles</h5>
                <ul class="list-unstyled">
                    <li><a href="{{ route('home') }}" class="text-white">Inicio</a></li>
                    <li><a href="{{ route('productores.index') }}" class="text-white">Productores</a></li>
                    <li><a href="#" class="text-white">Contacto</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5>Contacto</h5>
                <address class="text-muted">
                    <p><i class="bi bi-geo-alt-fill me-2"></i> Dirección, Ciudad, País</p>
                    <p><i class="bi bi-telephone-fill me-2"></i> (123) 456-7890</p>
                    <p><i class="bi bi-envelope-fill me-2"></i> info@sistema-productores.com</p>
                </address>
            </div>
        </div>
        <hr class="mt-4">
        <div class="text-center">
            <p class="mb-0">&copy; {{ date('Y') }} Sistema Productores. Todos los derechos reservados.</p>
        </div>
    </div>
</footer>
