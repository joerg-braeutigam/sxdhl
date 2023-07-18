{*
*  @author Saxtec <prestashop@saxtec.com>
*  @copyright  2007-2020 Saxtec
*  @license    Paid Licence
*}

<div class="panel col-lg-10 right-panel">
    <h3>
        <i class="fa fa-info-circle"></i> {l s='DHL Data Access' mod='sxdhl'} -
        <small>{$module_display|escape:'htmlall':'UTF-8'}</small>
    </h3>
    <p>{l s='To create valid DHL labels, you need a business customer contract with DHL' mod='sxdhl'}</p>
    <p>{l s='As well as valid access data to the DHL business customer portal.' mod='sxdhl'}</p>

    <form method="post"
          action="{$moduleAdminLink|escape:'htmlall':'UTF-8'}&page=dataConfigLive"
          class="form-horizontal">

        <div class="form-group">
            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
                <div class="text-right">
                    <label class="boldtext control-label">
                        {l s='User Business Portal Login' mod='sxdhl'}</label>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                <input type="text" name="dhl_user_live"
                       value="{$dhl_user_live|escape:'htmlall':'UTF-8'}"
                       class="form-control input-sm">
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
                <div class="text-right">
                    <label class="boldtext control-label">
                        {l s='DHL Business Portal Password' mod='sxdhl'}</label>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                <input type="password" name="dhl_pw_live"
                       value="{$dhl_pw_live|escape:'htmlall':'UTF-8'}"
                       class="form-control input-sm">
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
                <div class="text-right">
                    <label class="boldtext control-label">
                        {l s='DHL customer number' mod='sxdhl'}</label>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                <input type="text" name="dhk_ekp_live"
                       value="{$dhk_ekp_live|escape:'htmlall':'UTF-8'}"
                       class="form-control input-sm">
            </div>
        </div>
        <hr>
        <div class="form-group">
            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
                <div class="text-right">
                    <label class="boldtext control-label">
                        {l s='DHL billing number' mod='sxdhl'}</label>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                <input type="text" name="dhl_billing_live"
                       value="{$dhl_billing_live|escape:'htmlall':'UTF-8'}"
                       class="form-control input-sm">
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
                <div class="text-right">
                    <label class="boldtext control-label">
                        {l s='DHL national paket id' mod='sxdhl'}</label>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                <input type="text" name="dhl_national_live"
                       value="{$dhl_national_live|escape:'htmlall':'UTF-8'}"
                       class="form-control input-sm">
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
                <div class="text-right">
                    <label class="boldtext control-label">
                        {l s='DHL international paket id' mod='sxdhl'}</label>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                <input type="text" name="dhl_international_live"
                       value="{$dhl_international_live|escape:'htmlall':'UTF-8'}"
                       class="form-control input-sm">
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
                <div class="text-right">
                    <label class="boldtext control-label">
                        {l s='DHL national product paket' mod='sxdhl'}</label>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                <input type="text" name="dhl_product_national_live"
                       value="{$dhl_product_national_live|escape:'htmlall':'UTF-8'}"
                       class="form-control input-sm">
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
                <div class="text-right">
                    <label class="boldtext control-label">
                        {l s='DHL international product paket' mod='sxdhl'}</label>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                <input type="text" name="dhl_product_international_live"
                       value="{$dhl_product_international_live|escape:'htmlall':'UTF-8'}"
                       class="form-control input-sm">
            </div>
        </div>
        <div class="panel-footer">
            <button type="submit" value="1" id="submitDataConsent"
                    name="dhl_live"
                    class="btn btn-default pull-left">
                <i class="process-icon-save"></i>
                {l s='Save' mod='sxdhl'}
            </button>
        </div>
    </form>
    <br>
    <br>

    <div role="alert" data-alert="info" class="alert alert-info">
        {l s='We are happy to answer any questions you may have' mod='sxdhl'}<br>
        {l s='Please visit: ' mod='sxdhl'}
        <a href="https://addons.prestashop.com/de/2_community-developer?contributor=570952" target="_blank">PrestaShop AddOn Store</a>
    </div>
</div>
