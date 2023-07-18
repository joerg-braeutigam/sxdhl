{*
*  @author Saxtec <prestashop@saxtec.com>
*  @copyright  2007-2020 Saxtec
*  @license    Paid Licence
*}

<div id="modulecontent" class="clearfix">
    <div id="sxdhl-menu">
        <div class="col-lg-2">
            <div class="list-group" v-on:click.prevent>
                <a href="#" class="list-group-item"
                   v-bind:class="{ 'active': isActive('Config') }"
                   v-on:click="makeActive('Config')">
                    <i class="fa fa-gavel"></i>
                    {l s='Default Configuration' mod='sxdhl'}
                </a>
                {if $dhl_mode == 'test'}
                    <a href="#" class="list-group-item"
                       v-bind:class="{ 'active': isActive('dataConfigDev') }"
                       v-on:click="makeActive('dataConfigDev')">
                        <i class="fa fa-user-secret"></i>
                        {l s='Test Data Access' mod='sxdhl'}
                    </a>
                {else}
                    <a href="#" class="list-group-item"
                       v-bind:class="{ 'active': isActive('dataConfigLive') }"
                       v-on:click="makeActive('dataConfigLive')">
                        <i class="fa fa-user-secret"></i>
                        {l s='Live Data Access' mod='sxdhl'}
                    </a>
                {/if}
                <a href="#" class="list-group-item"
                   v-bind:class="{ 'active': isActive('senderData') }"
                   v-on:click="makeActive('senderData')">
                    <i class="fa fa-check-square"></i>
                    {l s='DHL Sender Data' mod='sxdhl'}
                </a>
                {if $dhl_tracking == '1'}
                <a href="#" class="list-group-item"
                   v-bind:class="{ 'active': isActive('tracking') }"
                   v-on:click="makeActive('tracking')">
                    <i class="fa fa-question-circle"></i>
                    {l s='DHL Tracking Data' mod='sxdhl'}
                </a>
                {/if}
                    <a href="#" class="list-group-item"
                       v-bind:class="{ 'active': isActive('faq') }"
                       v-on:click="makeActive('faq')">
                        <i class="fa fa-question-circle"></i>
                        {l s='Help' mod='sxdhl'}
                    </a>
            </div>
            <div class="list-group" v-on:click.prevent>
                <a class="list-group-item" style="text-align:left">
                    <i class="icon-info"></i>
                    {l s='Module' mod='sxdhl'}: {$module_name|escape:'htmlall':'UTF-8'}
                    <br><i class="icon-info"></i>
                    {l s='Version' mod='sxdhl'}:
                    {$module_version|escape:'htmlall':'UTF-8'}
                    <br><i class="icon-info"></i>
                    PrestaShop: {$ps_version|escape:'htmlall':'UTF-8'}
                </a>
            </div>
        </div>
    </div>

    {* list your admin tpl *}
    <div id="Config" class="sxdhl_menu addons-hide">
        {include file="./tabs/config.tpl"}
    </div>

    <div id="dataConfigDev" class="sxdhl_menu addons-hide">
        {include file="./tabs/dataconfigdev.tpl"}
    </div>

    <div id="dataConfigLive" class="sxdhl_menu addons-hide">
        {include file="./tabs/dataconfiglive.tpl"}
    </div>

    <div id="senderData" class="sxdhl_menu addons-hide">
        {include file="./tabs/senderdata.tpl"}
    </div>

    <div id="faq" class="sxdhl_menu addons-hide">
        {include file="./tabs/help.tpl"}
    </div>

    <div id="tracking" class="sxdhl_menu addons-hide">
        {include file="./tabs/tracking.tpl"}
    </div>
</div>

{* Use this if you want to send php var to your js *}
<script type="text/javascript">
    var base_url = "{$ps_base_dir|escape:'htmlall':'UTF-8'}";
    var isPs17 = "{$isPs17|escape:'htmlall':'UTF-8'}";
    var moduleName = "{$module_name|escape:'htmlall':'UTF-8'}";
    var currentPage = "{$currentPage|escape:'htmlall':'UTF-8'}";
    var moduleAdminLink = "{$moduleAdminLink|escape:'htmlall':'UTF-8'}";
    var ps_version = "{$isPs17|escape:'htmlall':'UTF-8'}";
    var customer_link = "{$customer_link|escape:'htmlall':'UTF-8'}";
</script>
