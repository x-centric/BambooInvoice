<?php
/**
 * This file is essentially a stripped down version of /views/invoices/view.php
 * Any changes you make to that formatting, you may consider adding to this.
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $page_title;?></title>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<style type="text/css">
/**
 * Invoice view styles notes
 *
 * This file NEEDS a locally located stylesheet to generate the appropriate formatting for 
 * transformation into a PDF.  If you alter this file (and you are encouraged to do so) just
 * keep in mind that all of your formatting must be located here.  You might also find that
 * there is limited or no support for a specific CSS style you want (ie: floating) and you'll
 * need to work around with old-school tables.  Sorry for that... ;)  
 */

body {
	margin: 0.5in;
	font-family: Helvetica;
	font-size: 8pt;
}
h1, h2, h3, h4, h5, h6, li, blockquote, p, th, td {
	font-family: Helvetica;
}
h1, h2, h3, h4 {
	color: #5E88B6;
	font-weight: bold;
}
h4, h5, h6 {
	color: #5E88B6;
}
h2 {
	margin: 0 auto auto auto;
	font-size: x-large;
}
h2 span {
	text-transform: uppercase;
}
li, blockquote, p, th, td {
	font-size: 80%;
}
ul {
	list-style: url(img/bullet.gif) none;
}
table {
	width: 100%;
}
td p {
	font-family: Helvetica;
	font-size: 8pt;
	font-weight: bold;
	margin: 0;
}
th {
	color: #FFF;
	text-align: left;
	background-color:#000000;
	font-size: 8pt;
}
.bamboo_invoice_bam {
	color: #5E88B6;
	font-weight: bold;
	text-transform: capitalize;
}
.bamboo_invoice_inv {
	font-weight: bold;
	font-variant: small-caps;
	color: #333;
}
#footer {
	border-top: 1px solid #CCC;
	text-align: left;
	font-size: 7pt;
	color: #999999;
}
#footer a {
	color: #999999;
	text-decoration: none;
}
table.stripe {
	border-collapse: collapse;
	page-break-after: auto;
	font-size: 8pt;
}
table.stripe td {
	font-weight: normal;
	font-size: 8pt;
}
</style>
</head>
<body>

	<table>
		<tr>
			<td width="65%">
				<?php if (isset($company_logo)) {echo $company_logo.'<br /><br />';}?>

				<p>
					<?php echo $companyInfo->address1;?><br />
					<?php echo $companyInfo->postal_code;?> <?php echo $companyInfo->city;?><br />
					<?php echo $companyInfo->country;?><br />
					<?php echo auto_link(prep_url($companyInfo->website));?>
				</p>
			</td>
			<td align="right">
				<h2><span><?php echo (!$credit ? $this->lang->line('invoice_invoice'):$this->lang->line('invoice_credit'));?></span></h2><br /><br />
				<p>
					<strong>
						<?php echo (!$credit ? $this->lang->line('invoice_number'):$this->lang->line('invoice_credit_number')) .': '. $row->invoice_number;?>
						<br>DATUM: <?php echo $date_invoice_issued;?>
					</strong>
				</p>
			<br /><br /><br />
			<table width="100%" border=0><tr><td align="left">
			<p ><br /><br /><br />
			<?php echo $row->name;?><br />
			<?php if ($row->address1 != '') {echo $row->address1;}?><br />
			<?php if ($row->postal_code != '') {echo ' ' . $row->postal_code;}?> <?php if ($row->city != '') {echo $row->city;}?><br /><br /><br /><br />
			<?php if ($row->tax_code != '') {echo $this->lang->line('settings_tax_code').': '.$row->tax_code;}?>
			</p>
			
			</td></tr></table>

			</td>
		</tr>
	</table>

	<table class="invoice_items stripe">
		<tr>
			<th width="75px" align="center">AANTAL</th>
			<th>OMSCHRIJVING</th>
			<th width="75px" align="center">EH PRIJS</th>
			<th width="75px" align="center">TOTAAL</th>
		</tr>
		<?php foreach ($items->result() as $item):?>
		<tr valign="top">
			<td align="center"><?php echo str_replace('.00', '', $item->quantity);?></td>
			<td><?php echo nl2br(str_replace(array('\n', '\r'), "\n", $item->work_description));?></td>
			<td align="center"><?php echo $this->settings_model->get_setting('currency_symbol') . str_replace('.', $this->config->item('currency_decimal'), $item->amount);?> <?php if ($item->taxable == 0){echo '(' . $this->lang->line('invoice_not_taxable') . ')';}?></td>
			<td align="center"><?php echo $this->settings_model->get_setting('currency_symbol') . number_format($item->quantity * $item->amount, 2, $this->config->item('currency_decimal'), '');?></td>
		</tr>
		<?php endforeach;?>
	</table>
	<br /><br /><br />
	<p align=right>
		<?php echo $total_no_tax;?>
		<?php echo $tax_info;?>
		<?php echo $total_with_tax;?>
		<?php echo $total_paid;?>
		<?php echo $total_outstanding;?>
	</p>

	<p style="font-size: 8pt;">
		<strong><?php echo $this->lang->line('invoice_payment_term');?>: <?php echo $this->settings_model->get_setting('days_payment_due');?> <?php echo $this->lang->line('date_days');?></strong> 
		(Verval datum: <?php echo $date_invoice_due;?>)
	<br />
	</p>

	<p><?php echo auto_typography($row->invoice_note);?></p>

	<?php if ($this->config->item('show_client_notes') === TRUE):?>
	<p>
		<?php echo auto_typography($client_note)?>
	</p>
	<?php endif;?>

	<div id="footer">
		<?php if ($this->settings_model->get_setting('display_branding') == 'y'):?>
			<p>
				<?php echo $this->lang->line('invoice_generated_by');?> 
				<?php echo $this->lang->line('bambooinvoice_logo');?><br />
				<a href="http://www.bambooinvoice.be/">http://www.bambooinvoice.be</a>
			</p>
		<?php endif;?>
	</div>

</body>
</html>