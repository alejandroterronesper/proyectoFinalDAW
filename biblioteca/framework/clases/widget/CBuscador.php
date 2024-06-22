<?php



/**
 * Clase que define el comportamiento del widget de la barra de buscador
 * 
 * Se usan etiquetas div, a e i
 */
class CBuscador extends CWidget {


    //Variables privadas de instancia
    private array $_atributosHTML = [];



    /**
     * Constructor de la clase CBuscador
     * Recibe como parámetros los atributos html en array
     * 
     * @param Array $atributosHTML atributos de HTML
     */
    public function __construct(array $atributosHTML = []){
        

        if (!isset($atributosHTML["class"])){
            $this->_atributosHTML["class"] = "content2"; //clase barra en CSS
        }
    } 


    /**
     * Redefinción del método dibujaApertura(), el cual se encarga de abrir las etiquetas que engloban al contenido del componente
     * formando una caja
     * @return String -> Cadena con las etiquetas y contenido del menu
     */
    public function dibujaApertura(): string
    {
        ob_start();


        echo CHTML::dibujaEtiqueta("div", $this->_atributosHTML, null, false).PHP_EOL;
            echo CHTML::dibujaEtiqueta("div", ["class" => "search"],null, false).PHP_EOL;
                echo CHTML::dibujaEtiqueta("a", ["href" => "", "target" => "_blank", "hidden" => "hidden"], null, false).PHP_EOL;
                echo CHTML::dibujaEtiquetaCierre("a").PHP_EOL;
                echo CHTML::campoText("buscarBarra", "", ["placeholder" => "Buscar..."]).PHP_EOL;
                echo CHTML::dibujaEtiqueta("div", ["class" => "autocomplete"], null, false).PHP_EOL;
                echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;

                echo CHTML::dibujaEtiqueta("div", ["class" => "icon"], null, false).PHP_EOL;

                    echo CHTML::dibujaEtiqueta("i", ["class" => "fas fa-search"],null, false).PHP_EOL;
                    echo CHTML::dibujaEtiquetaCierre("i").PHP_EOL;

                echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;
            echo CHTML::dibujaEtiquetaCierre("div").PHP_EOL;
        $contenido = ob_get_contents();
        ob_end_clean();

        return $contenido;
    }


    /**
     * Redefinición del método dibujaFin(), el cual se encarga de cerra las etiquetas abiertas y no cerradas en el 
     * método dibujaApertura()
     * @return string -> Cadena con el cierre de las etiquetas abiertas en dibujaApertura()
     */
    public function dibujaFin(): string
    {
        return CHTML::dibujaEtiquetaCierre("div").PHP_EOL;

    }

    
    /**
     * Redefinición del método dibujate(), el cual se encarga de dibujar el componente, llamando sucesivamente al 
     * dibujaApertura() y dibujaCierre()
     * @return string -> Cadena con el contenido del componente (dibujaApertura() y dibujaCierre())
     */
    public function dibujate(): string
    {
        return $this->dibujaApertura().$this->dibujaFin();
    }



}










?>