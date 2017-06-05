<?php

class InsertCodeModule extends HC_Module {
  public function __construct($hc) {
    parent::__construct($hc);

    $this->registerWindowCallback('software', 'InsertCodeWindowCallback');
    $this->registerWindowCallback('insertcodemodule_listcodes', 'ListCodesWindowCallback');
    $this->registerWindowCallback('insertcodemodule_checkcode', 'CheckCodeWindowCallback');

    $this->registerApiCallback('insertcodemodule_addCode', 'AddCodeApiCallback');
    $this->registerApiCallback('insertcodemodule_updateCode', 'UpdateCodeApiCallback');
    $this->registerApiCallback('insertcodemodule_setEnableCode', 'SetEnableCodeApiCallback');
  }

  public static function setup($hc) {
    $sql = "CREATE TABLE IF NOT EXISTS hc_m_InsertCodeModule_data(
      id INT NOT NULL AUTO_INCREMENT,
      code TEXT NOT NULL,
      active tinyint(1) DEFAULT 0,
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

  public function InsertCodeWindowCallback() {
    $html = '<textarea id="insertcodemodule_textarea"></textarea>';
    $html .= '<input type="submit" value="Add code" onclick="insertcodemodule_addcode()" id="insertcodemodule_addcode" />';
    $html .= '<input type="submit" value="List codes" data-updatewindowboxservice="insertcodemodule_listcodes" id="insertcodemodule_listcodes"/>';

    return [
      'html' => $html,
      'title' => '
      <svg class="icon software windowicon">
        <use xlink:href="#software">
        </use>
      </svg>
      Insert code',
    ];
  }

  public function ListCodesWindowCallback() {
    $html = '<input type="submit" value="Add code" data-updatewindowboxservice="software" />';
    $html .= '<table class="table table-responsive insertcodemodule_listcode">';
    $html .= "<tr><th class='insertcodemodule_tablecodefield'>Code Preview</th><th class='insertcodemodule_tableactivefield'>Active</th></tr>";

    $db = $stmt = $this->hc->getDB()->getDBo();
    $sql = "SELECT * FROM hc_m_InsertCodeModule_data";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $truncatelenght = 200;
    foreach ($rows as $row) {
      $code = $row['code'];
      $code = (strlen($code) > $truncatelenght) ? substr($code,0,$truncatelenght-3).'...' : $code;
      $code = htmlentities($code);
      $html .= "<tr><td data-updatewindowboxservice='insertcodemodule_checkcode' data-cbdata-Codeid='{$row['id']}' class='insertcodemodule_tablecodefield'>$code</td><td><label class='switch'><input type='checkbox' onchange='insertcodemodule_setEnableCode({$row['id']}, this.checked, this)' ".($row['active'] ? 'checked':'')."><div class='slider round'></div></label></td></tr>";
    }

    $html .= '</table>';
    return [
      'html' => $html,
      'title' => '
      <svg class="icon software windowicon">
        <use xlink:href="#software">
        </use>
      </svg>
      Insert code',
    ];
  }

  public function CheckCodeWindowCallback($fields = []) {
    $html = '';
    if (empty($fields['Codeid'])) return $this->ListCodesWindowCallback();

    $db = $stmt = $this->hc->getDB()->getDBo();
    $sql = "SELECT * FROM hc_m_InsertCodeModule_data WHERE id=?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$fields['Codeid']]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($rows) === 0) return $this->ListCodesWindowCallback();

    $code = $rows[0]['code'];
    $html = '<textarea id="insertcodemodule_textarea">'.$code.'</textarea>';
    $html .= '<input type="submit" value="Update code" onclick="insertcodemodule_updatecode('.$fields['Codeid'].')" id="insertcodemodule_updatecode"/>';
    $html .= '<input type="submit" value="List codes" data-updatewindowboxservice="insertcodemodule_listcodes" id="insertcodemodule_listcodes" />';

    return [
      'html' => $html,
      'title' => '
      <svg class="icon software windowicon">
        <use xlink:href="#software">
        </use>
      </svg>
      Insert code',
    ];
  }

  public function AddCodeApiCallback($identifier, $data, $cbdata) {
    if (empty($data['code']) || empty($data['doEnable'])) {
      return [
        'status' => 'error',
        'msg' => 'Fill the fields',
      ];
    }

    $db = $stmt = $this->hc->getDB()->getDBo();
    $sql = "INSERT INTO hc_m_InsertCodeModule_data
      (code, active)
      VALUES(:code, :active)";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':code', $data['code'], PDO::PARAM_STR);
    $stmt->bindValue(':active', $data['doEnable'], PDO::PARAM_INT);
    $s = $stmt->execute();

    if ($s) {
      return [
        'status' => 'ok',
        'msg' => 'Saved',
      ];
    } else {
      return [
        'status' => 'error',
        'msg' => 'Unkown',
      ];
    }
  }

  public function UpdateCodeApiCallback($identifier, $data, $cbdata) {
    if (empty($data['code']) || empty($data['id'])) {
      return [
        'status' => 'error',
        'msg' => 'Fill the fields',
      ];
    }

    $db = $stmt = $this->hc->getDB()->getDBo();
    $sql = "UPDATE hc_m_InsertCodeModule_data
      SET code=:code
      WHERE id=:id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':code', $data['code'], PDO::PARAM_STR);
    $stmt->bindValue(':id', $data['id'], PDO::PARAM_INT);
    $s = $stmt->execute();

    if ($s) {
      return [
        'status' => 'ok',
        'msg' => 'Saved',
      ];
    } else {
      return [
        'status' => 'error',
        'msg' => 'Unkown',
      ];
    }
  }

  public function SetEnableCodeApiCallback($identifier, $data, $cbdata) {
    if (empty($data['id']) || empty($data['doEnable'])) {
      return [
        'status' => 'error',
        'msg' => 'Fill the fields',
      ];
    }
    if ($data['doEnable'] === 'true') $data['doEnable'] = true;
    else $data['doEnable'] = false;

    $db = $stmt = $this->hc->getDB()->getDBo();
    $sql = "UPDATE hc_m_InsertCodeModule_data
      SET active=:active
      WHERE id=:id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':active', $data['doEnable'], PDO::PARAM_INT);
    $stmt->bindValue(':id', $data['id'], PDO::PARAM_INT);
    $s = $stmt->execute();

    if ($s) {
      return [
        'status' => 'ok',
        'msg' => 'Saved',
      ];
    } else {
      return [
        'status' => 'error',
        'msg' => 'Unkown',
      ];
    }
  }

  public function onCreatingMetacode(&$metacode) {
    $db = $stmt = $this->hc->getDB()->getDBo();
    $sql = "SELECT * FROM hc_m_InsertCodeModule_data";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as $row) {
      if ($row['active']) {
        $metacode[] = $row['code'];
      }
    }

    $metacode[] = '<link rel="stylesheet" href="modules/InsertCodeModule/style.css">';
    $metacode[] = '<script src="modules/InsertCodeModule/script.js"></script>';
  }

}
