jQuery(document).ready(function ($) {
  $("#semplisio #check").click(function (e) {
    $("#semplisio .check").show();
    $("#semplisio.diagnostica .wait").show();
    $("#semplisio.diagnostica .esitoko").hide();
    $("#semplisio.diagnostica .esitook").hide();

    $.ajax({
      url: wp.ajax.settings.url,
      method: "post",
      data: {
        action: "semplisio-diagnostica",
      },
      success: function (res) {
        $(e.target).hide();
        res = JSON.parse(res);
        var success = true;
        for (var i = 0; i < res.length; i++) {
          var result = res[i];
          $("." + result.id + " .wait").hide();
          if (result.status) {
            $("." + result.id + " .esitook").show();
            $("." + result.id + " .esitoko").hide();
          } else {
            success = false;
            $("." + result.id + " .esitoko").show();
            $("." + result.id + " .esitook").hide();
            $(e.target).show();
          }
        }
        if (success) {
          $(".procedi").prop("disabled", false);
          $(".procedi").removeAttr("disabled");
          $(".procedi").attr(
            "href",
            "/wp-admin/admin.php?page=semplisio&tab=impostazioni"
          );
        } else {
          $(".procedi").attr("disabled", "disabled");
          $(".procedi").prop("disabled", true);
          $(".procedi").attr("href", "#");
        }
      },
      error: function (res) {
        $(e.target).hide();
        alert(JSON.stringify(res));
        location.reload();
      },
    });
  });

  $("#semplisio-gestionale").change(function () {
    $("#semplisio .button").hide();
    $("#semplisio #scegli-gestionale").show();
    $(".form-semplisio-gestionale").addClass("hidden");
  });

  $("#semplisio #scegli-gestionale").click(function () {
    $(".form-semplisio-gestionale").addClass("hidden");
    $(".form-semplisio-gestionale input").each(function () {
      $(this).val("");
      $(this).prop("disabled", true);
    });
    $("." + $("#semplisio-gestionale").val()).removeClass("hidden");
    $("." + $("#semplisio-gestionale").val() + " input").each(function () {
      $(this).prop("disabled", false);
    });
    $("#semplisio #scegli-gestionale").hide();
    $("#semplisio #verifica-gestionale").show();
  });

  $("#semplisio #verifica-gestionale").click(function (e) {
    if (
      document.querySelector("#semplisio.impostazioni form").reportValidity()
    ) {
      $(e.target).prop("disabled", true);
      var dati = {};
      dati["action"] = "semplisio-gestionale";
      jQuery(":input:visible").each(function (ele) {
        if (this.name.substring(0, 10) == "semplisio-") {
          dati[this.name] = this.value;
        }
      });

      $.ajax({
        url: wp.ajax.settings.url,
        method: "post",
        data: dati,
        success: function (res) {
          $(e.target).prop("disabled", false);
          res = JSON.parse(res);

          if (res) {
            $("#semplisio #salva-campi").show();
            $(".procedi").prop("disabled", false);
            $(".procedi").removeAttr("disabled");
            $(".procedi").attr(
              "href",
              "/wp-admin/admin.php?page=semplisio&tab=workflows"
            );
            $(".errormsg").hide();
            $(e.target).hide();
          } else {
            $(".errormsg").show();
            $("#semplisio #salva-campi").hide();
            $(".procedi").attr("disabled", "disabled");
            $(".procedi").prop("disabled", true);
            $(".procedi").attr("href", "#");
          }
        },
        error: function (res) {
          $(e.target).prop("disabled", false);
          $("#semplisio #salva-campi").hide();
          alert(JSON.stringify(res));
        },
      });
    }
  });

  $("#semplisio input.semplisio-import").click(function () {
    $(".nota-all").hide();
    $(".nota-partial").hide();
    $("#import-prodotti-ora").prop("disabled", true);
    $("#semplisio-filtro-prodotti").val("");
    if ($(this).is(":checked")) {
      $(".prodotti-ora").show();
      $("#import-prodotti-ora").prop("disabled", false);
      if (this.value == 2) {
        $("#import-all").prop("checked", false);
        $(".nota-all").hide();
        $(".nota-partial").show();
        $("#semplisio-filtro-prodotti").val("woocommerce");
      } else {
        $("#import-partial").prop("checked", false);
        $("#semplisio-filtro-prodotti").val("");
        $(".nota-all").show();
        $(".nota-partial").hide();
      }
    } else {
      $(".prodotti-ora").hide();
      $("#import-prodotti-ora").val("");
    }
  });

  $("#semplisio #btn-next").click(function () {
    if (document.querySelector("#semplisio.workflows form").reportValidity()) {
      $("#semplisio .step1").addClass("hidden");
      $("#semplisio .step2").removeClass("hidden");
    }
  });
  $("#semplisio #btn-prev").click(function () {
    $("#semplisio .step1").removeClass("hidden");
    $("#semplisio .step2").addClass("hidden");
  });
});
