<script>



function pulsar_modificar(id) {
    
    window.location.replace('index.php?opcion=users&modificar=1&id='+id);
}


function pulsar_delete(id) {
    Swal.fire({
        title: "¿Estás seguro de borrar este registro?",
        text: "No se podrá recuperar",
        icon: "warning",
        role: "alertdialog",
        showCancelButton: true,
        confirmButtonText: "Sí, Borrar!",
        cancelButtonText: "No, Cancelar!",
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
    }).then((result) => {
        if (result.isConfirmed) {
            var parametros = { "id": id };
            $.ajax({
                data: parametros,
                type: "POST",
                url: "api/actions/app_delete_user.php",
                success: function(data) {
                    if (data === 'OK') {
                        Swal.fire({
                            title: "Borrado",
                            text: "El registro se ha borrado satisfactoriamente!",
                            icon: "success",
                            confirmButtonText: "OK",
                            confirmButtonColor: "#3085d6"
                        }).then(() => {
                            window.location.replace('index.php?opcion=users');
                        });
                    } else {
                        Swal.fire("Error", "El registro no se ha borrado satisfactoriamente.", "error");
                    }
                }
            });
        }
    });
}



        </script>