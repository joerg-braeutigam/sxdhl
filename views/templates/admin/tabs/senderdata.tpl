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
    <p>{l s='Enter your sender address here.' mod='sxdhl'}</p>
    <p>{l s='This will be entered in the DHL label as the sender.' mod='sxdhl'}</p>

    <form method="post"
          action="{$moduleAdminLink|escape:'htmlall':'UTF-8'}&page=senderData"
          class="form-horizontal">

        <div class="form-group">
            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
                <div class="text-right">
                    <label class="boldtext control-label">
                        {l s='Name of the Company' mod='sxdhl'}</label>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                <input type="text" name="dhl_sender_company"
                       value="{$dhl_sender_company|escape:'htmlall':'UTF-8'}"
                       class="form-control input-sm">
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
                <div class="text-right">
                    <label class="boldtext control-label">
                        {l s='Street' mod='sxdhl'}</label>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                <input type="text" name="dhl_sender_street"
                       value="{$dhl_sender_street|escape:'htmlall':'UTF-8'}"
                       class="form-control input-sm">
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
                <div class="text-right">
                    <label class="boldtext control-label">
                        {l s='House Number' mod='sxdhl'}</label>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                <input type="text" name="dhl_sender_house"
                       value="{$dhl_sender_house|escape:'htmlall':'UTF-8'}"
                       class="form-control input-sm">
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
                <div class="text-right">
                    <label class="boldtext control-label">
                        {l s='ZIP' mod='sxdhl'}</label>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                <input type="text" name="dhl_sender_zip"
                       value="{$dhl_sender_zip|escape:'htmlall':'UTF-8'}"
                       class="form-control input-sm">
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
                <div class="text-right">
                    <label class="boldtext control-label">
                        {l s='City' mod='sxdhl'}</label>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                <input type="text" name="dhl_sender_city"
                       value="{$dhl_sender_city|escape:'htmlall':'UTF-8'}"
                       class="form-control input-sm">
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
                <div class="text-right">
                    <label class="boldtext control-label">
                        {l s='Country' mod='sxdhl'}</label>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                <input type="text" name="dhl_sender_country"
                       value="{$dhl_sender_country|escape:'htmlall':'UTF-8'}"
                       class="form-control input-sm">
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
                <div class="text-right">
                    <label class="boldtext control-label">
                        {l s='ISO Code of Country' mod='sxdhl'}</label>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                <input type="text" name="dhl_sender_iso"
                       value="{$dhl_sender_iso|escape:'htmlall':'UTF-8'}"
                       class="form-control input-sm">
            </div>
        </div>

        <div class="panel-footer">
            <button type="submit" value="1" id="submitDataConsent"
                    name="dhl_sender"
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
>