$(document).ready(function () {
  $("#Tabla_Carpetas").DataTable({
    searching: false,
    ordering: false,
    lengthChange: false,
    info: false,
    paginate: false,
  });

  $("#Tabla_Archivos").DataTable({
    // searching: false,
    ordering: false,
    lengthChange: false,
    info: false,
    paginate: false,
  });
});

/*--------->  Muestra el modal para compartir archivos <---------*/
function MostrarModalCompartirArchi(id) {
  $("#idArchiCompartido").val(id);
  $("#ModalCompartir").modal("show");
};

/*--------->  Elimina el archivo seleccionado <---------*/
function EliminarArchi(id) {
  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: "btn btn-success m-1",
      cancelButton: "btn btn-danger m-1",
    },
    buttonsStyling: false,
  });

  swalWithBootstrapButtons
    .fire({
      title: "Está seguro de eliminar el archivo?",
      text: "Recuerde que no puede revertir esto!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Sí, eliminar!",
      cancelButtonText: "No, cancelar!",
      reverseButtons: true,
    })
    .then((result) => {
      if (result.value) {
        try {
          $.post(
            "codigos/BorArchivos.php",
            {
              id: id,
            },
            function (res) {
              if (res == "1") {
                location.href = "mydrive.php";
                // MostrarNotify("Archivo Eliminado", "success");
              } else {
                MostrarNotify("Archivo no Eliminado", "error");
              }
            }
          );
        } catch (e) {
          console.log(e);
        }
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        //swalWithBootstrapButtons.fire(
        //    'Cancelled',
        //    'Your imaginary file is safe :)',
        //    'error'
        //)
      }
    });
};

/*--------->  Elimina la carpeta seleccionado <---------*/
function EliminarCarpeta(id) {
  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: "btn btn-success m-1",
      cancelButton: "btn btn-danger m-1",
    },
    buttonsStyling: false,
  });

  swalWithBootstrapButtons
    .fire({
      title: "Está seguro de eliminar la carpeta?",
      text: "Recuerde que no puede revertir esto!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Sí, eliminar!",
      cancelButtonText: "No, cancelar!",
      reverseButtons: true,
    })
    .then((result) => {
      if (result.value) {
        try {
          $.post(
            "codigos/borCarpeta.php", {
              id: id,
            },
            function (res) {
              if (res == "1") {
                location.href = "mydrive.php";
                MostrarNotify("Archivo Eliminado", "success");
              } else {
                MostrarNotify("Archivo no Eliminado", "error");
              }
            }
          );
        } catch (e) {
          console.log(e);
        }
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        //swalWithBootstrapButtons.fire(
        //    'Cancelled',
        //    'Your imaginary file is safe :)',
        //    'error'
        //)
      }
    });
};

/*--------->  Busca los usuarios con los que se compartio el archivo <---------*/
function ArchivoCompartidoCon(id) {
  $.post(
    "codigos/BuscarArchiComparUsuario.php",
    {
      idArchiCompar: id,
    },
    function (res) {
      if (res != "") {
        var datos = JSON.parse(res);

        //Agrega los usuarios
        for (let i = 0; i < datos.length; i++) {
          // Agrega los botones al div
          $("#lstUsuarios").prepend(
            `<button class="btn btn-info btn-sm m-1">${datos[i]}</button>`
          );
        }
        //Muestra el modal
        $("#ModalListaArchiComparUsuarios").modal("show");

        // ESTE SE USA CON LAS SENTENCIAS QUE ESTAN COMENTADAS EN EL .PHP
        // var datos = JSON.parse(res);
        // for (let i = 0; i < datos["usuario"].length; i++) {
        //   alert(datos["usuario"][i]);
        // }
      }
    }
  );
};

/*--------->  Borra los botones creados anteriormente cuando se cierra el modal <---------*/
function BorrarDivModalCompartido() {
  var element = document.getElementById("lstUsuarios");
  while (element.firstChild) {
    element.removeChild(element.firstChild);
  }
};

/*--------->  Al seleccionar una carpeta, muestra los datos que contiene es una tabla <---------*/
function MostrarArchiCarpeta(id, nomCarpeta) {
  $("#RenderTablaArchivos").addClass("hide");
  $("#RenderTablaArchiCarpetas").removeClass("hide");

  document.getElementById("NomCarpeta").innerText = nomCarpeta;
  $("#bc").removeClass("hide");
  document.getElementById("bc").innerText = nomCarpeta;
};

/*--------->  Mensaje Sweet Alert <---------*/
function MostrarSweetAlert(icon, text) {
  Swal.fire({
    position: "center",
    icon: icon,
    title: text,
    showConfirmButton: false,
    timer: 1600,
  });
};

/*--------->  Mensaje Notify <---------*/
function MostrarNotify(alerta, msj) {
  $.notify(msj, {
    globalPosition: "top center",
    className: alerta,
  });
};
