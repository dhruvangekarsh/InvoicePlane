<html lang="<?php echo trans('cldr'); ?>">
<head>
    <meta charset="utf-8">
    <title><?php echo trans('invoice'); ?></title>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/default/css/templates.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/default/css/custom-pdf.css">
</head>
<body>
<header class="clearfix">

    <div id="logo">
        <?php echo invoice_logo_pdf(); ?>
    </div>

    <div id="client">
        <div>
            <b><?php echo $invoice->client_name; ?></b>
        </div>
        <?php if ($invoice->client_vat_id) {
            echo '<div>' . trans('vat_id_short') . ': ' . $invoice->client_vat_id . '</div>';
        }
        if ($invoice->client_tax_code) {
            echo '<div>' . trans('tax_code_short') . ': ' . $invoice->client_tax_code . '</div>';
        }
        if ($invoice->client_address_1) {
            echo '<div>' . $invoice->client_address_1 . '</div>';
        }
        if ($invoice->client_address_2) {
            echo '<div>' . $invoice->client_address_2 . '</div>';
        }
        if ($invoice->client_city && $invoice->client_zip) {
            echo '<div>' . $invoice->client_city . ' ' . $invoice->client_zip . '</div>';
        } else {
            if ($invoice->client_city) {
                echo '<div>' . $invoice->client_city . '</div>';
            }
            if ($invoice->client_zip) {
                echo '<div>' . $invoice->client_zip . '</div>';
            }
        }
        if ($invoice->client_state) {
            echo '<div>' . $invoice->client_state . '</div>';
        }
        if ($invoice->client_country) {
            echo '<div>' . get_country_name(trans('cldr'), $invoice->client_country) . '</div>';
        }

        echo '<br/>';

        if ($invoice->client_phone) {
            echo '<div>' . trans('phone_abbr') . ': ' . $invoice->client_phone . '</div>';
        } ?>

    </div>
    <div id="company">
        <div><b><?php echo $invoice->user_name; ?></b></div>
        <?php if ($invoice->user_vat_id) {
            echo '<div>' . trans('vat_id_short') . ': ' . $invoice->user_vat_id . '</div>';
        }
        if ($invoice->user_tax_code) {
            echo '<div>' . trans('tax_code_short') . ': ' . $invoice->user_tax_code . '</div>';
        }
        if ($invoice->user_address_1) {
            echo '<div>' . $invoice->user_address_1 . '</div>';
        }
        if ($invoice->user_address_2) {
            echo '<div>' . $invoice->user_address_2 . '</div>';
        }
        if ($invoice->user_city && $invoice->user_zip) {
            echo '<div>' . $invoice->user_city . ' ' . $invoice->user_zip . '</div>';
        } else {
            if ($invoice->user_city) {
                echo '<div>' . $invoice->user_city . '</div>';
            }
            if ($invoice->user_zip) {
                echo '<div>' . $invoice->user_zip . '</div>';
            }
        }
        if ($invoice->user_state) {
            echo '<div>' . $invoice->user_state . '</div>';
        }
        if ($invoice->user_country) {
            echo '<div>' . get_country_name(trans('cldr'), $invoice->user_country) . '</div>';
        }

        echo '<br/>';

        if ($invoice->user_phone) {
            echo '<div>' . trans('phone_abbr') . ': ' . $invoice->user_phone . '</div>';
        }
        if ($invoice->user_fax) {
            echo '<div>' . trans('fax_abbr') . ': ' . $invoice->user_fax . '</div>';
        }
        ?>
    </div>

</header>

<main>

    <div class="invoice-details clearfix">
        <table>
            <tr>
                <td><?php echo trans('invoice_date') . ':'; ?></td>
                <td><?php echo date_from_mysql($invoice->invoice_date_created, true); ?></td>
            </tr>
            <tr>
                <td><?php echo trans('due_date') . ': '; ?></td>
                <td><?php echo date_from_mysql($invoice->invoice_date_due, true); ?></td>
            </tr>
            <tr>
                <td class="text-green"><?php echo trans('amount_due') . ': '; ?></td>
                <td class="text-green"><?php echo format_currency($invoice->invoice_balance); ?></td>
            </tr>
            <?php if ($payment_method): ?>
                <tr>
                    <td><?php echo trans('payment_method') . ': '; ?></td>
                    <td><?php echo $payment_method->payment_method_name; ?></td>
                </tr>
            <?php endif; ?>
        </table>
    </div>

    <h1 class="invoice-title text-green"><?php echo trans('invoice') . ' ' . $invoice->invoice_number; ?></h1>

    <table class="item-table">
        <thead>
        <tr>
            <th class="item-name"><?php echo trans('item'); ?></th>
            <th class="item-desc"><?php echo trans('description'); ?></th>
            <th class="item-amount text-right"><?php echo trans('qty'); ?></th>
            <th class="item-price text-right"><?php echo trans('price'); ?></th>
            <?php if ($show_discounts) : ?>
                <th class="item-discount text-right"><?php echo trans('discount'); ?></th>
            <?php endif; ?>
            <th class="item-total text-right"><?php echo trans('total'); ?></th>
        </tr>
        </thead>
        <tbody>

        <?php
        foreach ($items as $item) { ?>
            <tr>
                <td><?php echo $item->item_name; ?></td>
                <td><?php echo nl2br($item->item_description); ?></td>
                <td class="text-right">
                    <?php echo format_amount($item->item_quantity); ?>
                </td>
                <td class="text-right">
                    <?php echo format_currency($item->item_price); ?>
                </td>
                <?php if ($show_discounts) : ?>
                    <td class="text-right">
                        <?php echo format_currency($item->item_discount); ?>
                    </td>
                <?php endif; ?>
                <td class="text-right">
                    <?php echo format_currency($item->item_total); ?>
                </td>
            </tr>
        <?php } ?>

        </tbody>
        <tbody class="invoice-sums">

        <tr>
            <td <?php echo($show_discounts ? 'colspan="5"' : 'colspan="4"'); ?> class="text-right">
                <?php echo trans('subtotal'); ?>
            </td>
            <td class="text-right"><?php echo format_currency($invoice->invoice_item_subtotal); ?></td>
        </tr>

        <?php if ($invoice->invoice_item_tax_total > 0) { ?>
            <tr>
                <td <?php echo($show_discounts ? 'colspan="5"' : 'colspan="4"'); ?> class="text-right">
                    <?php echo trans('item_tax'); ?>
                </td>
                <td class="text-right">
                    <?php echo format_currency($invoice->invoice_item_tax_total); ?>
                </td>
            </tr>
        <?php } ?>

        <?php foreach ($invoice_tax_rates as $invoice_tax_rate) : ?>
            <tr>
                <td <?php echo($show_discounts ? 'colspan="5"' : 'colspan="4"'); ?> class="text-right">
                    <?php echo $invoice_tax_rate->invoice_tax_rate_name . ' (' . format_amount($invoice_tax_rate->invoice_tax_rate_percent) . '%)'; ?>
                </td>
                <td class="text-right">
                    <?php echo format_currency($invoice_tax_rate->invoice_tax_rate_amount); ?>
                </td>
            </tr>
        <?php endforeach ?>

        <tr>
            <td <?php echo($show_discounts ? 'colspan="5"' : 'colspan="4"'); ?> class="text-right">
                <b><?php echo trans('total'); ?></b>
            </td>
            <td class="text-right">
                <b><?php echo format_currency($invoice->invoice_total); ?></b>
            </td>
        </tr>
        <tr>
            <td <?php echo($show_discounts ? 'colspan="5"' : 'colspan="4"'); ?> class="text-right">
                <?php echo trans('paid'); ?>
            </td>
            <td class="text-right">
                <?php echo format_currency($invoice->invoice_paid); ?>
            </td>
        </tr>
        <tr>
            <td <?php echo($show_discounts ? 'colspan="5"' : 'colspan="4"'); ?> class="text-right text-green">
                <b><?php echo trans('balance'); ?></b>
            </td>
            <td class="text-right text-green">
                <b><?php echo format_currency($invoice->invoice_balance); ?></b>
            </td>
        </tr>
        </tbody>
    </table>

</main>

<footer>
    <?php if ($invoice->invoice_terms) : ?>
        <div class="notes">
            <b><?php echo trans('terms'); ?></b><br/>
            <?php echo nl2br($invoice->invoice_terms); ?>
        </div>
    <?php endif; ?>
</footer>

</body>
</html>
