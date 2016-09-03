<div class="row col-lg-12 checkbox-group">
    <div class="col-lg-2 col-md-3 col-sm-3 col-xs-4">
        <div class="checkbox checkbox-slider--b checkbox-slider-md">
            <label>
                <input type="checkbox" name="asn" value="true" ng-model="{{ $model }}.tx_email_asn"><span>ASN</span>
            </label>
        </div>
    </div>
    <div class="col-lg-2 col-md-3 col-sm-3 col-xs-4">
        <div class="checkbox checkbox-slider--b checkbox-slider-md">
            <label>
                <input type="checkbox" name="csr" value="true" ng-model="{{ $model }}.tx_email_csr"><span>CSR</span>
            </label>
        </div>
    </div>
    <div class="col-lg-2 col-md-3 col-sm-3 col-xs-3">
        <div class="checkbox checkbox-slider--b checkbox-slider-md">
            <label>
                <input type="checkbox" name="receive" value="true" ng-model="{{ $model }}.tx_email_receive"><span>Receiving</span>
            </label>
        </div>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3">
        <div class="checkbox checkbox-slider--b checkbox-slider-md">
            <label>
                <input type="checkbox" name="ship" value="true" ng-model="{{ $model }}.tx_email_ship"><span>Shipping</span>
            </label>
        </div>
    </div>
</div>