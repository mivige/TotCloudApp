<script>






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
                url: "api/actions/app_delete_user_rol_assign.php",
                success: function(data) {
                    if (data === 'OK') {
                        Swal.fire({
                            title: "Deleted",
                            text: "The record has been successfully deleted!",
                            icon: "success",
                            confirmButtonText: "OK",
                            confirmButtonColor: "#3085d6"
                        }).then(() => {
                            window.location.replace('index.php?opcion=users_roles&id=<?php echo $id?>');
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