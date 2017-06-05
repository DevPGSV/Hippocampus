# MÓDULOS

## Índice ##
- [Gestión académica](https://github.com/DevPGSV/Hippocampus/doc/modules/GestionAcademica.md)
- [Google](https://github.com/DevPGSV/Hippocampus/doc/modules/Google.md)
- [Mensajería](https://github.com/DevPGSV/Hippocampus/doc/modules/Mensajeria.md)
- [Redes sociales](https://github.com/DevPGSV/Hippocampus/doc/modules/RedesSociales.md)
- [Aplicaciones informáticas](https://github.com/DevPGSV/Hippocampus/doc/modules/AplicacionesInformaticas.md)
- [Servicios de la Facultad](https://github.com/DevPGSV/Hippocampus/doc/modules/ServiciosFacultad.md)
- [Otros](https://github.com/DevPGSV/Hippocampus/doc/modules/Otros.md)

Los módulos son el fuerte de la aplicación. Son los que ofrecen los servicios para los usuarios.

Se colocan en la carpeta modules situada en la raíz del proyecto.

Dentro de la carpeta modules hay una carpeta por cada módulo, que contine todos los archivos que el módulo pueda necesitar.

Dentro de la carpeta del módulo debe existir un archivo llamado exactamente igual que la carpeta, terminado en `.php`. Este archivo debe contener una clase que se debe llamar igual que la carpeta del módulo, y que debe extender de `HC_Module`.

El constructor de la clase principal del módulo recibe una instancia del objeto Hippocampus, que deberá pasarle al constructor de la clase padre.

Un ejemplo de un módulo:

`modules/ExampleModule/ExampleModule.php`

``` php
    <?php
    class ExampleModule extends HC_Module {
        public function __construct($hc) {
            parent::__construct($hc);
        }
    }
    ?>
```
Ese sería el esquema básico de un módulo que no hace nada. Las funcionalidades de los módulos se añadirían en el constructor y en funciones específicas dentro de la clase.

Los módulos pueden acceder a la instancia de Hippocampus con `$this->hc`. Desde este objeto pueden obtener instancias a la base de datos, y a cualquier otra parte de la aplicación.

### Añadir elementos al menú lateral

Los módulos pueden implementar la función `onCreatingSidebar()`, que se llamará durante el proceso de creación del menú. Recibirá un parámetro con un array de elementos. Este parámetro se pasa por referencia, por lo que el módulo podrá editar el array, añadiendo nuevos elementos o borrando los existentes.

Un elemento consta de un array con 3 claves: **icon**, **text**, e **id**. En base a estos valores se creará posteriormente el elemento en el menú.

El **id** de un elemento del menú debe ser único (se recomienda incluir el nombre del módulo como parte del id para disminuir las posibilidades de choque entre id). El id se asociará posteriormente con una de las ventanas de la vista de usuario, en la que se mostrará el contenido adecuado según el id asociado.

### Añadir código en la sección `<head>` de la página

Implementando la función `onCreatingMetacode()`, los módulos reciben un array por referencia con los elementos del código de la sección head de las páginas. En este array se pueden insertar elementos con referencias a archivos de javascript y de hojas de estilo. Además, se puede insertar código directamente, por ejemplo, insertando un elemento con el código entre las etiquetas `<script>` y `</script>`.

Aquí un módulo podría importar librerías o estilo CSS de fuentes externas, o de la propia carpeta del módulo.

### Configurar contenido de las ventanas

La vista principal del usuario (el **userview**) tiene un número configurable de ventanas. Su contenido se rige por un identificador.
Los módulos pueden registrarse creando un hook que será llamado cuando el contenido de una de estas ventanas se tenga que mostrar.

Para ello, en el contructor de la clase del módulo, se ejecutará el siguiente código:

```php
    <?php
    $this->registerWindowCallback('window_identifier', 'callback_function');
    ?>
```

Siendo **window_identifier** el identifiador (por ejemplo, el id de un elemento del menú lateral), y **callback_function** un string con el nombre de una función pública presente en esa misma clase que se llamará cuando se tenga que mostrar contenido en una ventana.

La función del callback puede, opcionalmente, recibir un array de campos, para personalizar más el contenido a mostrar al usuario.

Esta función debe devolver un array asociativo con 2 claves: **html** y **title**.
El contenido se verá en el cuerpo y la cabecera de la ventana, respectivamente.

Para ampliar la funcionalidad de los módulos y facilitar la transición entre ventanas, se puede incluir un elemento HTML con el atributo `data-updatewindowboxservice`, dándole el valor de un identificador de ventana al que se cambiará cuando se haga clic en este elemento.

Un módulo puede configurar tantos callbacks como se desee, llamando varias veces a la función `registerWindowCallback()`, indicando cada vez el identificador y el nombre de la función del callback. Por ello, un módulo puede configurar varias vistas distintas, y permitir al usuario navegar entre estas vistas creando elementos con el atributo `data-updatewindowboxservice`.

Para permitir una navegación más facil, se pueden añadir datos extra en forma de atributos de la forma `data-cbdata-Clave`. Clave será la clave del array de campos extra que se recibirá en la función del callback de la vista. Se pueden tener tantos valores como se desee, usando diferentes claves para cada valor.

**Importante**: la clave deberá empezar por mayúscula. Si no es así, la primera letra se convertirá a mayúscula automaticamente.

Por ejemplo:

```html
    <p data-updatewindowboxservice="newview" data-cbdata-Key1="value1" data-cbdata-Key2="value2">
        Click to go to new view!
    </p>
```

### Registro de llamadas a la API

Los módulos pueden capturar llamadas específicas a la api de la aplicación para procesarlas en funciones propias.

Incluyendo el siguiente código en el constructor de la clase del módulo:

```php
    <?php
    $this->registerApiCallback('api_action', 'api_function_callback');
    ?>
```

El módulo registrará la función `api_function_callback` para que procese todas las llamadas a la api con acción **api_action**.
La función de callback deberá devolver un array con las opciones que la llamada a la api espere obtener en forma de un array en JSON.

La función `registerApiCallback()` admite un tercer argumento, para pasar información adicional, que luego se recibirá en la función del callback.

La función del callback recibe 3 argumentos: **$identifier**, **$data** y **$cbdata**.

 * **identifier**: identificador por el que esta función está procesando la llamada a la api. Es el valor del primer argumento de `registerApiCallback()`. Útil si se usa una misma función para procesar varias acciones de la api.

 * **$data**: los datos de la petición de la api en un array asociativo, obtenidos de `$_POST`.

 * **$cbdata**: El contenido del tercer argumento de `registerApiCallback()`.

Los módulos pueden incluir código Javascript con peticiones AJAX que el mismo módulo procese, o simplemente capturar llamadas a la api creadas por la aplicación o por otros módulos.

Si una llamada a la API es capturada y procesada por un módulo, no se procesará con el código por defecto.

### Configuración inicial durante la instalación del módulo

En ocasiones es posible que un módulo necesite ejecutar un código la primera vez que se ejecuta. Por ejemplo, para configurar alguna clave, o para crear una tabla específica en la base de datos.

Para ello, los módulos tienen la posibilidad de implementar la función `setup()`.
Esta función debe ser pública y estática, ya que es llamada antes de instanciar la clase del módulo. Al ser estática, no tiene acceso a `$this->hc`, por lo que recibe la instancia de Hippocampus como argumento.

Esta función deberá devolver true para que el módulo cargue correctamente. Si esta función falla devolviendo false, el módulo será ignorado.
