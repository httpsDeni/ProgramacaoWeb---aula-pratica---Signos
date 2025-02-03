<?php
session_start();

function determinarSigno($data) {
    $signos = [
        'Aries'       => ['03-21', '04-19'],
        'Touro'       => ['04-20', '05-20'],
        'Gemeos'      => ['05-21', '06-20'],
        'Cancer'      => ['06-21', '07-22'],
        'Leao'        => ['07-23', '08-22'],
        'Virgem'      => ['08-23', '09-22'],
        'Libra'       => ['09-23', '10-22'],
        'Escorpiao'   => ['10-23', '11-21'],
        'Sagitario'   => ['11-22', '12-21'],
        'Capricornio' => ['12-22', '01-19'],
        'Aquario'     => ['01-20', '02-18'],
        'Peixes'      => ['02-19', '03-20']
    ];

    $data = date('m-d', strtotime($data));

    foreach ($signos as $signo => $periodo) {
        if (($data >= $periodo[0] && $data <= $periodo[1]) ||
            ($periodo[0] > $periodo[1] && ($data >= $periodo[0] || $data <= $periodo[1]))) {
            return $signo;
        }
    }
    return 'Desconhecido';
}

function removerAcentos($string) {
    return str_replace(
        ['Á','É','Í','Ó','Ú','Â','Ê','Î','Ô','Û','Ã','Õ','Ç','á','é','í','ó','ú','â','ê','î','ô','û','ã','õ','ç'],
        ['A','E','I','O','U','A','E','I','O','U','A','O','C','a','e','i','o','u','a','e','i','o','u','a','o','c'],
        $string
    );
}

function formatarNomeImagem($signo) {
    // Converter o nome do signo para minúsculo e remover acentos
    $signo = strtolower(removerAcentos($signo));
    
    // Remover espaços em branco se houver
    $signo = str_replace(' ', '', $signo);
    
    return $signo;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['data_nascimento'])) {
        $_SESSION['error'] = "Por favor, informe uma data válida!";
        header("Location: index.php");
        exit;
    }

    $signo = determinarSigno($_POST['data_nascimento']);
    
    // Carregar e processar o arquivo XML
    $xml = simplexml_load_file('signos.xml');
    $signoInfo = null;
    
    foreach ($xml->signo as $s) {
        if (strtolower((string)$s->nome) === strtolower($signo)) {
            $signoInfo = $s;
            break;
        }
    }
    
    if ($signoInfo) {
        include 'layouts/header.php';
        ?>
        <div class="resultado-container">
            <div class="card resultado-card shadow">
                <div class="card-body">
                    <h2 class="signo-titulo"><?= $signoInfo->nome ?></h2>
                    <?php 
                    $imagemPath = "assets/imgs/" . formatarNomeImagem($signo) . ".png";
                    // Debug: mostrar o caminho da imagem
                    echo "<!-- Tentando carregar imagem: " . $imagemPath . " -->";
                    ?>
                    <img src="<?= $imagemPath ?>" alt="<?= $signo ?>" class="signo-imagem">
                    <?php
                    // Convertendo as datas do formato DD-MM para exibição
                    $dataInicio = str_pad($signoInfo->dataInicio, 5, '0', STR_PAD_LEFT);
                    $dataFim = str_pad($signoInfo->dataFim, 5, '0', STR_PAD_LEFT);
                    ?>
                    <p class="data-periodo">Período: <?= $dataInicio ?> a <?= $dataFim ?></p>
                    <p class="regente-info">Regente: <?= $signoInfo->regente ?></p>
                    <p class="signo-descricao"><?= $signoInfo->descricao ?></p>
                    <div class="text-center mt-4">
                        <a href="index.php" class="btn btn-primary">Descobrir Outro Signo</a>
                    </div>
                </div>
            </div>
        </div>
        <?php
    } else {
        $_SESSION['error'] = "Desculpe, não foi possível encontrar as informações do seu signo.";
        header("Location: index.php");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
?>
