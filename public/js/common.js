function getCartItem(link) {
    $.get(link,function (data, status) {
        $('#shopping-cart-block').html(data);
    });
}

function changeCount(product,cart,link) {
    let count;
    if (document.documentElement.clientWidth > 767){
        count = $('#count'+product).val();
    } else {
        count = $('#count-mob'+product).val();
    }
    if (count.length < 1){
        return false;
    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },

    });

    $.ajax({
        type: 'POST',
        url: link,
        data: `product_id=${product}&cart_id=${cart}&count=${count}`,
        success: function (data) {
            $('#price'+product).text(`${data.response.product_cost} грн`);
            $('#cart .g-totel span').text(`${data.response.sum} грн`);
            $('#total-price,#total-price-checkout').text(`${data.response.sum} грн`);
        }
    });
}

function deleteProduct(product,cart,link) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },

    });

    $.ajax({
        type: 'POST',
        url: link,
        data: `product_id=${product}&cart_id=${cart}`,
        success: function (data) {
            $('#tr_product'+data.response.id_product).remove();
            $('#li_product'+data.response.id_product).remove();
            $('#cart .g-totel span').text(`${data.response.sum} грн`);
            $('#total-price').text(`${data.response.sum} грн`);
        }
    });
}

$(document).ready(function() {
    const numItems = $('li.fancyTab').length;
    $("li.fancyTab").width(100/numItems+'%');

    $('#search-detail-car button').click(function (e) {
        e.preventDefault();
        $('#root-category-modification').html(`
                                            <p class="text-center">
                                                <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
                                                <span class="sr-only">Loading...</span>
                                            </p>
        `);
        $('#root-category-modification-wrapper').show();
        $.post($('#search-detail-car-form').attr('action'),$('#search-detail-car-form').serialize(),function (data) {
            $('#root-category-modification-wrapper').show();

            let str_data = '';
            data.response.forEach(function (item) {
                str_data += `<div class="col-xs-12 col-sm-6 col-lg-4"><a class="h5" target="_blank" href="/catalog/${item.id}?modification_auto=${data.modification_auto}&type_auto=${data.type_auto}">${item.description}</a></div>`;
            });
            $('#root-category-modification').html(str_data);
        });
    });
});

$(window).load(function() {

    $('.fancyTabs').each(function() {
        var highestBox = 0;
        $('.fancyTab a', this).each(function() {
            if ($(this).height() > highestBox)
                highestBox = $(this).height();
        });
        $('.fancyTab a', this).height(highestBox);

    });
});

function urlRusLat(str) {
    str = str.toLowerCase();
    var cyr2latChars = new Array(
        ['а', 'a'], ['б', 'b'], ['в', 'v'], ['г', 'g'],
        ['д', 'd'],  ['е', 'e'], ['ё', 'yo'], ['ж', 'zh'], ['з', 'z'],
        ['и', 'i'], ['й', 'y'], ['к', 'k'], ['л', 'l'],
        ['м', 'm'],  ['н', 'n'], ['о', 'o'], ['п', 'p'],  ['р', 'r'],
        ['с', 's'], ['т', 't'], ['у', 'u'], ['ф', 'f'],
        ['х', 'h'],  ['ц', 'c'], ['ч', 'ch'],['ш', 'sh'], ['щ', 'shch'],
        ['ъ', ''],  ['ы', 'y'], ['ь', ''],  ['э', 'e'], ['ю', 'yu'], ['я', 'ya'],

        ['А', 'A'], ['Б', 'B'],  ['В', 'V'], ['Г', 'G'],
        ['Д', 'D'], ['Е', 'E'], ['Ё', 'YO'],  ['Ж', 'ZH'], ['З', 'Z'],
        ['И', 'I'], ['Й', 'Y'],  ['К', 'K'], ['Л', 'L'],
        ['М', 'M'], ['Н', 'N'], ['О', 'O'],  ['П', 'P'],  ['Р', 'R'],
        ['С', 'S'], ['Т', 'T'],  ['У', 'U'], ['Ф', 'F'],
        ['Х', 'H'], ['Ц', 'C'], ['Ч', 'CH'], ['Ш', 'SH'], ['Щ', 'SHCH'],
        ['Ъ', ''],  ['Ы', 'Y'],
        ['Ь', ''],
        ['Э', 'E'],
        ['Ю', 'YU'],
        ['Я', 'YA'],

        ['a', 'a'], ['b', 'b'], ['c', 'c'], ['d', 'd'], ['e', 'e'],
        ['f', 'f'], ['g', 'g'], ['h', 'h'], ['i', 'i'], ['j', 'j'],
        ['k', 'k'], ['l', 'l'], ['m', 'm'], ['n', 'n'], ['o', 'o'],
        ['p', 'p'], ['q', 'q'], ['r', 'r'], ['s', 's'], ['t', 't'],
        ['u', 'u'], ['v', 'v'], ['w', 'w'], ['x', 'x'], ['y', 'y'],
        ['z', 'z'],

        ['A', 'A'], ['B', 'B'], ['C', 'C'], ['D', 'D'],['E', 'E'],
        ['F', 'F'],['G', 'G'],['H', 'H'],['I', 'I'],['J', 'J'],['K', 'K'],
        ['L', 'L'], ['M', 'M'], ['N', 'N'], ['O', 'O'],['P', 'P'],
        ['Q', 'Q'],['R', 'R'],['S', 'S'],['T', 'T'],['U', 'U'],['V', 'V'],
        ['W', 'W'], ['X', 'X'], ['Y', 'Y'], ['Z', 'Z'],

        [' ', '_'],['0', '0'],['1', '1'],['2', '2'],['3', '3'],
        ['4', '4'],['5', '5'],['6', '6'],['7', '7'],['8', '8'],['9', '9'],
        ['-', '-']

    );

    var newStr = new String();

    for (var i = 0; i < str.length; i++) {

        ch = str.charAt(i);
        var newCh = '';

        for (var j = 0; j < cyr2latChars.length; j++) {
            if (ch == cyr2latChars[j][0]) {
                newCh = cyr2latChars[j][1];

            }
        }
        newStr += newCh;

    }
    return newStr.replace(/[_]{2,}/gim, '_').replace(/\n/gim, '');
}



function getCarsDetail(type_auto,year_auto,brand_auto,model_auto,modification_auto,engine_auto,body_auto,token,name,interval) {
    $('#search_cars_modal').modal('hide');
    $('#search-detail-car-form .search-car__list').hide();
    $('#root-category-modification').html(`
                                            <p class="text-center">
                                                <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
                                                <span class="sr-only">Loading...</span>
                                            </p>
    `);
    $('#root-category-modification-wrapper').show();
    $('#history-car').html(`
        <div>
            <button type="button" class="close" onclick="$('#history-car').hide();$('#search-detail-car-form .search-car__list').show();$('#root-category-modification-wrapper').hide();"><span aria-hidden="true">&times;</span></button>
            <p class="h5 text-uppercase">${name}</p>
            <span class="small text-info">${interval}</span>
        </div>
    `).show();
    $.post($('#search-detail-car-form').attr('action'),{
        'type_auto': type_auto,
        'year_auto': year_auto,
        'brand_auto': brand_auto,
        'model_auto': model_auto,
        'modification_auto': modification_auto,
        'engine_auto': engine_auto,
        'body_auto': body_auto,
        '_token': token
    },function (data) {
        let str_data = '';
        data.response.forEach(function (item) {
            str_data += `<div class="col-xs-12 col-sm-6 col-lg-4"><a class="h5" target="_blank" href="/catalog/${item.id}?modification_auto=${modification_auto}&type_auto=${type_auto}">${item.description}</a></div>`;
        });
        $('#root-category-modification').html(str_data);
    });
}