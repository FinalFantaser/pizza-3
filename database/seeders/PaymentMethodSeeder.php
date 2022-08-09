<?php

namespace Database\Seeders;

use App\Models\Shop\Payment\PaymentMethod;
use App\Repository\Shop\Payment\PaymentMethodRepository;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder
{

    use WithoutModelEvents;

    public function __construct(
        private PaymentMethodRepository $repository
    ){} //Конструктор

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function(){
            $this->repository->create(code: PaymentMethod::CODE_CASH_PICKUP, title: PaymentMethod::TITLE_CASH_PICKUP );
            $this->repository->create(code: PaymentMethod::CODE_CASH_DELIVERY, title: PaymentMethod::TITLE_CASH_DELIVERY );
            $this->repository->create(code: PaymentMethod::CODE_CARD_PICKUP, title: PaymentMethod::TITLE_CARD_PICKUP );
            $this->repository->create(code: PaymentMethod::CODE_CARD_DELIVERY, title: PaymentMethod::TITLE_CARD_DELIVERY );
            $this->repository->create(code: PaymentMethod::CODE_ONLINE_PICKUP, title: PaymentMethod::TITLE_ONLINE_PICKUP );
            $this->repository->create(code: PaymentMethod::CODE_ONLINE_DELIVERY, title: PaymentMethod::TITLE_ONLINE_DELIVERY );
        });
    }
}
