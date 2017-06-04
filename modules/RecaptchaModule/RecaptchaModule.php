<?php

class RecaptchaModule extends HC_Module {
  public function __construct($hc) {
    parent::__construct($hc);

    $this->registerWindowCallback('recaptcha_config', 'RecaptchaConfigWindowCallback');

    $this->registerApiCallback('recaptchamodule_config', 'RecaptchaConfigApiCallback');
    $this->registerApiCallback('register', 'RecaptchaRegisterApiCallback');
  }

  public function onCreatingSidebar(&$sidebar) {
    $cu = $this->hc->getUserManager()->getLoggedInUser();
    $newEntry = [
      'icon' => '',
      'text' => 'Recaptcha',
      'id' => 'recaptcha_config',
    ];
    if ($cu !== false && $cu->isAdmin()) {
      array_push($sidebar, $newEntry); // To apend the entry
    }
  }

  private function checkGoogleRecaptcha($secret, $response, $remoteip = false) {
      $url = 'https://www.google.com/recaptcha/api/siteverify';
      $params = [
        'secret' => $secret,
        'response' => $response,
      ];
      if ($remoteip !== false) {
          $params['remoteip'] = $remoteip;
      }
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
      $result = curl_exec($ch);
      curl_close($ch);
      return json_decode($result, true);
  }


  public function RecaptchaConfigWindowCallback() {

    $html = '
    <p>Create a new "reCAPTCHA V2" app in <a href="https://www.google.com/recaptcha/admin" target="_blank">Manage your reCAPTCHA API keys</a>.</p>
    <p>Access the data in "Adding reCAPTCHA to your site" > "Keys".</p><br>
    <input type="text" name="recaptchamodule_config_token_public" placeholder="Site key" class="recaptchamodule_admininput"><br>
    <input type="text" name="recaptchamodule_config_token_secret" placeholder="Secret key" class="recaptchamodule_admininput"><br>
    <p id="recaptchamodule_config_message"></p>
    <input type="submit" value="Save Configuration" id="recaptchamodule_config_submit" onclick="recaptchamodule_config_submit()">
    ';

    return [
      'html' => $html,
      'title' => 'Recaptcha configuration',
    ];
  }

  public function RecaptchaConfigApiCallback($identifier, $data, $cbdata) {
    if (!empty($data['token_public']) && !empty($data['token_secret'])) {
      $this->hc->getDB()->setConfigValue("module.RecaptchaModule.token_public", $data['token_public']);
      $this->hc->getDB()->setConfigValue("module.RecaptchaModule.token_secret", $data['token_secret']);
      return [
        'status' => 'ok',
        'msg' => 'reCatpcha configuration updated',
      ];
    } else {
      return [
        'status' => 'error',
        'msg' => 'Fill all fields',
      ];
    }
  }

  public function RecaptchaRegisterApiCallback($identifier, $data, $cbdata) {
    // check credentials, create user, log in user?, return status
    $answer['status']='ok';
    if (empty($data['nombre'])) {
        $answer['status']='error';
        $answer['msg'][]='No se ha introducido el nombre.';
    }
    if (empty($data['email'])) {
        $answer['msg'][]='No se ha introducido el correo.';
        $answer['status']='error';
    }
    if (empty($data['usuario'])) {
        $answer['msg'][]='No se ha introducido el usuario.';
        $answer['status']='error';
    }
    if (empty($data['password'])) {
        $answer['msg'][]='No se ha introducido la contraseña.';
        $answer['status']='error';
    }
    if ($answer['status'] == 'error') {
        if (count($answer['msg']) === 0) {
            $answer['msg'] = 'Error desconocido.';
        }
        return $answer;
    }

    $gRecaptchaValidation = $this->checkGoogleRecaptcha($this->hc->getDB()->getConfigValue('module.RecaptchaModule.token_secret'), $data['g-recaptcha-response']);
    if (!$gRecaptchaValidation['success']) {
        $answer['status']='error';
        $answer['msg'][]='Error con el Captcha.';
        if (!empty($gRecaptchaValidation['error-codes'])) {
            foreach ($gRecaptchaValidation['error-codes'] as $gCaptchaErrorCode) {
                $answer['msg'][]='recaptcha_'.$gCaptchaErrorCode;
            }
        }
    }


    if ($this->hc->getDB()->getUserDataByUsername($data['usuario'])!== false) {
        $answer['msg'][]='Ese user ya existe, por favor prueba de nuevo.';
        $answer['status']='error';
    }
    if ($this->hc->getDB()->getUserDataByEmail($data['email'])!== false) {
        $answer['msg'][]='Ese email ya está registrado, por favor prueba de nuevo.';
        $answer['status']='error';
    }
    if (!preg_match('/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/', $data['email'])) {
        $answer['msg'][]='Ese email no es correcto, por favor prueba de nuevo.';
        $answer['status']='error';
    }
    if (!preg_match('/^([a-zA-Z0-9]{4,20})+$/', $data['usuario'])) {
        $answer['msg'][]='El usuario debe tener entre 4 y 20 caracteres, por favor prueba de nuevo.';
        $answer['status']='error';
    }
    if (!preg_match('/^([a-zA-Z0-9]{4,20})+$/', $data['usuario'])) {
        $answer['msg'][]='El usuario debe tener entre 4 y 20 caracteres, por favor prueba de nuevo.';
        $answer['status']='error';
    }
    /*if(!preg_match('/(?=.*[0-9])(?=.*[¡!¿?@#$%^&*\/\+_<>])(?=.*[a-z])(?=.*[A-Z]).{8,20}/', $data['password'])){
      $answer['msg'][]='La contraseña debe contener al menos un número, una letra minúscula, una mayúscula y un caracter espcecial; y tener entre 8 y 20 caracteres. Por favor, inténtelo de nuevo.';
      $answer['status']='error';
    }*/

    if ($answer['status'] == 'error') {
        if (count($answer['msg']) === 0) {
            $answer['msg'] = 'Error desconocido.';
        }
        return $answer;
    }

    $u = new User($this->hc, -1, $data['usuario'], $data['email'], false, '-', 3);
    $salt = Utils::randStr(32);
    $pw = hash('sha256', $salt.$data['password']);
    $s = $this->hc->getDB()->registerNewUser($u, $pw, $salt, $_SESSION['csalt']);
    $_SESSION['csalt'] = '';
    unset($_SESSION['csalt']);
    if ($s) {
        $answer['status'] = 'ok';
        $answer['msg'] = 'Usuario creado correctamente';
    } else {
        $answer['status'] = 'error';
        $answer['msg'] = 'Error desconocido.';
    }

    return $answer;
  }

  public function onCreatingMetacode(&$metacode) {
    $metacode[] = '<script>
  function recaptchamodule_config_submit() {
    var b = $("#recaptchamodule_config_submit");
    $.ajax({
      type: "POST",
      url: "api.php?action=recaptchamodule_config",
      dataType: "json",
      data: {
        "token_public": $("input[name=\'recaptchamodule_config_token_public\']").val(),
        "token_secret": $("input[name=\'recaptchamodule_config_token_secret\']").val(),
      },
      success: function(data) {
        $("#recaptchamodule_config_message").html(data["msg"]);
        if (data["status"] == "ok") {
          console.log("recaptcha configured ok");
          setTimeout(function(){
            $("input[name=\'recaptchamodule_config_token_public\']").val("");
            $("input[name=\'recaptchamodule_config_token_secret\']").val("");
          }, 1000);
        }
      },
    });
  }
  </script>';
    $metacode[] = '<script src="//www.google.com/recaptcha/api.js"></script>';
    $metacode[] = '<link rel="stylesheet" href="modules/RecaptchaModule/style.css">';
  }

}
