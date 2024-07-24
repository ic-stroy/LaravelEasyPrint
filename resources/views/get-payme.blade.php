@php
     use App\Constants;
@endphp
<form method="POST" action="https://test.paycom.uz">
    <!-- Идентификатор WEB Кассы -->
    <input type="text" name="merchant" value="{{ Constants::PAYME_MERCHANT_ID }}"/>
    <!-- Сумма платежа в тийинах -->
    <input type="text" name="amount" value="{{ $amount*100 }}"/>
    <!-- Поля Объекта Account -->
    <input type="text" name="account[order_id]" value="{{ $order_id }}"/>
    <button type="submit">Оплатить с помощью <b>Payme</b></button>
</form>
