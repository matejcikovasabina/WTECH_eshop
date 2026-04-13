<section class="checkout-steps-wrap">
    <div class="container py-1">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="d-flex flex-wrap justify-content-between text-center gap-3 small fw-semibold">
                    <a href="{{ route('cart.index') }}"
                       class="checkout-step {{ ($activeStep ?? '') === 'cart' ? 'active-step' : 'inactive-step' }} text-decoration-none flex-fill pb-2">
                        Košík
                    </a>

                    <div class="checkout-step {{ ($activeStep ?? '') === 'delivery' ? 'active-step' : 'inactive-step' }} flex-fill pb-2">
                        Doručenie
                    </div>

                    <div class="checkout-step {{ ($activeStep ?? '') === 'payment' ? 'active-step' : 'inactive-step' }} flex-fill pb-2">
                        Platba
                    </div>

                    <div class="checkout-step {{ ($activeStep ?? '') === 'summary' ? 'active-step' : 'inactive-step' }} flex-fill pb-2">
                        Sumár
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>