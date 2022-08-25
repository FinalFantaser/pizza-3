<?php
echo '<?xml version="1.0" encoding="utf-8"?>';
?>
<service action="orders" id="{{$order->id}}" source="1">
  <order>
    <client_id></client_id>
    <client_full_name>{{$order->customerData->name}}</client_full_name>
      <client_address>
        <city>{{$order->customerData->city->name}}</city>
        <street>{{$order->customerData->street}}</street>
        <house>{{$order->customerData?->house ?? ''}}</house>
        <room>{{$order->customerData?->room ?? ''}}</room>
        <entrance>{{$order->customerData?->entrance ?? ''}}</entrance>
        <intercom>{{$order->customerData?->entrance ?? ''}}</intercom>
        <floor>{{$order->customerData?->entrance ?? ''}}</floor>
        <comment>{{$order->note ?? ''}}</comment>
        <map_latitude></map_latitude>{{--Координаты широты (Обязательно, если сайт не определяет зоны доставки)--}}
        <map_longitude></map_longitude>{{--Координаты долготы (Обязательно, если сайт не определяет зоны доставки)--}}
        <corp>{{$order->customerData?->corp ?? ''}}</corp>
      </client_address>
    <delivery_at>{{$order->time ?? ''}}</delivery_at>
    <pickup>{{$order->delivery_method === \App\Models\Shop\Delivery\DeliveryMethod::TYPE_PICKUP ? 1 : 0}}</pickup>
    <phone>{{$order->customerData->phone}}</phone>{{--Номер телефона клиента. Начиная с 8 без пробелов.--}}
    <client_comment></client_comment>{{--Комментарий клиента к заказу --}}
    <sum>{{$order->cost}}</sum>{{--Сумма заказа --}}
    <restaurant_id>{{$order->deliveryZone?->restaurant_id ?? ''}}</restaurant_id>{{--Код ресторана, который будет выполнять заказ (Определяется сайтом по зоне доставки. Если сайт работает без зон доставки, то обязательно заполнить координаты широты и долготы адреса)--}}
    @if($order->delivery_method === \App\Models\Shop\Delivery\DeliveryMethod::TYPE_COURIER)
      <restaurant_zone_id>{{$order->deliveryZone?->code ?? ''}}</restaurant_zone_id>{{--Код зоны доставки, зона доставки определяется сайтом (Если сайт работает без зон доставки, то обязательно заполнить координаты широты и долготы адреса)--}}
    @else
    <restaurant_zone_id></restaurant_zone_id>
    @endif
  </order>
  <payment>{{--Параметры оплаты заказа--}}
    <payment_id>{{$order->payment?->method_id ?? $order->payment_method_id}}</payment_id>{{--Код вида оплаты. Коды видов оплат обговариваются отдельно--}}
    <payment_title>{{$order->payment?->title ?? $order->payment_method_name}}</payment_title>
    <payment_status>{{$order->paid ? 2 : 1}}</payment_status>{{--Статус оплаты (1 - Не проведена, 2 - Проведена)--}}
    <payment_sum>{{$order->payment?->sum ?? $order->cost}}</payment_sum>{{--Сумма оплаты--}}
    <change_sum>{{$order->payment?->change_sum ?? ''}}</change_sum>{{--Сумма с которой нужна сдача--}}
  </payment>
  <items>{{--Товарная часть заказа. В этом разделе перечисляются товарные строки заказа.--}}
    @foreach ($order->items as $item)
      <item>{{--Базовая строка заказа--}}
      <product_id>{{$item->product_id}}</product_id>{{--Код товара--}}
      <product_parent_id></product_parent_id>{{--Код товара родителя. Для базовой строки всегда пустое--}}
      <price>{{$item->product_price}}</price>{{--Цена товара--}}
      <quantity>{{$item->product_quantity}}</quantity>{{--Кол-во товара--}}
      <name>{{$item->product_name}}</name>{{--Название товара--}}
      </item>

      @isset($item->product_options){{--Опции заказа--}}
        @foreach ($item->product_options as $option)
          @foreach($option['selected'] as $selected)
            <item>
              <product_id>{{$option['id']}}</product_id>{{--Код модификатора--}}
              <product_parent_id>{{$item->product_id}}</product_parent_id>{{--Код товара родителя--}}
              <product_price>{{$selected['price']}}</product_price>{{--Цена модификатора--}}
              <quantity>{{$selected['quantity'] ?? 1}}</quantity>{{--Кол-во модификатора. Умножается на кол-во базовой строки--}}
              <name>{{$selected['name']}}</name>{{--Название модификатора--}}
            </item>
          @endforeach
        @endforeach
      @endisset
    @endforeach
  </items>
</service>