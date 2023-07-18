{*
*  @author Saxtec <prestashop@saxtec.com>
*  @copyright  2007-2020 Saxtec
*  @license    Paid Licence
*}

<div id="sxdhl" class="mt-2">
    <ul class="nav nav nav-tabs d-print-none" role="tablist">
        <li class="nav-item">
            <a href="#" class="nav-link active show"><i class="icon-cog"></i>
            {l s='DHL Business Integration' mod='sxdhl'}</a>
        </li>
    </ul>
    <div class="tab-content">
        <table class="table">
            <thead>
            <tr>
                <th>{l s='Shipping Number' d='Modules.Faviconnotificationbo.Admin'}</th>
                <th>{l s='Download' d='Modules.Faviconnotificationbo.Admin'}</th>
                <th>{l s='Create Date' d='Modules.Faviconnotificationbo.Admin'}</th>
                <th>{l s='Last Modified' d='Modules.Faviconnotificationbo.Admin'}</th>
                <th>{l s='State' d='Modules.Faviconnotificationbo.Admin'}</th>
                <th>{l s='Link to DHL' d='Modules.Faviconnotificationbo.Admin'}</th>
            </tr>
            </thead>
            <tbody>
            {assign var='z' value=0}
            {foreach $label_html as $label}
                <tr>
                    <td>{$label.shipping_number|escape:'htmlall':'UTF-8'}</td>
                    <td><a href="{$label.download_path|escape:'htmlall':'UTF-8'}{$label.label_file|escape:'htmlall':'UTF-8'}" target="_new"> {$label.label_file|escape:'htmlall':'UTF-8'}</a></td>
                    <td>{$label.label_create|escape:'htmlall':'UTF-8'}</td>
                    <td>{$label.label_update|escape:'htmlall':'UTF-8'}</td>
                    <td>{$label.state|escape:'htmlall':'UTF-8'}</td>
                    <td><a href="https://www.dhl.de/de/privatkunden.html?piececode={$label.shipping_number|escape:'htmlall':'UTF-8'}" target="_new">Track this</a></td>
                </tr>
                {foreach $label_detail.$z as $detail}
                <tr>
                    <td></td>
                    <td><small>{$detail.event_timestanp|escape:'htmlall':'UTF-8'}</small></td>
                    <td><small>{$detail.event_text|escape:'htmlall':'UTF-8'}</small></td>
                    <td><small>{$detail.event_location|escape:'htmlall':'UTF-8'}</small></td>
                    <td><small>{$detail.ice|escape:'htmlall':'UTF-8'} / {$detail.ric|escape:'htmlall':'UTF-8'}</small></td>
                    <td></td>
                </tr>
                {/foreach}
                {assign var='z' value=$z + 1}
            {/foreach}
            </tbody>
        </table>
    </div>
    <hr>
    <div class="input-group">
        <form action="{$create_url|escape:'htmlall':'UTF-8'}#sxdhl" method="post">
            <input type="hidden" name="create_label">
            <table class="table">
                <tr>
                    <td>
                        <select name="weight" class="form-control form-control fixed-width-xs pull-left">
                            {for $kg=1 to 30}
                                <option value="{$kg|escape:'htmlall':'UTF-8'}">{$kg|escape:'htmlall':'UTF-8'}</option>
                            {/for}
                        </select>
                    </td>
                    <td>{l s='Weight: (kg)' mod='sxdhl'}</td>
                    <td>|</td>
                    <td><input type="submit" class="btn btn-primary" value="{l s='Create New DHL Label' mod='sxdhl'}"></td>
                </tr>
            </table>
        </form>
    </div>
</div>
