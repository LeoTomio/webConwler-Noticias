<?php

class RadioCidadeCrawler {

    private $url;
    private $proxy;
    private $dom;
    private $html;

    public function __construct() {
        //Seta os valores das variáveis
        $this->url = "https://rc.am.br/homes/page_noticias/";
        $this->proxy = "10.1.21.254:3128";
        $this->dom = new DOMDocument();
    }

    public function getParagrafos() {

        $this->carregarHtml();
        $tagsDiv = $this->capturarTagsDivGeral();
        $divsInternas = $this->capturarDivsInternasPageContent($tagsDiv);
        $tagsP = $this->capturarTagsP($divsInternas);
        $arraypararafos = $this->getArrayParagrafos($tagsP);
        return $arraypararafos;
    }

    private function getContextoConexao() {
        //configuração de proxy
        $arrayConfig = array(
            'http' => array(
                'proxy' => $this->proxy,
                'request_fulluri' => true
            ),
            'https' => array(
                'proxy' => $this->proxy,
                'request_fulluri' => true),
        );
        $context = stream_context_create($arrayConfig);
        return $context;
    }

    private function carregarHtml() {
        $context = $this->getContextoConexao();
        $this->html = file_get_contents($this->url, false, $context);

        libxml_use_internal_errors(true);

        //Transformando html em objeto
        $this->dom->loadHTML($this->html);
        libxml_clear_errors();
    }

    private function capturarTagsDivGeral() {

        $tagsDiv = $this->dom->getElementsByTagName('div');                     //Captura as tags div
        return $tagsDiv;
    }

    private function capturarDivsInternasPageContent($divsGeral) {
        $divsInternas = null;
        foreach ($divsGeral as $div) {                                            //Pega as tags Div
            $classe = $div->getAttribute('class');

            if ($classe == 'col s12 m12 l12') {                            //Pega a classe chamada page_content
                $divsInternas = $div->getElementsByTagName('div');      //Pega as divs internas da div page_content
                break;
            }
        }
        return $divsInternas;
    }

    private function capturarTagsP($divsInternas) {

        $tagsP = null;

        foreach ($divsInternas as $divInterna) {
            $classeInterna = $divInterna->getAttribute('class');    //Pega as divs que possuem class, dentro da div page_content


            if ($classeInterna == 'col s12 ') {                    //Pega as classes chamadas box_announce
                $tagsP = $divInterna->getElementsByTagName('p'); //Pega todas as tags P dentro de box_announce
            }
        }
        return $tagsP;
    }
    
    private function capturaTagA(){
        
    }

    private function getArrayParagrafos($tagsP) {
        $arrayP = [];
        foreach ($tagsP as $p) {
            $arrayP[] = $p->nodeValue;                          //Imprime todos os paragrafos
        }
        return $arrayP;
    }

}
