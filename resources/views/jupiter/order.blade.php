<?php
  echo '<?xml version="1.0" encoding="utf-8"?>';
?>
<service action="orders" id="{{$order->id}}" source="0"> <!--id - номер заказа. source - код источника заказа, коды источников обговариваются отдельно -->
  <order>
    <concept/> <!--Код концепции по мультибренду, если не используется <concept/>-->
    <client_id/>{{$order->customerData->id}}</client_id> <!--Код клиента на сайте. Не обязательно-->
    <client_full_name>{{$order->customerData->name}}</client_full_name> <!--Полное имя клиента-->
    <client_address> <!--Информация об адресе доставки-->
      <city>{{$order->customerData->city->name}}</city> <!--Город-->
      <street>{{$order->customerData->address}}</street> <!--Улица-->
      <house></house> <!--Дом-->
      <room></room> <!--Квартира-->
      <entrance></entrance> <!--Подъезд-->
      <intercom></intercom> <!--Код домофона-->
      <floor></floor> <!--Этаж-->
      <comment/> <!--Комментарий клиента к адресу -->
      <map_latitude/> <!--Координаты широты (Обязательно, если сайт не определяет зоны доставки)-->
      <map_longitude/> <!--Координаты долготы (Обязательно, если сайт не определяет зоны доставки)-->
      <corp/>
    </client_address>
    <delivery_at/>
    <phone>{{$order->customerData->phone}}</phone><!--Номер телефона клиента. Начиная с 8 без пробелов.-->
    <client_comment/> <!--Комментарий клиента к заказу -->
    <sum>{{$order->cost}}</sum> <!--Сумма заказа -->
    <restaurant_id>36</restaurant_id> <!--Код ресторана, который будет выполнять заказ (Определяется сайтом по зоне доставки. Если сайт работает без зон доставки, то обязательно заполнить координаты широты и долготы адреса)-->
    <promo/>
    <restaurant_zone_id>210</restaurant_zone_id> <!--Код зоны доставки, зона доставки определяется сайтом (Если сайт работает без зон доставки, то обязательно заполнить координаты широты и долготы адреса)-->
  </order>
  <payment> <!--Параметры оплаты заказа-->
    <payment_id>{{$order->payment?->method_id ?? ''}}</payment_id> <!--Код вида оплаты. Коды видов оплат обговариваются отдельно-->
    <payment_title>{{$order->payment?->title ?? ''}}</payment_title>
    <payment_status>{{$order->paid ? 2 : 1}}</payment_status> <!--Статус оплаты (1 - Не проведена, 2 - Проведена)-->
    <payment_sum>{{$order->payment?->sum ?? ''}}</payment_sum> <!--Сумма оплаты-->
    <change_sum>{{$order->payment?->change_sum ?? ''}}</change_sum> <!--Сумма с которой нужна сдача-->
  </payment>
  <items> <!--Товарная часть заказа. В этом разделе перечисляются товарные строки заказа.-->
    @foreach ($order->items as $item)
        <!--Базовая строка заказа-->
        <item>
            <product_id>{{$item->product_id}}</product_id> <!--Код товара-->
            <product_parent_id/> <!--Код товара родителя. Для базовой строки всегда пустое-->
            <price>{{$item->product_price}}</price> <!--Цена товара-->
            <quantity>{{$item->product_quantity}}</quantity> <!--Кол-во товара-->
            <name>{{$item->product_name}}</name> <!--Название товара-->
        </item>

        <!--Опции заказа-->
        @isset($item->product_options)
            @foreach ($item->product_options as $option)
                <item>
                    <product_id>{{$option['id']}}</product_id> <!--Код модификатора-->
                    <product_parent_id>{{$item->product_id}}</product_parent_id> <!--Код товара родителя-->
                    <product_price></product_price> <!--Цена модификатора-->
                    <quantity></quantity> <!--Кол-во модификатора. Умножается на кол-во базовой строки-->
                    <name>{{$option['selected'][0]}}</name> <!--Название модификатора-->
              </item>
            @endforeach
        @endisset
    @endforeach
  </items>
</service>