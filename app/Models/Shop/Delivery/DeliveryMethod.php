<?php

namespace App\Models\Shop\Delivery;

use App\Models\Shop\Delivery\PickupPoint;
use App\ReadRepository\Shop\Delivery\PickupPointReadRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryMethod extends Model
{
    use HasFactory;

    public $timestamps = false;

    const TYPE_PICKUP = 'pickup'; //Самовывоз
    const TYPE_COURIER = 'courier'; //Доставка курьером

    protected $fillable = [
        'name', 'type', 'cost', 'free_from', 'min_weight', 'max_weight',
    ];

    private function typeIs(string $type): bool //Проверяет, относится ли метод доставки к определённому типу
    {
        return $this->id === $type;
    } //typeIs

    public function isPickup(): bool //Проверяет, является ли данный тип самовывозом
    {
        return $this->typeIs(self::TYPE_PICKUP);
    } //isPickup

    public function isDelivery(): bool //Проверяет, является ли данный тип доставкой курьером
    {
        return $this->typeIs(self::TYPE_COURIER);
    } //isDelivery

    public function pickupPoints(City $city) //Получить пункты самовывоза по городу
    {
        return (new PickupPointReadRepository)->findByCity($city->id);
    } //pickupPoints
}
