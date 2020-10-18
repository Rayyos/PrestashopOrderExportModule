{*
* NOTICE OF LICENSE
*
* This source file is subject to a commercial license from SAS Comptoir du Code
* Use, copy, modification or distribution of this source file without written
* license agreement from the SAS Comptoir du Code is strictly forbidden.
* In order to obtain a license, please contact us: contact@comptoirducode.com
*
* @package   cdc_googletagmanager
* @author    Vincent - Comptoir du Code
* @copyright Copyright(c) 2015-2016 SAS Comptoir du Code
* @license   Commercial license
*}
<style>
    .cdc-info {
        background: #d9edf7;
        color: #1b809e;
        padding: 7px;
        /*border-left: solid 3px #1b809e;*/
        margin-top: 50px;
        font-weight: normal;
    }

    .cdc-warning-box {
        background: #FFF3D7;
        color: #D2A63C;
        padding: 16px;
        font-weight: bold;
        border: solid 2px #fcc94f;
        margin: 30px 0;
        text-align: center;
        font-size: 1.2em;
    }

    .hook_ok {
        color: #00aa00;
        font-weight: bold;
    }
    .hook_nok {
        color: #cc0000;
        font-weight: bold;
    }
    .hook_list {
        font-family: monospace;
        list-style-type: square;
    }
</style>
<div class="bootstrap">


    <div class="panel text-center">
        <img src="{$module_dir|escape:'htmlall':'UTF-8'}/logo.png" >
        <h1>
            Google Tag Manager Enhanced E-commerce
            <br /><small>GTM integration + Enhanced E-commerce + Google Trusted Stores</small>
        </h1>
    </div>

    <div>


        <div class="panel">
            <div>
                <h2>INSTALLATION</h2>
                <p>{l s='In order to work properly, this module needs the installation of custom hooks :' mod='cdc_googletagmanager'}</p>
                <ul class="hook_list">
                    {foreach $hook_list as $hook => $installed}
                        <li>
                            {$hook} : <span class="{if $installed}hook_ok{else}hook_nok{/if}">{if $installed}found{else}not found{/if}</span>
                        </li>
                    {/foreach}
                </ul>
            </div>

            <div style="margin: 20px 0;">
                <a href="{$form_action}&install_hooks" class="btn btn-success btn-lg button"><b>Install missing hooks</b></a>
                <p style="margin: 6px 0;"><small>{l s='Only one click, and we do everything. We check if hooks are in the files, if not, we add them.' mod='cdc_googletagmanager'}</small></p>
            </div>

            {if $multishop}
                <div>
                    <h2>{l s='Multishops' mod='cdc_googletagmanager'}</h2>
                    <p>{l s='Multishop feature is enabled and you have at least 2 shops. The automatic installation may fails if you have many themes. Please refer to the documentation and install the hooks manually.' mod='cdc_googletagmanager'}</p>
                </div>
            {/if}


            <div>
                <h2>Troubleshooting / Manual installation</h2>
                <p>If you have problem installing the hooks, please refer to the documentation (<a href="http://comptoirducode.com/prestashop/modules/cdc_googletagmanager/doc/doc_v4_en.html" target="_blank">en</a> / <a href="http://comptoirducode.com/prestashop/modules/cdc_googletagmanager/doc/doc_v4_fr.html" target="_blank">fr</a>) or contact <a href="https://addons.prestashop.com/contact-community.php?id_product=23806" target="_blank">our support team on Prestashop</a>.</p>
            </div>

        </div>



        <div style="margin-top: 10px;">
            <p>
                <b>Documentation</b> :
                <a href="http://comptoirducode.com/prestashop/modules/cdc_googletagmanager/doc/doc_v4_en.html" target="_blank">English</a> -
                <a href="http://comptoirducode.com/prestashop/modules/cdc_googletagmanager/doc/doc_v4_fr.html" target="_blank">Fran&ccedil;ais</a>
            </p>

        </div>

    </div>
</div>
