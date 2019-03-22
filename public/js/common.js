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
            $('#root-category-modification').html(makeTemplateCategoryCar(data));
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
    if (modification_auto === undefined){
        modification_auto = data.modification_auto;
    }
    if (type_auto === undefined){
        type_auto = data.type_auto;
    }
    let str_data = '';
    data.response.forEach(function (item) {
        str_data += `<div class="col-xs-12 col-sm-6 col-lg-4 padding-0 margin-bottom-0">
                            <div class="panel panel-default">
                              <div class="panel-heading">
                                <a class="h3" target="_blank" href="/brands?modification_auto=${modification_auto}&type_auto=${type_auto}">${item.description}</a>
                              </div>
                              <div class="panel-body row">
                                <div class="list-group" style="background-image: url('${(item.image_data !== null)?'/images/catalog/'+item.image_data.logo:''}');">`;
        item.sub_category.forEach(function (sub,i) {
            if(i <= 4){
                str_data += `<a href="/catalog/${sub.id}?modification_auto=${modification_auto}&type_auto=${type_auto}" class="list-group-item text-primary border-0">${sub.description}</a>`
            }
            if(i === 5){
                str_data += `<a class="list-group-item border-0" target="_blank" href="/brands?modification_auto=${modification_auto}&type_auto=${type_auto}"><small>показать все</small></a>`
            }
        });
        str_data += '</div></div></div></div>';
    });

    return str_data;
}

function dataFilter(level,link) {
    switch (level) {
        case 1:
            getDateFilter(link,'Марка','#brand_auto',['id','description']);
            $('#search-detail-car').addClass('hidden');
            if ($('#year_auto').val() === ''){
                $('#brand_auto').next().prop('disabled', 'disabled').selectric('refresh');
            }
            break;
        case 2:
            getDateFilter(link,'Модель','#model_auto',['id','name']);
            $('#search-detail-car').addClass('hidden');
            if ($('#brand_auto').val() === ''){
                $('#model_auto').prop('disabled', 'disabled').selectric('refresh');
            }
            break;
        case 3:
            getDateFilter(link,'Кузов','#body_auto',['displayvalue','displayvalue']);
            $('#search-detail-car').addClass('hidden');
            if ($('#model_auto').val()){
                $('#body_auto').prop('disabled', 'disabled').selectric('refresh');
            }
            break;
        case 4:
            getDateFilter(link,'Двигатель','#engine_auto',['displayvalue','displayvalue']);
            $('#search-detail-car').addClass('hidden');
            if ( $('#body_auto').val() !== ''){
                $('#engine_auto').prop('disabled', 'disabled').selectric('refresh');
            }
            break;
        case 5:
            getDateFilter(link,'Модификация','#modification_auto',['id','name']);
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

    $('#profile_track_order').submit(function (e) {
        e.preventDefault();
        $('#result_track_order ').removeClass('hidden').find('.panel-body').html('<p class="text-center"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></p>');
        $.get(`${$(this).attr('action')}?id=${$('#profile_track_id_order').val()}`,function (data) {
            if (data.no_success !== undefined){
                $('#result_track_order .panel-body').html(`<div class="alert alert-danger" role="alert">${data.no_success}</div>`);
            }else if(data.order !== undefined) {
                switch (data.order.oder_status) {
                    case 2:
                        $('#result_track_order .panel-body').html('<div class="alert alert-info" role="alert"><p class="text-center">Заказ был сохранён, но ещё не обработан менеджером</p></div>');
                        break;
                    case 3:
                        $('#result_track_order .panel-body').html(`<div class="alert alert-success" role="alert"><p class="text-center">Заказ был обработан менеджером.<strong> В ближайшее время будет отправлен</strong></p></div>`);
                        break;
                    case 4:
                        $('#result_track_order .panel-body').html(`
                            <div class="col-xs-12 padding-0">
                                <div class="col-xs-2 hidden-xs">
                                    <svg style="width: 100%;height: auto;" height="480pt" viewBox="0 -72 480 480" width="480pt" xmlns="http://www.w3.org/2000/svg"><path d="m280 88h48v112h-48zm0 0" fill="#2d72bc"/><path d="m328 200h96l-28.71875-100.398438c-1.964844-6.859374-8.226562-11.589843-15.359375-11.601562h-51.921875zm0 0" fill="#c4f236"/><path d="m472 280v-48c0-17.671875-14.328125-32-32-32h-160v80zm0 0" fill="#4891d3"/><path d="m280 280v-240c0-17.671875-14.328125-32-32-32h-216c-13.253906 0-24 10.746094-24 24v224c0 13.253906 10.746094 24 24 24zm0 0" fill="#57b7eb"/><path d="m144 56c-26.507812 0-48 21.492188-48 48 0 26.480469 21.519531 88 48 88s48-61.519531 48-88c0-26.507812-21.492188-48-48-48zm0 64c-8.835938 0-16-7.164062-16-16s7.164062-16 16-16 16 7.164062 16 16-7.164062 16-16 16zm0 0" fill="#f9e109"/><path d="m368 240c-22.089844 0-40-17.910156-40-40h-48v80h192v-40zm0 0" fill="#2d72bc"/><path d="m400 176c-26.507812 0-48-21.492188-48-48v-40h-24v112h96l-6.878906-24zm0 0" fill="#a1d51c"/><path d="m168 240c-70.691406 0-128-57.308594-128-128v-104h-8c-13.253906 0-24 10.746094-24 24v224c0 13.253906 10.746094 24 24 24h248v-40zm0 0" fill="#4891d3"/><path d="m112 104c.023438-23.402344 16.921875-43.378906 40-47.28125-2.644531-.441406-5.320312-.679688-8-.71875-26.507812 0-48 21.492188-48 48 0 26.480469 21.519531 88 48 88 2.78125-.085938 5.507812-.765625 8-2-22.71875-10.480469-40-62-40-86zm0 0" fill="#fcbc04"/><path d="m168 280c0 26.507812-21.492188 48-48 48s-48-21.492188-48-48 21.492188-48 48-48 48 21.492188 48 48zm0 0" fill="#f9e109"/><path d="m120 256c21.859375.035156 40.933594 14.835938 46.398438 36 1.066406-3.910156 1.601562-7.945312 1.601562-12 0-26.507812-21.492188-48-48-48s-48 21.492188-48 48c0 4.054688.535156 8.089844 1.601562 12 5.464844-21.164062 24.539063-35.964844 46.398438-36zm0 0" fill="#fcbc04"/><path d="m136 280c0 8.835938-7.164062 16-16 16s-16-7.164062-16-16 7.164062-16 16-16 16 7.164062 16 16zm0 0" fill="#4891d3"/><path d="m424 280c0 26.507812-21.492188 48-48 48s-48-21.492188-48-48 21.492188-48 48-48 48 21.492188 48 48zm0 0" fill="#f9e109"/><path d="m376 256c21.859375.035156 40.933594 14.835938 46.398438 36 1.066406-3.910156 1.601562-7.945312 1.601562-12 0-26.507812-21.492188-48-48-48s-48 21.492188-48 48c0 4.054688.535156 8.089844 1.601562 12 5.464844-21.164062 24.539063-35.964844 46.398438-36zm0 0" fill="#fcbc04"/><path d="m392 280c0 8.835938-7.164062 16-16 16s-16-7.164062-16-16 7.164062-16 16-16 16 7.164062 16 16zm0 0" fill="#4891d3"/><g fill="#39519d"><path d="m120 224c-30.929688 0-56 25.070312-56 56s25.070312 56 56 56 56-25.070312 56-56-25.070312-56-56-56zm0 96c-22.089844 0-40-17.910156-40-40s17.910156-40 40-40 40 17.910156 40 40-17.910156 40-40 40zm0 0"/><path d="m120 256c-13.253906 0-24 10.746094-24 24s10.746094 24 24 24 24-10.746094 24-24-10.746094-24-24-24zm0 32c-4.417969 0-8-3.582031-8-8s3.582031-8 8-8 8 3.582031 8 8-3.582031 8-8 8zm0 0"/><path d="m376 224c-30.929688 0-56 25.070312-56 56s25.070312 56 56 56 56-25.070312 56-56-25.070312-56-56-56zm0 96c-22.089844 0-40-17.910156-40-40s17.910156-40 40-40 40 17.910156 40 40-17.910156 40-40 40zm0 0"/><path d="m376 256c-13.253906 0-24 10.746094-24 24s10.746094 24 24 24 24-10.746094 24-24-10.746094-24-24-24zm0 32c-4.417969 0-8-3.582031-8-8s3.582031-8 8-8 8 3.582031 8 8-3.582031 8-8 8zm0 0"/><path d="m384 160h-16v-32c0-4.417969-3.582031-8-8-8s-8 3.582031-8 8v32c0 8.835938 7.164062 16 16 16h16c4.417969 0 8-3.582031 8-8s-3.582031-8-8-8zm0 0"/><path d="m144 48c-30.929688 0-56 25.070312-56 56 0 27.679688 22.320312 96 56 96s56-68.320312 56-96c0-30.929688-25.070312-56-56-56zm0 136c-18.398438 0-40-52.878906-40-80 0-22.089844 17.910156-40 40-40s40 17.910156 40 40c0 27.121094-21.601562 80-40 80zm0 0"/><path d="m144 80c-13.253906 0-24 10.746094-24 24s10.746094 24 24 24 24-10.746094 24-24-10.746094-24-24-24zm0 32c-4.417969 0-8-3.582031-8-8s3.582031-8 8-8 8 3.582031 8 8-3.582031 8-8 8zm0 0"/><path d="m192 184c0 4.417969 3.582031 8 8 8h32c4.417969 0 8-3.582031 8-8s-3.582031-8-8-8h-32c-4.417969 0-8 3.582031-8 8zm0 0"/><path d="m96 184c0-4.417969-3.582031-8-8-8h-32c-4.417969 0-8 3.582031-8 8s3.582031 8 8 8h32c4.417969 0 8-3.582031 8-8zm0 0"/><path d="m440 192h-10l-26.960938-94.640625c-2.964843-10.300781-12.402343-17.382813-23.117187-17.359375h-91.921875v-40c0-22.089844-17.910156-40-40-40h-216c-17.671875 0-32 14.328125-32 32v224c0 17.671875 14.328125 32 32 32h8c4.417969 0 8-3.582031 8-8s-3.582031-8-8-8h-8c-8.835938 0-16-7.164062-16-16v-224c0-8.835938 7.164062-16 16-16h216c13.253906 0 24 10.746094 24 24v232h-72c-4.417969 0-8 3.582031-8 8s3.582031 8 8 8h96c4.417969 0 8-3.582031 8-8s-3.582031-8-8-8h-8v-64h152c13.253906 0 24 10.746094 24 24v40h-8c-4.417969 0-8 3.582031-8 8s3.582031 8 8 8h16c4.417969 0 8-3.582031 8-8v-48c0-22.089844-17.910156-40-40-40zm-152-96h32v72c0 4.417969 3.582031 8 8 8s8-3.582031 8-8v-72h43.921875c3.671875-.144531 6.972656 2.230469 8 5.761719l25.4375 90.238281h-125.359375zm0 0"/></g></svg>
                                </div>
                                <div class="col-xs-12 col-sm-10">
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            <p class="h4 margin-bottom-0">${data.order.track_data.Status}</p>
                                            <span class="text-info small">Ориентировочная дата доставки - ${data.order.track_data.ScheduledDeliveryDate}</span>
                                        </li>
                                        <li class="list-group-item">
                                            <span class="text-info small">Маршрут</span>
                                            <p class="h6">${data.order.track_data.CitySender} - ${data.order.track_data.CityRecipient}</p>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-3">
                                                    <span class="text-info small">Вес</span>
                                                    <p class="h6">${data.order.track_data.DocumentWeight} кг.</p>
                                                </div>
                                                <div class="col-xs-12 col-sm-9">
                                                    <span class="text-info small">Адрес доставки</span>
                                                    <p class="h6">${data.order.track_data.WarehouseRecipient}</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-3">
                                                    <span class="text-info small">Сумма к оплате</span>
                                                    <p class="h6">${data.order.track_data.DocumentCost} грн.</p>
                                                </div>
                                                <div class="col-xs-12 col-sm-9">
                                                    <span class="text-info small">Плательщик</span>
                                                    <p class="h6">${data.order.track_data.PayerType === 'Recipient'?'Получатель':'Отправитель'}</p>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        `);
                        break;
                    case 5:
                        $('#result_track_order .panel-body').html(`<div class="alert alert-danger" role="alert"><p class="text-center">Заказ был отменен. Свяжитесь с администрацией для более детальной информацией!</p></div>`);
                        break;
                    case 6:
                        $('#result_track_order .panel-body').html(`<div class="alert alert-success" role="alert"><p class="text-center">Заказ был успешно закрыт.</p></div>`);
                        break;
                    default:
                        $('#result_track_order .panel-body').html(`<div class="alert alert-warning" role="alert"><p class="text-center">Упсс, данных по такому номеру заказа нету =(</p></div>`);
                }
            }
            console.log(data);
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
