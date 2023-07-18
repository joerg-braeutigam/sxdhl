{*
*  @author Saxtec <prestashop@saxtec.com>
*  @copyright  2007-2020 Saxtec
*  @license    Paid Licence
*}

<div class="panel col-lg-10 right-panel">
    <h3>
        <i class="fa fa-info-circle"></i> {l s='Development Data Access' mod='sxdhl'} -
        <small>{$module_display|escape:'htmlall':'UTF-8'}</small>
    </h3>
    <p>{l s='In development mode, you only need access data to the developer portal on dhl.' mod='sxdhl'}</p>
    <form method="post"
          action="{$moduleAdminLink|escape:'htmlall':'UTF-8'}&page=dataConfigDev"
          class="form-horizontal">

        <div class="form-group">
            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
                <div class="text-right">
                    <label class="boldtext control-label">
                        {l s='User Name DEV' mod='sxdhl'}</label>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                <input type="text" name="dhl_user_dev"
                       value="{$dhl_user_dev|escape:'htmlall':'UTF-8'}" class="form-control input-sm">
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
                <div class="text-right">
                    <label class="boldtext control-label">
                        {l s='User Password DEV' mod='sxdhl'}</label>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                <input type="password" name="dhl_pw_dev"
                       value="{$dhl_pw_dev|escape:'htmlall':'UTF-8'}" class="form-control input-sm">
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
                <div class="text-right">
                    <label class="boldtext control-label">
                        {l s='DHL customer number DEV' mod='sxdhl'}</label>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                <input type="text" name="dhk_ekp_dev"
                       value="{$dhk_ekp_dev|escape:'htmlall':'UTF-8'}" class="form-control input-sm" readonly>
            </div>
        </div>
        <hr>
        <div class="form-group">
            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
                <div class="text-right">
                    <label class="boldtext control-label">
                        {l s='DHL billing number DEV' mod='sxdhl'}</label>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                <input type="text" name="dhl_billing_dev"
                       value="{$dhl_billing_dev|escape:'htmlall':'UTF-8'}" class="form-control input-sm" readonly>
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
                <div class="text-right">
                    <label class="boldtext control-label">
                        {l s='DHL national paket id DEV' mod='sxdhl'}</label>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                <input type="text" name="dhl_national_dev"
                       value="{$dhl_national_dev|escape:'htmlall':'UTF-8'}" class="form-control input-sm" readonly>
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
                <div class="text-right">
                    <label class="boldtext control-label">
                        {l s='DHL international paket id DEV' mod='sxdhl'}</label>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                <input type="text" name="dhl_international_dev"
                       value="{$dhl_international_dev|escape:'htmlall':'UTF-8'}" class="form-control input-sm" readonly>
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
                <div class="text-right">
                    <label class="boldtext control-label">
                        {l s='DHL national product paket DEV' mod='sxdhl'}</label>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                <input type="text" name="dhl_product_national_dev"
                       value="{$dhl_product_national_dev|escape:'htmlall':'UTF-8'}" class="form-control input-sm" readonly>
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
                <div class="text-right">
                    <label class="boldtext control-label">
                        {l s='DHL international product paket DEV' mod='sxdhl'}</label>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                <input type="text" name="dhl_product_international_dev"
                       value="{$dhl_product_international_dev|escape:'htmlall':'UTF-8'}" class="form-control input-sm" readonly>
            </div>
        </div>
        <div class="panel-footer">
            <button type="submit" value="1" id="submitDataConsent"
                    name="dhl_dev"
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
