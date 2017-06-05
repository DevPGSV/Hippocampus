<?php

class SoftwareModule extends HC_Module {
    public function __construct($hc) {
        parent::__construct($hc);

        $this->registerWindowCallback('softwareprovided', 'SoftwareWindowCallback');
    }

    public function onCreatingSidebar(&$sidebar) {
        $newEntry = [
            'icon' => 'software',
            'text' => 'Software',
            'id' => 'softwareprovided',
        ];
        array_unshift($sidebar, $newEntry); // To prepend the entry
    }

    public function SoftwareWindowCallback() {

        // Muestra dos botones para elegir ir a elementos recibidos, enviados o para escribir un nuevo mensaje:
        $html = '
        <div class="light-gray-box">
            <p class="software-title">
                Microsoft Dreamspark Premium
            </p>
            <p> La Universidad Complutense tiene suscrito el acuerdo ELMS Dreamspark Premium para instituciones académicas con Microsoft.
                <br>
                Dicho acuerdo, permite a la comunidad universitaria acceder a las últimas versiones de software de:
                    <br>
                    <br>
                    - Sistemas Operativos: Windows y Windows server
                    <br>
                    - Herramientas de desarrollo: Visual Studio (versiones ultimate, premium y profesional), Expressions
                    <br>
                    - Aplicaciones: OneNote, Visio, Project
                    <br>
                    - Servidores: SQL server, Sharepoint, BizTalk server
                    <br><br>
            </p>

            <a href="http://e5.onthehub.com/WebStore/Welcome.aspx?ws=b8510eb2-826f-e011-971f-0030487d8897">
              <input type="submit" value="Ir a la tienda" />
            </a>
        </div>

        <div class="light-gray-box">
            <p class="software-title">
                Office 365 ProPlus
            </p>
            <p> La Universidad Complutense y Microsoft han llegado a un acuerdo que permite a todos los miembros de la comunidad universitaria (PDI, PAS y Estudiantes) utilizar el software integrado en la plataforma Office 365 ProPlus sin coste adicional.
                <br><br>
                Cada usuario puede utilizar dicho software en ordenadores de sobremesa, portátiles, smartphones, tablets con sistemas operativos Windows, Android o iOS.
                <br><br>
            </p>

            <a href="http://e5.onthehub.com/WebStore/Welcome.aspx?ws=b8510eb2-826f-e011-971f-0030487d8897">
              <input type="submit" value="Obtener más información" />
            </a>
        </div>
        ';

        return [
            'html' => $html,
            'title' => '<svg class="icon software windowicon">
                <use xlink:href="#software">
                </use>
                </svg>Software',
        ];
    }
}
