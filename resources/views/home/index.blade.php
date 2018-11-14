@extends('layouts.app')

@section('content')

    <!-- Content -->
    <div id="content">

        <a href="{{route('product','test')}}">{{__('Ссылка на страницу продукта')}}</a>

        <!-- Filter -->
        @component('component.filter')

        @endcomponent

        @component('component.main_page_links')

        @endcomponent

        @component('component.shopping_info')

        @endcomponent

        <div class="container-fluid margin-top-40" style="background: #f4f4f4 url(https://dok.dbroker.com.ua/images/bg_bot_t.jpg) repeat-x center top;">
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-md-6">
                        <p class="h2">Как правильно купить запчасти?</p>
                        <p>Рано или поздно при использовании автомобиля его владельцу понадобится приобрести кузовные запчасти, или какие-то другие автозапчасти для замены тех, что вышли из стороя. Как выход из ситуации, автолюбитель обращается либо на СТО, либо к товарищу-специалисту. Однако в обоих вариантах возникнет переплата. Еще одним решением вопроса является самостоятельная покупка необходимой запчасти.  В данном случае сработает поговорка: «Зачем тратить больше?». Если в ближайшее время вас интересует поиск и продажа запчастей, то ниже Вы сможете ознакомиться с краткой инструкцией по подбору и покупке запчастей. </p>
                        <p>Где именно, и как купить запчасти для иномарок? Что лучше: автобазар, обычный магазин автозапчастей, или интернет-магазин запчастей? Если у вас нет времени на разъезды по городу в поисках необходимой запчасти, то вам в помощь интернет-магазин запчастей DOK, в котором есть большой каталог запчастей. Оформив заказ, вы получите максимально быструю его доставку. Если же Вы хотите купить запчасть не в виртуальном магазине, а так сказать, пощупав ее, то Вам либо на автомобильный рынок, либо же обычный магазин или автосервис. </p>
                        <p>Купить запчасти для иномарки Вы можете в любом из вышеприведенных мест, но при этом нужно придерживаться определенных правил при выборе и самой покупке запчасти. </p>
                        <p>Распишем ситуацию на примере, считая что Вы – владелец автомобиля Mazda. </p>
                        <p>В интернет-сети и на рынках большое число запчастей, предлагаемых на ваш автомобиль. Но мы хотим обратить ваше внимание на то, что практически все эти товары,  включая кузовные запчасти, не производятся компанией Mazda. Выбирая ту или иную запчасть, всегда интересуйтесь компанией-производителем. Касательно цен: они могут значительно отличаться в зависимости от того, оригинальная это запчасть или нет. </p>
                        <p>Оригинальные запчасти всегда имеют свою маркировку и фирменную упаковку. А также всегда идентифицируются по своему каталожному номеру. Если вам необходимо купить запчасти моторной группы, ходовой или же к коробке передач, то тут советуем не гнаться за максимальной экономией.  Это связано с тем, что у неоригинальных запчастей производственный ресурс меньше, чем у оригинальной запчасти. Если же Вам необходимо заменить ручку от двери, или фару, то в этом случае допустимо прибегнуть к экономии. </p>
                    </div>
                    <div class="col-md-6">
                        <p style="margin-top:60px;">Запчасти следует приобретать только там, где на ваше приобретение дадут гарантию. А при обнаружении брака будет, кому вы сможете предъявить претензию. </p>
                        <p>Всегда необходимо давать оценку продавцу, не важно – это интернет-магазин запчастей, или же торговый ларек на рынке. Прежде всего, обращайте внимание на ассортимент запчастей и профессиональную компетентность продавца или консультанта. Если же в процессе подбора запчастей у Вас возникли какие-либо сомнения, то стоит отказаться от покупки в этом месте. </p>
                        <p>Вы должны понимать, что при возникновении у продавца трудностей по подбору запчастей на ваш автомобиль по номеру кузова (VIN-коду), велика вероятность того, что купленная запчасть не подойдет для вашего авто. </p>
                        <p>Большую популярность в части продажи запчастей получили online-ресурсы, среди них и наш – интернет-магазин запчастей DOK. Для наших клиентов мы решили не только задачу в простой и удобной покупке запчастей, но и позаботились в их оперативной доставке по всей территории Украины. Используя сайт интернет-магазина ДОК вы сможете подобрать нужные вам запчасти того или иного производителя исходя из марки вашего автомобиля, ее модели, модификации и года выпуска, и цены, которую вы готовы заплатить.  DOK – это самый полноценный каталог запчастей для иномарок, а также максимально удобный поиск как по артикулу запчасти, так и по ее названию и производителю. К нашим неоспоримым преимуществам относится предоставление консультаций высококвалифицированных экспертов, которые всегда помогут в правильном подборе той или иной запчасти. Цены на запчасти в интернет-магазине гораздо дешевле, чем на автобазаре или же на СТО.  Помимо этого, покупая запчасти в DOK, вы спокойны за их совместимость с Вашим автомобилем. </p>
                        <p>Покупая запчасти в интернете, всегда интересуйтесь возможностью их обмена или же возврата. Серьезный интернет-магазин запчастей всегда защитит интересы своего покупателя. </p>
                    </div>

                </div>
            </div>
        </div>

    </div>
    <!-- End Content -->

@endsection
