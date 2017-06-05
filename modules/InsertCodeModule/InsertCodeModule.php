<?php

class InsertCodeModule extends HC_Module {
  public function __construct($hc) {
    parent::__construct($hc);

    $this->registerWindowCallback('software', 'InsertCodeCallback');
    $this->registerWindowCallback('software2', 'OnlineEditorCallback');
  }

  public static function setup($hc) {
    $sql = "CREATE TABLE IF NOT EXISTS hc_m_InsertCodeModule_data(
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

  public function onCreatingSidebar(&$sidebar) {
    $newEntry = [
      'icon' => 'software',
      'text' => 'Software',
      'id' => 'software',
    ];
    array_unshift($sidebar, $newEntry); // To prepend the entry
  }

  public function InsertCodeCallback() {
    return [
      'html' => '<form action="" method="post">
                    <textarea name="comments" id="comments">
                    Hey... Enter your code!

                         Or make the box bigger! --->
                    </textarea>
                    <input type="submit" value="Submit">
                 </form>

                 <p data-updatewindowboxservice="software2" data-cbdata-F1="v1" data-cbdata-F2="v2">Or try CodeAcademy....</p>',
      'title' => '<svg class="icon software windowicon">
                    <use xlink:href="#software">
                    </use>
                  </svg>
                  Insert code',
    ];
  }

  public function OnlineEditorCallback() {
    return [
      'html' => '<div class="geadiv"><object data="https://www.quackit.com/html/online-html-editor/">
                  </object></div><p data-updatewindowboxservice="software">Back....</p>',
      'title' => '<svg class="icon software windowicon">
                    <use xlink:href="#software">
                    </use>
                  </svg>
                  Online HTML Editor',
    ];
  }

  public function ExampleNotificationCallback($cbData) {
    return '<p>Module dummy data for notification: <em>Example</em></p><br><pre>'.print_r($cbData, true).'</pre>';
  }

}
