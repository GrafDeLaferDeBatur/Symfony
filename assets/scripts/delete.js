
// const $ = require('jquery');
$(document).ready(function() {

    $(".to-delete").on('click', function(event) {
        let slug = $(this).data('slug');
        $("#myModal").attr('data-slug', slug);
        $('#myModal').modal('show');
    });

    $("#deleteProduct").on('click', function(event){
        $.ajax({
            url: Routing.generate('deletingProduct', { slug: $("#myModal").data('slug') }),
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                console.log(data.message);
                location.reload();
            },
            error: function() {
                console.log('Произошла ошибка при удалении продукта');
            }
        });
    });

    $("#closeModal").on('click', function(event){
        $('#myModal').modal('hide');
    });
});
