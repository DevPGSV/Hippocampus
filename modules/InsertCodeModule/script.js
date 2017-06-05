function insertcodemodule_addcode() {
  var textarea = $('#insertcodemodule_textarea');
  $.ajax({
    type: "POST",
    url: "api.php?action=insertcodemodule_addCode",
    dataType: "json",
    data: {
      "code": textarea.val(),
      "doEnable": false,
    },
    success: function(data) {
      if (data["status"] == "ok") {
        textarea.val('');
      } else {
        alert("¡Error! ¡No se envió correctamente el código!");
      }
    },
  });
}

function insertcodemodule_updatecode(id) {
  var textarea = $('#insertcodemodule_textarea');
  $.ajax({
    type: "POST",
    url: "api.php?action=insertcodemodule_updateCode",
    dataType: "json",
    data: {
      "code": textarea.val(),
      "id": id,
    },
    success: function(data) {
      if (data["status"] == "ok") {
        alert("Code updated");
      } else {
        alert("¡Error! ¡No se envió correctamente el código!");
      }
    },
  });
}

function insertcodemodule_setEnableCode(id, doEnable, uiObject) {
  $.ajax({
    type: "POST",
    url: "api.php?action=insertcodemodule_setEnableCode",
    dataType: "json",
    data: {
      "id": id,
      "doEnable": doEnable,
    },
    success: function(data) {
      if (data["status"] == "ok") {
        console.log("code set enabled to " + doEnable + " correctly");
        console.log(uiObject);

      } else {
        setTimeout(function() {
          uiObject.checked = !uiObject.checked;
        }, 100);
      }
    },
  });
}
