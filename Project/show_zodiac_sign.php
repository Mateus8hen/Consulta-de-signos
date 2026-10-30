<?php
include __DIR__ . '/layouts/header.php';

$signoEncontrado = null;
$erro = null;

$dataNascimento = filter_input(INPUT_POST, 'data_nascimento', FILTER_UNSAFE_RAW);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $erro = 'Acesse o formulário para informar sua data de nascimento.';
} elseif (!$dataNascimento) {
    $erro = 'Informe sua data de nascimento.';
} else {
    $data = DateTime::createFromFormat('!Y-m-d', $dataNascimento);
    $errosData = DateTime::getLastErrors();

    if (
        !$data ||
        ($errosData !== false && ($errosData['warning_count'] > 0 || $errosData['error_count'] > 0)) ||
        $data->format('Y-m-d') !== $dataNascimento ||
        $data > new DateTime('today')
    ) {
        $erro = 'A data informada é inválida. Verifique e tente novamente.';
    } else {
        $signos = simplexml_load_file(__DIR__ . '/signos.xml');

        if ($signos === false) {
            $erro = 'Não foi possível carregar o arquivo de signos.';
        } else {
            // Convertemos a data de nascimento para um número MMDD.
            $mesDia = (int) $data->format('md');

            foreach ($signos->signo as $signo) {
                $inicio = DateTime::createFromFormat('!d/m/Y', (string) $signo->dataInicio . '/2000');
                $fim = DateTime::createFromFormat('!d/m/Y', (string) $signo->dataFim . '/2000');

                if (!$inicio || !$fim) {
                    continue;
                }

                $inicioNumero = (int) $inicio->format('md');
                $fimNumero = (int) $fim->format('md');

                // Capricórnio
                if ($inicioNumero > $fimNumero) {
                    $estaNoPeriodo = ($mesDia >= $inicioNumero || $mesDia <= $fimNumero);
                } else {
                    $estaNoPeriodo = ($mesDia >= $inicioNumero && $mesDia <= $fimNumero);
                }

                if ($estaNoPeriodo) {
                    $signoEncontrado = $signo;
                    break;
                }
            }

            if ($signoEncontrado === null && $erro === null) {
                $erro = 'Não foi possível identificar o signo para essa data.';
            }
        }
    }
}
?>

<main class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <section class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">
                    <?php if ($erro !== null): ?>
                        <div class="text-center">
                            <div class="display-4 mb-3" aria-hidden="true">!</div>
                            <h1 class="h3 mb-3">Não foi possível realizar a consulta</h1>
                            <div class="alert alert-warning" role="alert">
                                <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center">
                            <p class="text-uppercase text-primary fw-semibold small mb-2">Seu resultado</p>
                            <div class="display-3 mb-2" aria-hidden="true">✦</div>
                            <h1 class="display-5 fw-bold mb-3">
                                <?= htmlspecialchars((string) $signoEncontrado->signoNome, ENT_QUOTES, 'UTF-8') ?>
                            </h1>
                            <p class="text-secondary">
                                Nascimento:
                                <?= htmlspecialchars($data->format('d/m/Y'), ENT_QUOTES, 'UTF-8') ?>
                            </p>
                            <p class="lead mb-4">
                                <?= htmlspecialchars((string) $signoEncontrado->descricao, ENT_QUOTES, 'UTF-8') ?>
                            </p>
                        </div>
                    <?php endif; ?>

                    <a href="index.php" class="btn btn-primary w-100">Voltar ao início</a>
                </div>
            </section>
        </div>
    </div>
</main>

<script src="assets/js/app.js"></script>
</body>
</html>
