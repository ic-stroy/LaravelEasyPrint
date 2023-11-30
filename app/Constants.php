<?php

namespace App;

class Constants {

   //   STATUS
      const ACTIVE = 1;
      const NOT_ACTIVE = 2;

   // Order    type_ids
   const BASKED = 1;
   const ORDERED = 2;
   const ACCEPTED = 3;
   const ON_THE_WAY = 4;
   const FINISHED = 5;


   // 1 basked 2 ordered 3 accepted 4 on_the_way 5 finished

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

}
