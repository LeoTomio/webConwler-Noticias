<?php

//Configurações de Proxy SENAI
$proxy = '10.1.21.254:3128';


//------------------------------------



$url = "http://gutenberg.org/";
$html = file_get_contents($url, false, $context);

$dom = new DOMDocument();
libxml_use_internal_errors(true);

//Transformando html em objeto
$dom->loadHTML($html);
libxml_clear_errors();

//Capturando as tags P
//$tagsP = $dom->getElementsByTagName('p');
//      foreach ($tagsP as $p) {
//      echo $p->nodeValue;
//                    echo "<br><br/>";
//Captura as tags div
$tagsDiv = $dom->getElementsByTagName('div');
$arrayP = [];


foreach ($tagsDiv as $div) {                                    //Pega as tags Div
    $classe = $div->getAttribute('class');                      //Pega as Divs que possue Class

    if ($classe == 'col s12 m12 l12') {                            //Pega a classe chamada page_content
        $divsInternas = $div->getElementsByTagName('div');      //Pega as divs internas da div page_content

        foreach ($divsInternas as $divInterna) {
            $classeInterna = $divInterna->getAttribute('class');    //Pega as divs que possuem class, dentro da div page_content


            if ($classeInterna == 'col s12') {                    //Pega as classes chamadas box_announce
                $tagPInternas = $divInterna->getElementsByTagName('p'); //Pega todas as tags P dentro de box_announce


                foreach ($tagPInternas as $p) {                     
                    $arrayP[] = $p->nodeValue;                          //Imprime todos os paragrafos
                }
                break;
            }
        }
        break;
    }
}
//Exibe o ArrayP 
print_r($arrayP);

?>

