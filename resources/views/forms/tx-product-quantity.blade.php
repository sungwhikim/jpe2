<div class="col-lg-2 col-md-2 col-sm-2 form-group quantity-container">
    <label class="col-lg-3 control-label">Quantity</label>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-float">
        @include('forms.tx-product-quantity-raw', ['model_object' => 'txCtrl.newItem', 'controller' => 'txCtrl'])
    </div>
</div>