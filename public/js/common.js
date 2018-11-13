function getCartItem(link) {
    $.get(link,function (data, status) {
        $('#shopping-cart-block').html(data);
        console.log(status);
    });
}

