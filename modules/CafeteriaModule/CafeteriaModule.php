<?php

class CafeteriaModule extends HC_Module {
    public function __construct($hc) {
        parent::__construct($hc);

        $this->registerWindowCallback('coffee', 'CafeteriaWindowCallback');
    }


    public function onCreatingSidebar(&$sidebar) {
        $newEntry = [
          'icon' => 'coffee',
          'text' => 'Cafetería',
          'id' => 'coffee',
        ];
        array_unshift($sidebar, $newEntry); // To prepend the entry
    }

    public function CafeteriaWindowCallback() {
        return [
          'html' => '
            <p></p>
            <p>Información sobre la cafetería de la Facultad de Informática.
            </p>
            <p id="librelab">
             HORARIOS
            </p>
            <p><ul>
            <li>Horario habitual (a partir del 5 de septiembre):  8:00 - 19:00 h.</li>
            <li>Del 29 de agosto al 2 de septiembre: 8:00 - 18:00 h.</li>
            <li>Del 22 al 26 de agosto: CERRADA.</li>
            </ul></p>
            <p id="librelab">
             PRECIOS
            </p>
            <p>
            Menú: 4,85€ <br>
            Pincho de tortilla: 1,40€ <br>
            Botellín de Mahou: 1€ <br>
            Café: 0,80€ <br>
            Desayuno: 1,50€<br>
            Hamburguesa completa: 4€<br>
            Bocadillo entero: 3€ <br>
            </p>
            <p id="librelab">
             CONTACTO
            </p>
            <p>
            Teléfono: 91 394 7569
            </p>',
          'title' => '<svg class="icon coffee windowicon">
            <use xlink:href="#coffee">
            </use>
          </svg>Cafetería',
        ];
    }

}
