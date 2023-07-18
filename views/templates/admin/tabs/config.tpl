{*
*  @author Saxtec <prestashop@saxtec.com>
*  @copyright  2007-2020 Saxtec
*  @license    Paid Licence
*}

<div class="panel col-lg-10 right-panel">
    <h3>
        <i class="fa fa-info-circle"></i> {l s='Default Configuration' mod='sxdhl'} -
        <small>{$module_display|escape:'htmlall':'UTF-8'}</small>
    </h3>
    <h2>{l s='Welcome to your DHL module' mod='sxdhl'}</h2>
    <br>
    <p>{l s='This interface will help you to configurate the DHL module.' mod='sxdhl'}</p>
    <p>{l s='The configuration is necessary to create a DHL label comfortably.' mod='sxdhl'}</p>
    <p>{l s='You need three Steps:' mod='sxdhl'}</p>
    <ol type="1">
        <li>{l s='Activate the DHL Live mode and select whether the label should be displayed immediately when it is created' mod='sxdhl'}</li>
        <li>{l s='Enter your DHL login data' mod='sxdhl'}</li>
        <li>{l s='Enter your correct sender address' mod='sxdhl'}</li>
    </ol>
    <p>{l s='We are also happy to assist you should you have any questions.' mod='sxdhl'}</p>

    <form method="post"
          action="{$moduleAdminLink|escape:'htmlall':'UTF-8'}&page=Config"
          class="form-horizontal">
        <div class="form-group">
            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
                <div class="text-right">
                    <label class="boldtext control-label">
                        {l s='DHL Business Live Mode' mod='sxdhl'}</label>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                    <span class="switch prestashop-switch fixed-width-lg">
                        <input class="yes" type="radio"
                               name="sxdhl_mode_form_switch"
                               id="switch_mode_on"
                               data-toggle="collapse"
                               data-target="#account_creation_message:not(.in)"
                               value="1" {if $dhl_mode == 'production'}checked="checked"{/if}>
                        <label for="switch_mode_on" class="radioCheck">
                            {l s='YES' mod='sxdhl'}
                        </label>

                        <input class="no" type="radio"
                               name="sxdhl_mode_form_switch"
                               id="switch_mode_off"
                               data-toggle="collapse"
                               data-target="#account_creation_message.in"
                               value="0" {if $dhl_mode == 'test'}checked="checked"{/if}>
                        <label for="switch_mode_off" class="radioCheck">
                            {l s='NO' mod='sxdhl'}
                        </label>
                        <a class="slide-button btn"></a>
                    </span>
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
                <div class="text-right">
                    <label class="boldtext control-label">
                        {l s='Auto View DHL Label (Popup)' mod='sxdhl'}</label>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                    <span class="switch prestashop-switch fixed-width-lg">
                        <input class="yes" type="radio"
                               name="sxdhl_autoview_form_switch"
                               id="autoview_on"
                               data-toggle="collapse"
                               data-target="#account_creation_message:not(.in)"
                               value="1" {if $dhl_autoview == '1'}checked="checked"{/if}>
                        <label for="autoview_on" class="radioCheck">
                            {l s='YES' mod='sxdhl'}
                        </label>

                        <input class="no" type="radio"
                               name="sxdhl_autoview_form_switch"
                               id="autoview_off"
                               data-toggle="collapse"
                               data-target="#account_creation_message.in"
                               value="0" {if $dhl_autoview == '0'}checked="checked"{/if}>
                        <label for="autoview_off" class="radioCheck">
                            {l s='NO' mod='sxdhl'}
                        </label>
                        <a class="slide-button btn"></a>
                    </span>
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
                <div class="text-right">
                    <label class="boldtext control-label">
                        {l s='Enable Paket Tracking' mod='sxdhl'}</label>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                    <span class="switch prestashop-switch fixed-width-lg">
                        <input class="yes" type="radio"
                               name="sxdhl_tracking_form_switch"
                               id="tracking_on"
                               data-toggle="collapse"
                               data-target="#account_creation_message:not(.in)"
                               value="1" {if $dhl_tracking == '1'}checked="checked"{/if}>
                        <label for="tracking_on" class="radioCheck">
                            {l s='YES' mod='sxdhl'}
                        </label>

                        <input class="no" type="radio"
                               name="sxdhl_tracking_form_switch"
                               id="tracking_off"
                               data-toggle="collapse"
                               data-target="#account_creation_message.in"
                               value="0" {if $dhl_tracking == '0'}checked="checked"{/if}>
                        <label for="tracking_off" class="radioCheck">
                            {l s='NO' mod='sxdhl'}
                        </label>
                        <a class="slide-button btn"></a>
                    </span>
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
                <div class="text-right">
                    <label class="boldtext control-label">
                        {l s='DHL Tracking Link' mod='sxdhl'}</label>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                    <input type="text" name="dhl_tracking_link" value="{$dhl_tracking_link|escape:'htmlall':'UTF-8'}" class="">
            </div>
        </div>
        <div class="panel-footer">
            <button type="submit" value="1" id="submitDataConsent"
                    name="dhl_default_data"
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
