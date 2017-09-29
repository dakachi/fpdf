<?php
if(empty($_POST['tax-due']) || empty($_POST['mot'])) {
?>
<!DOCTYPE html>
<html>
<head>
    <title>Print</title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
    $( function() {
    $( ".datepicker" ).datepicker();
    } );
    </script>
    <style type="text/css">
    form {
        width: 600px;
        margin: 0 auto;
        padding-top: 100px;
    }
    label {
        width: 100%;
        margin-top: 20px;
        display: block;
        font-weight: bolder;
        font-size: 16px;
    }
    input {
        width: 100%;
        padding: 10px 5px;
    }
    </style>
</head>
<body>
    <form method="post">
        <h1>Input mot & tax due</h1>
        <label>Tax due</label>
        <input name="tax-due" class="datepicker" value="<?php echo isset($_POST['tax-due']) ? $_POST['tax-due'] : ''; ?>" />
        <label>MOT</label>
        <input name="mot" class="datepicker" value="<?php echo isset($_POST['mot']) ? $_POST['mot'] : ''; ?>" />
        <p>
            <input type="submit" value="Submit" style="width: 100px" />
        </p>
    </form>
</body>
</html>

<?php
    exit;
}

$tax_due = $_POST['tax-due'];
$mot = $_POST['mot'];

require 'fpdf.php';

$order = wc_get_order( $_GET['order_id'] );

$billing = $order->get_address('billing');

$first_name = $billing['first_name'];
$last_name = $billing['last_name'];
$address = $billing['address_1'];
$city = $billing['city'];
$post_code = $billing['postcode'];

$product_id = $_GET['product_id'];
$thumbnail_id = get_post_thumbnail_id( $product_id );
$url = get_attached_file($thumbnail_id, 'full');


$items = $order->get_items();
$item = $items[$_GET['item_id']];

$verhicle_make = $item['Vehicle Make'];
$tax_due = date('d - M - Y', strtotime($tax_due));
$mot = date('d - M - Y', strtotime($mot));

class PDF extends FPDF
{
// Page header
    public function Header()
    {
        // Logo

        // Arial bold 15
        $this->SetFont('Arial', 'B', 15);

        // Title
        // Line break
        $this->Ln(15);
    }

// Page footer
    public function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times', '', 12);

$pdf->Image($url, 120, 15, 80);
$pdf->SetFont('Arial', '', 18);
$pdf->Text( 138, 47, $tax_due);

$pdf->SetFont('Arial', '', 22);
$pdf->Text( 140, 64, $verhicle_make);

$pdf->SetFont('Arial', '', 10);
$pdf->Text( 147, 82, $mot);

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 6, 'Mr. '.$first_name.' '. $last_name . ',', 0, 1);
$pdf->Cell(0, 6, $address, 0, 1);
$pdf->Cell(0, 6, $city, 0, 1);
$pdf->Cell(0, 6, $post_code, 0, 1);

$pdf->ln(5);

$pdf->Cell(0, 10, 'Simply peel off the sticker and place inside your vehicle. ', 0, 1);
$pdf->Cell(0, 10, 'Thank you for using Tax Disk Remind-Us.', 0, 1);

$pdf->Output();


exit;
