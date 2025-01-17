<script>



function pulsar_modificar(id) {
    
    window.location.replace('index.php?opcion=paas_public_bandwidth&modificar=1&id='+id);
}


function pulsar_delete(id) {
    Swal.fire({
        title: "¿Are you sure you want to delete the record?",
        text: "Is not possible to recover it",
        icon: "warning",
        role: "alertdialog",
        showCancelButton: true,
        confirmButtonText: "Yes, Delete!",
        cancelButtonText: "No, Cancel!",
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
    }).then((result) => {
        if (result.isConfirmed) {
            var parametros = { "id": id };
            $.ajax({
                data: parametros,
                type: "POST",
                url: "api/actions/app_delete_paas_public_bandwidth.php",
                success: function(data) {
					data = data.trim();
                    if (data === 'OK') {
                        Swal.fire({
                            title: "Deleted",
                            text: "The record has been successfully deleted!",
                            icon: "success",
                            confirmButtonText: "OK",
                            confirmButtonColor: "#3085d6"
                        }).then(() => {
                            window.location.replace('index.php?opcion=paas_public_bandwidth');
                        });
                    } else {
                        Swal.fire("Error", "The record has NOT been successfully deleted.", "error");
                    }
                }
            });
        }
    });
}



        </script>