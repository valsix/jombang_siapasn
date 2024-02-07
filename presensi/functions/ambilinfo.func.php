<?
function ambilpresensiinfo($in1, $in2, $ininfo2, $out1, $out2, $outinfo2, $ask1, $ask2, $askinfo2)
{
	$valreturn= '<div class="rTable">
		<div class="rTableRow">
			<div class="rTableHead">Status</div>
			<div class="rTableHead">Jam</div>
			<div class="rTableHead">Ket</div>
		</div>
		<div class="rTableRow">
			<div class="rTableCell">In</div>
			<div class="rTableCell">'.$in1.'</div>
			<div class="rTableCell">
				<div class="infotooltip">'.$in2.'
					<span class="infotooltiptext">'.$ininfo2.'</span>
				</div>
			</div>
		</div>
		<div class="rTableRow">
			<div class="rTableCell">Out</div>
			<div class="rTableCell">'.$out1.'</div>
			<div class="rTableCell">
				<div class="infotooltip">'.$out2.'
					<span class="infotooltiptext">'.$outinfo2.'</span>
				</div>
			</div>
		</div>
		<div class="rTableRow">
			<div class="rTableCell">A/S/K</div>
			<div class="rTableCell">'.$ask1.'</div>
			<div class="rTableCell">
				<div class="infotooltip">'.$ask2.'
					<span class="infotooltiptext">'.$askinfo2.'</span>
				</div>
			</div>
		</div>
	</div>';

	return $valreturn;
}
?>