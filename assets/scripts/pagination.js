$(function() {

    $('#search_product_search').click(()=>{
        $('#search_product_page').val('');
    });

    $('a.page-link').click((event)=>{
        event.preventDefault();

        $('#search_product_page').val($(event.target).attr('data-page'));
        $('#form_search').submit();
    });

});