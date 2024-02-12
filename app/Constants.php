<?php

namespace App;

class Constants {

   //   STATUS and  Product slide_show status
      const ACTIVE = 1;
      const NOT_ACTIVE = 0;

   // Order    type_ids
   const BASKED = 1;
   const ORDERED = 2;
   const PERFORMED = 3;
   const CANCELLED = 4;
   const ACCEPTED_BY_RECIPIENT = 5;
   // 1 basked 2 ordered 3 performed 4 cancelled 5 accepted by recipient

    //Order detail companyalar ga notificatsiya borganda ular qabul qilishi yoki kechiktrishi

    const ORDER_DETAIL_BASKET = 1;
    const ORDER_DETAIL_ORDERED = 2;
    const ORDER_DETAIL_PERFORMED = 3;
    const ORDER_DETAIL_PERFORMED_BY_SUPERADMIN = 4;
    const ORDER_DETAIL_CANCELLED = 5;
    const ORDER_DETAIL_ACCEPTED_BY_RECIPIENT = 6;

   // Order    payment_method
   const CASH_ON_DELIVERY = 1;
   const BANK_CARD = 2;

   // coupon type
   const TO_ORDER_COUNT = 0; //maslan 10 ta orderga
   const FOR_ORDER_NUMBER = 1; // masalan 10 - orderga  // agar typy null bosa demak coupon order countga berilmagan

    //Personal info
    const MALE = 1;
    const FEMALE = 2;

    //User roles

    const SUPER_ADMIN = 1;
    const ADMIN = 1;
    const MANAGER = 1;
    const USER = 1;

    //    Uploads relation_type
    const PRODUCT ="products";
    const WAREHOUSE ="warehouses";

    //    Discount status
    const DISCOUNT_PRODUCT_TYPE = 1;
    const DISCOUNT_WAREHOUSE_TYPE = 2;

}
