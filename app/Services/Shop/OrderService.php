<?php
namespace App\Services\Shop;

use App\Models\Shop\Order\Order;
use App\Http\Requests\Api\Admin\Shop\Order\CancelRequest;
use App\Models\Shop\City;
use App\Repository\Shop\Order\OrderRepository;
use App\ReadRepository\Shop\OrderReadRepository;

class OrderService{
    private $repository;
    private $readRepository;

    public function __construct(OrderRepository $repository, OrderReadRepository $readRepository){
        $this->repository = $repository;
        $this->readRepository = $readRepository;
    } //Конструктор

    //
    // Методы для администраторов
    //
    public function payByAdmin(Order $order)
    {
        $this->repository->payByAdmin($order);
    } //payByAdmin

    public function makeSent(Order $order)
    {
        $this->repository->makeSent($order);
    } //makeSent

    public function makeCompleted(Order $order)
    {
        $this->repository->makeCompleted($order);
    } //makeCompleted

    public function cancelByAdmin(CancelRequest $request, Order $order)
    {
       $this->repository->cancelByAdmin($order, $request->reason);
    } //cancelByAdmin

    public function remove(Order $order)
    {
        $this->repository->remove($order);
    } //remove


    //
    // Методы для клиентов
    //
    public function payByCustomer(Order $order, $paymentMethod)
    {
        $this->repository->payByCustomer($order, $paymentMethod);
    } //payByCustomer

    public function cancelByCustomer(CancelRequest $request, Order $order)
    {
       $this->repository->cancelByUser($order, $request->reason);
    } //cancelByAdmin

    //
    //Методы для запросов В БД
    //
    public function getMethods(){
        return $this->readRepository->getMethods();    
    } //getMethods

    public function findByCity(City $city){
        return $this->readRepository->findByCity($city);
    } //findByCity
}