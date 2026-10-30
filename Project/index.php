<?php
include __DIR__ . '/layouts/header.php';
?>

<main class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <section class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">
                    <p class="text-uppercase text-primary fw-semibold small mb-2">Astrologia</p>
                    <h1 class="h2 mb-3">Descubra seu signo</h1>
                    <p class="text-secondary mb-4">
                        Informe sua data de nascimento para descobrir seu signo zodiacal.
                    </p>

                    <form id="signo-form" method="POST" action="show_zodiac_sign.php">
                        <div class="mb-3">
                            <label for="data_nascimento" class="form-label">Data de nascimento</label>
                            <input
                                type="date"
                                class="form-control form-control-lg"
                                id="data_nascimento"
                                name="data_nascimento"
                                required
                                max="<?= date('Y-m-d') ?>"
                            >
                            <div class="form-text">Selecione dia, mês e ano.</div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            Consultar meu signo
                        </button>
                    </form>
                </div>
            </section>
            <p class="text-center text-secondary small mt-3 mb-0">
                Projeto acadêmico — Programação Web
            </p>
        </div>
    </div>
</main>

<script src="assets/js/app.js"></script>
</body>
</html>
