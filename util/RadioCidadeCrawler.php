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

        $arrayparagrafos = array();

        $this->carregarHtml();
        $tagsDiv = $this->capturarTagsDivGeral();
        $divsInternas = $this->capturarDivsInternasPageContent($tagsDiv);
        $tagsH4 = $this->pegandoTagH4($divsInternas);
        $Desc = $this->pegandoANoticia($divsInternas);
        $arrayparagrafos[0] = $this->getArrayParagrafos($tagsH4);
        $arrayparagrafos[1] = $this->getArrayNoticias($Desc);
        return $arrayparagrafos;
        
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
        $divsInternas = array();
        foreach ($divsGeral as $div) {                                            //Pega as tags Div
            $classe = $div->getAttribute('class');

            if ($classe == 'col s12 m12 l12') {                            //Pega a classe chamada page_content
                $divsInternas = $div->getElementsByTagName('div');      //Pega as divs internas da div page_content
                break;
            }
        }
        return $divsInternas;
    }

    private function pegandoTagH4($divsInternas) {

        $tagsH4 = array();
        foreach ($divsInternas as $interna) {                                            //Pega as tags Div
            $classe = $interna->getAttribute('class');

            if ($classe == 'col s12 ') {                            //Pega a classe chamada page_content
                $tagsH4 = $interna->getElementsByTagName('h4');      //Pega as divs internas da div page_content
                break;
            }
            
            
        }
        return $tagsH4;
    }

    private function pegandoANoticia($divsInternas) {

        $divNoti = array();
        $divNotiFiltrada = array();

        foreach ($divsInternas as $internas) {                                            //Pega as tags Div
            $classe = $internas->getAttribute('class');

            if ($classe == 'col s12') {                            //Pega a classe chamada page_content
                $divNoti = $internas->getElementsByTagName('div');      //Pega as divs internas da div page_content
            }
            
            
        }

        return $divNoti;
    }

    private function getArrayParagrafos($tagsH4) {
        $arrayH4 = [];
        foreach ($tagsH4 as $h4) {
            $arrayH4[] = $h4->nodeValue;                          //Imprime todos os paragrafos
        }
        return $arrayH4;
    }

    private function getArrayNoticias($divNoti) {
        $arrayDesc = [];
        foreach ($divNoti as $desc) {
            $arrayDesc[] = $desc->nodeValue;                          //Imprime todos os paragrafos
        }

        return $arrayDesc;
    }

}
