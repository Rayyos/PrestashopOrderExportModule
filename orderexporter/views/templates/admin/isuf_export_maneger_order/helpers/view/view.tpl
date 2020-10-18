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


<div class="panel">
    <div class="panel-heading">Order Export Log</div>
    <table class="table table-responsive">
        <tbody>
            <tr>
                <th>id log</th>
                <td>{$orderLog->id_orderexporter}</td>
            </tr>
            <tr>
                <th>order</th>
                <td>{$orderLog->id_order}</td>
            </tr>
            <tr>
                <th>Customer</th>
                <td>{$orderLog->id_customer}</td>
            </tr>
            <tr>
                <th>sent</th>
                <td>{$orderLog->sent}</td>
            </tr>      
            <tr>
                <th>date_add</th>
                <td>{$orderLog->date_add}</td>
            </tr>
          
        </tbody>
    </table>
</div>



<div class="panel">
    <div class="panel-heading">Datalayer raw</div>
    {if !empty($orderLog->data)}
        <pre>{$orderLog->data nofilter}</pre>
    {else}
        <p>No datalayer saved</p>
    {/if}
</div>

<div class="panel">
    <div class="panel-heading">Datalayer JS formatted</div>
    <pre id="data_formatted">
	loading ...
    </pre>
</div>



<script data-keepinline="true">
    var data_preview = [];
    data_preview.push({$orderLog->data nofilter});
      console.log(data_preview);
    $("#data_formatted").text(JSON.stringify(data_preview, null, 4));
</script>

