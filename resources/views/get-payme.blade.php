<form id="theForm" method="POST" action="https://checkout.paycom.uz">
    <!-- Идентификатор WEB Кассы -->
    <input type="hidden" name="merchant" value="{{ $merchant_id }}"/>
    <!-- Сумма платежа в тийинах -->
    <input type="hidden" name="amount" value="{{ $price }}"/>
    <!-- Поля Объекта Account -->
    <input type="hidden" name="account[order_id]" value="{{ $order_id }}"/>
    <input type="hidden" name="callback" value="https://easyprint.uz/#/checkout/accept/true"/>
    <input type="hidden" name="callback_timeout" value="5000"/>
</form>
<script>
     document.getElementById('theForm').submit();
</script>
