<?php
echo '<?xml version="1.0" encoding="utf-8"?>';
?>
<service action="orders" id="{{$order->id}}" source="0">
  <order>
    <concept/> 
    <client_id/>
    <client_full_name>{{$order->customerData->name}}</client_full_name>
      <client_address>
        <city>{{$order->customerData->city->name}}</city>
        <street>{{$order->customerData->street}}</street>
        @isset($order->customerData->house)
          <house>{{$order->customerData->house}}</house>
        @else
        <house/>
        @endisset
        @isset($order->customerData->room){{--Квартира--}}
          <room>{{$order->customerData->room}}</room>
        @else
          <room/>
        @endisset
        @isset($order->customerData->entrance){{--Подъезд--}}
            <entrance>{{$order->customerData->entrance}}</entrance>
        @else
          <entrance/>
        @endisset
        @isset($order->customerData->intercom){{--Код домофона--}}
          <intercom>{{$order->customerData->entrance}}</intercom>
        @else
          <intercom/>
        @endisset
        @isset($order->customerData->floor){{--Этаж--}}
          <floor>{{$order->customerData->entrance}}</floor>
        @else
          <floor/>
        @endisset
        @isset($order->note){{--Комментарий клиента к адресу--}}
          <comment>{{$order->note}}</comment>
        @else
          <comment/>
        @endisset
        <map_latitude/>{{--Координаты широты (Обязательно, если сайт не определяет зоны доставки)--}}
        <map_longitude/>{{--Координаты долготы (Обязательно, если сайт не определяет зоны доставки)--}}
        @isset($order->customerData->corp){{--Корпус--}}
          <corp>{{$order->customerData->corp}}</corp>   
        @else
          <corp/>
        @endisset
      </client_address>
    @isset($order->time){{--Время доставки--}}
      <delivery_at>{{$order->time ?? ''}}</delivery_at>   
    @else
      <delivery_at/>
    @endisset
    <phone>{{$order->customerData->phone}}</phone>{{--Номер телефона клиента. Начиная с 8 без пробелов.--}}
    <client_comment/>{{--Комментарий клиента к заказу --}}
    <sum>{{$order->cost}}</sum>{{--Сумма заказа --}}
    <restaurant_id>{{$order->deliveryZone?->restaurant_id ?? ''}}</restaurant_id>{{--Код ресторана, который будет выполнять заказ (Определяется сайтом по зоне доставки. Если сайт работает без зон доставки, то обязательно заполнить координаты широты и долготы адреса)--}}
    <promo/>    
    @if($order->delivery_method === \App\Models\Shop\Delivery\DeliveryMethod::TYPE_COURIER)
      <restaurant_zone_id>{{$order->deliveryZone?->code ?? ''}}</restaurant_zone_id>{{--Код зоны доставки, зона доставки определяется сайтом (Если сайт работает без зон доставки, то обязательно заполнить координаты широты и долготы адреса)--}}
    @else
      <restaurant_zone_id/>
    @endif
  </order>
  <payment>{{--Параметры оплаты заказа--}}
    <payment_id>{{$order->payment?->method_id ?? ''}}</payment_id>{{--Код вида оплаты. Коды видов оплат обговариваются отдельно--}}
    <payment_title>{{$order->payment?->title ?? ''}}</payment_title>
    <payment_status>{{$order->paid ? 2 : 1}}</payment_status>{{--Статус оплаты (1 - Не проведена, 2 - Проведена)--}}
    <payment_sum>{{$order->payment?->sum ?? ''}}</payment_sum>{{--Сумма оплаты--}}
    <change_sum>{{$order->payment?->change_sum ?? ''}}</change_sum>{{--Сумма с которой нужна сдача--}}
  </payment>
  <items>{{--Товарная часть заказа. В этом разделе перечисляются товарные строки заказа.--}}
    @foreach ($order->items as $item)
      <item>{{--Базовая строка заказа--}}
      <product_id>{{$item->product_id}}</product_id>{{--Код товара--}}
      <product_parent_id/>{{--Код товара родителя. Для базовой строки всегда пустое--}}
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