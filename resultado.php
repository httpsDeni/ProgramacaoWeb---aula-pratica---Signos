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
    return strtolower(str_replace([' ', 'ã','á','é','í','ó','ú','â','ê','ô','ç'], 
    ['','a','a','e','i','o','u','a','e','o','c'], $signo));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['data_nascimento'])) {
        $_SESSION['error'] = "Por favor, informe uma data válida!";
        header("Location: index.php");
        exit;
    }

    $signo = determinarSigno($_POST['data_nascimento']);
    
    $descricoes = [
        'Aries' => 'Pioneiro e aventureiro, Áries é o primeiro signo do zodíaco! Regido por Marte, o planeta da guerra, os arianos são conhecidos por sua coragem extraordinária e espírito de liderança nato. Sua energia inesgotável e entusiasmo contagiante fazem deles verdadeiros iniciadores de projetos. Competitivos e determinados, não há desafio grande demais para um ariano enfrentar.',

        'Touro' => 'Governado por Vênus, Touro é o signo da abundância e do prazer sensorial. Os taurinos são conhecidos por sua natureza paciente, determinada e prática. Apreciam o conforto material, as artes e tudo que é belo. Com seus pés firmemente plantados no chão, são mestres em construir bases sólidas e duradouras, tanto em relacionamentos quanto em projetos.',

        'Gemeos' => 'Versátil e comunicativo, Gêmeos é regido por Mercúrio, o mensageiro dos deuses. Os geminianos são verdadeiros camaleões sociais, adaptando-se facilmente a diferentes situações e pessoas. Sua mente ágil e curiosidade infinita os tornam excelentes comunicadores e eternos aprendizes. São conhecidos por sua dualidade fascinante e capacidade de ver todos os lados de uma questão.',

        'Cancer' => 'Regido pela Lua, Câncer é o signo do lar e das emoções profundas. Os cancerianos são naturalmente intuitivos e protetores, com uma conexão especial com a família e suas raízes. Possuem uma memória extraordinária e uma imaginação rica, sendo capazes de criar ambientes acolhedores onde quer que estejam. São mestres em cuidar e nutrir aqueles que amam.',

        'Leao' => 'Governado pelo Sol, Leão é o signo da realeza e do brilho pessoal. Os leoninos são carismáticos, generosos e naturalmente destacam-se onde quer que estejam. Com sua criatividade marcante e autoconfiança radiante, são verdadeiros líderes natos. Seu coração grande e lealdade inabalável fazem deles amigos e parceiros extraordinários.',

        'Virgem' => 'Regido por Mercúrio, Virgem é o signo da análise e do perfeccionismo. Os virginianos são conhecidos por sua atenção meticulosa aos detalhes e desejo de servir. Com uma mente analítica afiada e praticidade invejável, são excelentes em resolver problemas e melhorar sistemas. Sua busca pela perfeição os torna profissionais dedicados e amigos leais.',

        'Libra' => 'Sob a regência de Vênus, Libra é o signo do equilíbrio e da harmonia. Os librianos são naturalmente diplomáticos e justos, sempre buscando beleza e paz em tudo que fazem. Com seu charme natural e senso estético refinado, são mestres em criar ambientes harmoniosos e mediar conflitos. Valorizam relacionamentos e parcerias acima de tudo.',

        'Escorpiao' => 'Regido por Plutão e Marte, Escorpião é o signo da transformação e dos mistérios. Os escorpianos são intensos, passionais e possuem uma força interior extraordinária. Com sua intuição aguçada e capacidade de ver além das aparências, são excelentes investigadores e estrategistas. Sua lealdade é inabalável, e sua capacidade de renascimento é inspiradora.',

        'Sagitario' => 'Governado por Júpiter, Sagitário é o signo da expansão e da sabedoria. Os sagitarianos são eternos otimistas e aventureiros, sempre em busca de novos horizontes e conhecimentos. Com seu espírito livre e filosofia de vida inspiradora, são excelentes professores e contadores de histórias. Sua honestidade e entusiasmo são contagiantes.',

        'Capricornio' => 'Regido por Saturno, Capricórnio é o signo da ambição e da responsabilidade. Os capricornianos são naturalmente disciplinados e determinados, com uma capacidade extraordinária de estabelecer e alcançar metas. Com sua sabedoria prática e paciência inabalável, são mestres em construir legados duradouros. Sua dedicação ao trabalho e lealdade são admiráveis.',

        'Aquario' => 'Sob a regência de Urano, Aquário é o signo da inovação e do progresso. Os aquarianos são visionários e originais, sempre à frente de seu tempo. Com sua mente brilhante e ideais humanitários, são verdadeiros revolucionários e inventores. Valorizam a liberdade individual e têm uma capacidade única de unir pessoas em prol de causas nobres.',

        'Peixes' => 'Governado por Netuno, Peixes é o signo da intuição e da compaixão universal. Os piscianos são naturalmente empáticos e sonhadores, com uma conexão profunda com o mundo espiritual e artístico. Sua sensibilidade extraordinária e imaginação fértil os tornam artistas naturais e conselheiros intuitivos. São mestres em compreender e curar as dores alheias.'
    ];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Resultado - Seu Signo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <h3 class="mb-0">Seu Signo é: <?= $signo ?></h3>
                    </div>
                    <div class="card-body text-center">
                        <img src="images/<?= formatarNomeImagem($signo) ?>.png" alt="<?= $signo ?>" class="signo-img">
                        <p class="mt-3"><?= $descricoes[$signo] ?? 'Descrição não disponível' ?></p>
                        <a href="index.php" class="btn btn-outline-success">Voltar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
