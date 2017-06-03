<?php

class ExampleModule extends HC_Module {
  public function __construct($hc) {
    parent::__construct($hc);

    $this->registerWindowCallback('example', 'ExampleWindowCallback');
    $this->registerWindowCallback('example2', 'ExampleWindowCallback2');

    $this->registerApiCallback('exampletestrequest', 'ExampleApiCallback');
  }

  /**
   * This function is called once, when the plugin is installed.
   */
  public static function setup($hc) {
    $sql = "CREATE TABLE hc_m_ExampleModule_data(
      id INT NOT NULL AUTO_INCREMENT,
      user INT NOT NULL,
      token VARCHAR(64) NOT NULL,
      data VARCHAR(32) NOT NULL,
      PRIMARY KEY (`id`)
    )";
    $db = $stmt = $hc->getDB()->getDBo();
    $stmt = $db->prepare($sql);
    return $stmt->execute();
  }

  /**
   * The function is called when creating the sidebar.
   * It receives the sidebar array by reference, so the module can modify the menu entries.
   */
  public function onCreatingSidebar(&$sidebar) {
    $newEntry = [
      'icon' => 'twitter',
      'text' => 'Example',
      'id' => 'example',
    ];
    array_unshift($sidebar, $newEntry); // To prepend the entry
  }

  /**
   *
   */
  public function onCreatingNotifications(&$notifications) {
    $newEntry = [
      'notificationCounter' => 2,
      'text' => 'Tienes {COUNTER} mensajes nuevos',
      'cb' => 'ExampleNotificationCallback',
      'cbData' => [],
    ];
    array_unshift($notifications, $newEntry); // To prepend the entry
  }

  /**
   * This funcion is called if a windowCallback is registered with registerWindowCallback in the constructor of the module.
   */
  public function ExampleWindowCallback() {
    return [
      'html' => '<p>Module dummy data for service: <em>Example</em></p><p data-updatewindowboxservice="example2" data-cbdata-F1="v1" data-cbdata-F2="v2">More....</p>',
      'title' => 'Example Title',
    ];
  }

  public function ExampleWindowCallback2($fields = []) {
    $html = '<p>Second callback!</p><p id="ExampleModule_window2_field1">Test</p><p data-updatewindowboxservice="example">Back....</p>';
    $html .= '<pre>'.print_r($fields, true).'</pre>';
    $html .= '<script>
    setInterval(
      function() {
        $.ajax({
          type: "POST",
          url: "api.php?action=exampletestrequest",
          dataType: "json",
          data: {
            "varr": "vall",
          },
          success: function(data) {
            //console.log(data);
            $("#ExampleModule_window2_field1").html(data["3"]);
          },
        })
      },
      5000);
    </script>';
    return [
      'html' => $html,
      'title' => 'Example Second Title',
    ];
  }

  /**
   * This funcion is called if an apiCallback is registered with registerApiCallback in the constructor of the module.
   */
  public function ExampleApiCallback($identifier, $data, $cbdata) {
    return [$identifier, $data, $cbdata, date('d/m/Y H:i:s')];
  }

  public function ExampleNotificationCallback($cbData) {
    return '<p>Module dummy data for notification: <em>Example</em></p><br><pre>'.print_r($cbData, true).'</pre>';
  }

}
