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
            $('#root-category-modification').html(makeTemplateCategoryCar(data,modification_auto,type_auto));
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
    $('#search-detail-car').hide();
    $('#root-category-modification').html(`
                                            <p class="text-center">
                                                <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
                                                <span class="sr-only">Loading...</span>
                                            </p>
    `);
    $('#root-category-modification-wrapper').show();
    $('#history-car').html(`
        <div>
            <div class="col-sm-8">
                 <p class="h5 text-uppercase margin-bottom-0">${name}</p>
                 <span class="small text-info">${interval}</span><br>
                 <button type="button" class="add-car" onclick="$('#history-car').hide();$('#search-detail-car-form .search-car__list').show();$('#root-category-modification-wrapper').hide();"><span aria-hidden="true">+добавить авто</span></button>
            </div>
            <div class="col-sm-4 text-right">
                <img style="width: 100%;max-width: 70px;" src="https://yii.dbroker.com.ua/img/all_cars/${model_auto}f.png" alt="">
                <img style="width: 100%;max-width: 125px;" src="https://yii.dbroker.com.ua/img/all_cars/${model_auto}s.png" alt="">
            </div>
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
        $('#root-category-modification').html(makeTemplateCategoryCar(data,modification_auto,type_auto));
    });
}

function makeTemplateCategoryCar(data,modification_auto,type_auto) {
    let str_data = '';
    data.response.forEach(function (item) {
        str_data += `<div class="col-xs-12 col-sm-6 col-lg-4 padding-0 margin-bottom-0">
                            <div class="panel panel-default">
                              <div class="panel-heading">
                                <a target="_blank" href="/brands?modification_auto=${modification_auto}&type_auto=${type_auto}">${item.description}</a>
                              </div>
                              <div class="panel-body row">
                                <div class="list-group" style="background-image: url('${(item.image_data !== null)?'/images/catalog/'+item.image_data.logo:''}');">`;
        item.sub_category.forEach(function (sub) {
            str_data += `<a href="/catalog/${sub.id}?modification_auto=${modification_auto}&type_auto=${type_auto}" class="list-group-item text-primary border-0">${sub.description}</a>`
        });
        str_data += `<a class="list-group-item border-0" target="_blank" href="/brands?modification_auto=${modification_auto}&type_auto=${type_auto}"><small>показать все</small></a></div></div></div></div>`
    });

    return str_data;
}

function dataFilter(level,link) {
    switch (level) {
        case 1:
            getDateFilter(link,'Выберите марку','#brand_auto',['id','description']);
            $('#search-detail-car').addClass('hidden');
            if ($('#year_auto').val() === ''){
                $('#brand_auto').next().prop('disabled', 'disabled').selectric('refresh');
            }
            break;
        case 2:
            getDateFilter(link,'Выберите модель','#model_auto',['id','name']);
            $('#search-detail-car').addClass('hidden');
            if ($('#brand_auto').val() === ''){
                $('#model_auto').prop('disabled', 'disabled').selectric('refresh');
            }
            break;
        case 3:
            getDateFilter(link,'Выберите кузов','#body_auto',['displayvalue','displayvalue']);
            $('#search-detail-car').addClass('hidden');
            if ($('#model_auto').val()){
                $('#body_auto').prop('disabled', 'disabled').selectric('refresh');
            }
            break;
        case 4:
            getDateFilter(link,'Выберите двигатель','#engine_auto',['displayvalue','displayvalue']);
            $('#search-detail-car').addClass('hidden');
            if ( $('#body_auto').val() !== ''){
                $('#engine_auto').prop('disabled', 'disabled').selectric('refresh');
            }
            break;
        case 5:
            getDateFilter(link,'Выберите модификацию','#modification_auto',['id','name']);
            $('#search-detail-car').addClass('hidden');
            if ($('#engine_auto').val() !== ''){
                $('#modification_auto').prop('disabled', 'disabled').selectric('refresh');
            }
            break;
        case 6:
            if($('#modification_auto').val() !== ''){
                $('#search-detail-car').removeClass('hidden').show();
                $('#car_f').attr('src',`https://yii.dbroker.com.ua/img/all_cars/${$('#model_auto').val()}f.png`);
                $('#car_s').attr('src',`https://yii.dbroker.com.ua/img/all_cars/${$('#model_auto').val()}s.png`);
            }
            break;
        default:
            $('.search-car__list').children('li:not(:first-child):not(:nth-child(2))').find('select').prop('disabled', 'disabled').selectric('refresh');
    }
}

function getDateFilter(link,mass,obj,dataKey) {
    $.get(link, function(data) {
        let str_data = `<option selected value="">${mass}</option>`;
        data.response.forEach(function (item) {
            str_data += `<option value="${item[dataKey[0]]}">${item[dataKey[1]]}</option>`
        });
        $(obj).removeAttr('disabled').html(str_data).selectric('refresh');
    });
}

function getSub(type,id = null,obj,link) {

    if (id === null) {
        $.get(`${link}?type=${type}`,function (data) {
            let data_str = '';
            data.subCategory.forEach(function (item) {
                data_str += `<li class="list-group-item child-list-group-item">
                                                            <a class="root-link" onclick="getSub('${type}','${item.assemblygroupdescription}',this,'${link}')" href="#.">${item.assemblygroupdescription}</a>
                                                                <ul class="list-group" style="display: none">
                                                                    <li style="text-align: center;">
                                                                        <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
                                                                        <span class="sr-only">Loading...</span>
                                                                    </li>
                                                                </ul>
                                                            </li>`;
            });
            $($(obj).siblings("ul")).html(data_str);
        });
    } else {
        if (typeof id === 'string'){
            $.get(`${link}?type=${type}&category=${id}&level=assemblygroupdescription`,function (data) {
                let data_str = '';
                data.subCategory.forEach(function (item,i,array) {
                    data_str += `<li class="list-group-item child-list-group-item">
                                                                <a href="${document.location.origin}/catalog/${item.id}?type=${type}">${(array[i].normalizeddescription === array[(array.length !== i + 1 ?i + 1:i)].normalizeddescription)?item.usagedescription:item.normalizeddescription}</a>
                                                            </li>`;
                });
                $($(obj).siblings("ul")).html(data_str);
            });
        } else {

        }
    }
}

$(document).ready(function () {
    $(function(){
        $('.qg-elements').click(function(){
            var qgParent = $(this).parent();
            if(qgParent.hasClass('qgExpandClosed')) {
                qgParent.removeClass('qgExpandClosed').addClass('qgExpandOpen');
            } else {
                qgParent.removeClass('qgExpandOpen').addClass('qgExpandClosed');
            }
        });
    });

    $('#qgTree > .qgContainer > li:first-child').addClass('to-parts');

    $('.qgContent').click(function(e){
        e.preventDefault();

        $('.oe-list').html(`<div id="load_block" class="text-center margin-top-50">
                        <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
                        <span class="sr-only">Loading...</span>
                    </div>`);

        const link = $('#ajax_data_vin_link').val();
        const token = $('#ajax_data_vin_token').val();
        var dataLink = $(this).find('a');

        if (dataLink.length === 0)
            return false;

        var postData = {
            ajaxQuery: 'getOeDetails',
            ssd: dataLink.data('ssd'),
            vid: dataLink.data('vid'),
            catalog: dataLink.data('catalog'),
            gid: dataLink.data('gid'),
            _token: token
        };

        $.ajax({
            type: 'POST',
            url: link,
            data: postData,
            dataType: 'json',
            success: function (data) {
                if (data) {
                    var list = $('.oe-list');
                    $('body').addClass('oe-category-page');
                    list.html('');
                    var html = '<div><h2>Запчасти в группе</h2></div>';
                    $.each(data.category,function(k,v){
                        html += custom(v, dataLink,'');
                    });
                    list.append(html);
                    $('.ajax').colorbox({
                        width:"30%",
                        title: function() {
                            var title = $(this).attr('title');
                            var url = $(this).data('url');
                            console.log($(this));
                            return '<a href="' + url + '" target="_blank">' + title + '</a>'
                        }
                    });
                    $('.oem_additional_items_link a').click(function() {
                        var oem_additional_items = $('.oem_additional_items');
                        if (oem_additional_items.hasClass('oem_additional_items_visible')) {
                            oem_additional_items.removeClass('oem_additional_items_visible');
                        } else {
                            oem_additional_items.addClass('oem_additional_items_visible');
                        }
                    });
                }
            }
        });
    });
});


//MAKE TPL FOR VINDECODER AJAX REQUEST
function custom(obj, dataLink,trademark) {
    var tpl = '<div class="oe-category">'
        + '<div class="oe-category-title"><h3>' + obj.name[0] + '</h3></div>'
        + '<div class="oe-item-union">';

    $.each(obj.items, function(k,v){
        var url = "?action=unitInfo"
            + "&catalog=" + dataLink.data('catalog')
            + "&unitid=" + v.attributes['@attributes']['unitid']
            + "&vehicleid=" + dataLink.data('vid')
            + "&vid=" + dataLink.data('vid')
            + "&infoVIN=" + dataLink.data('infovin')
            + "&categoryid=-1"
            + "&ssd=" + v.attributes['@attributes']['ssd'];

        tpl += '<div class="oe-item-block">'
            + '<div class="oe-title"><h4><a onclick="getAllInfoVinDecode(\'' + url + '\');" href="#"><strong>' + v.attributes['@attributes'].code + ': </strong>' + v.attributes['@attributes'].name + '<img src="https://exist.ua/images/arrow.svg" alt="arrow" class="arrow-svg" /> <span class="attentionText">все детали узла</span></a></h4></div>'
            + '<div class="oe-image-block">'
            + '<img title="' + v.attributes['@attributes'].name + '" src="' + (v.attributes['@attributes'].imageurl).replace('%size%','175') + '">'
            + '<div class="ajax cboxElement" data-url= "' + url + '" title="' + v.attributes['@attributes'].code + ': ' + v.attributes['@attributes'].name + '" href="' + (v.attributes['@attributes'].imageurl).replace('%size%','source') + '"></div>'
            + '</div>'
            + '<div class="oe-models">'
            + '<table class="oe-table">'
            + '<thead>'
            + '<tr>'
            + '<th>OEM</th>'
            + '<th>Наименование детали</th>'
            + '</tr>'
            + '</thead>'
            + '<tbody>';
        $.each(v.Detail, function(k,v){
            tpl += '<tr>'
                + '<td><a target="_blank" href="/catalog?pcode=' + v.oem + '&trademark=' + trademark + '">' + v.oem + '</a></td>'
                + '<td class="width-130"><a target="_blank" href="/catalog?pcode=' + v.oem + '&trademark=' + trademark + '">' + v.name + '</a></td>'
                + '</tr>';
        });
        if (v.additional) {
            tpl += '<tr class="oem_additional_items_link">'

                + '<td colspan="2"><a href="javascript:void(0);">другие части узла</a></td>'
                + '</tr>';
            //tpl += '<tr class="oem_additional_items"><td colspan="2"><table>';
            $.each(v.additional, function (k, v) {
                tpl += '<tr class="oem_additional_items oem_additional_items_visible">'
                    + '<td><a target="_blank" href="/catalog?pcode=' + v.oem + '&trademark=' + trademark + '">' + v.oem + '</a></td>'
                    + '<td class="width-130"><a target="_blank" href="/catalog?pcode=' + v.oem + '&trademark=' + trademark + '">' + v.name + '</a></td>'
                    + '</tr>';
            });
            //tpl += '</table></td></tr>';
        }
        tpl += '</tbody>'
            + '</table>'
            + '</div>'
            + '</div>';
    });
    tpl += '</div>'
        + '</div>';

    return tpl;
}

function getAllInfoVinDecode(link) {
    $('#data_link_vin_decode').val(link);
    $('#form_vin_decode_page').submit();
    return false;
}