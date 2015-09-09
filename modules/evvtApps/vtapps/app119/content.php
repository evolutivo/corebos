</br></br>
<table cellpadding="1" cellspacing="1" style="border-collapse: collapse;width:1250px;">
	<tbody>
		<tr>
			<td>
				<p>
					<span style="font-size:18px;"><span style="font-family: 'Times New Roman';">Teknema</span></span></p>
				<p>
					<span style="font-size:18px;">Soluzioni, Tecnologie e Multimedialità</span></p>
			</td>
			<td style="text-align: center;border-width:1px; ">
				<table align="right" border="1">
					<tbody>
						<tr>
							<td style="text-align: center;" width="300">
								<span style="font-size:18px;"><strong style="text-align: center; font-size: medium; ">DOCUMENTO DI TRASPORTO</strong><br style="text-align: center; font-size: medium; " />
								<span style="text-align: center;">(D.d.t.) - D.P.R. 472 del 14-08-1996</span><br style="text-align: center; font-size: medium; " />
								<br />
								<span style="text-align: center;">N. &nbsp;<?php echo getFieldValue('nrdoc','Adocmaster'); ?></span><span style="text-align: center;">&nbsp;&nbsp;&nbsp;del&nbsp;&nbsp;&nbsp;<?php echo getFieldValue('docdate_from','Adocmaster'); ?></span></span></td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<p>
					 </p>
				<p>
					<span style="font-size:18px;"><strong style="font-size: large; ">Teknema s.r.l.</strong></span></p>
			</td>
			<td>
				 </td>
		</tr>
		<tr>
			<td>
				<p>
					 </p>
				<p>
					 </p>
				<p>
					<span style="font-size:18px;"><em style="font-size: medium; ">Sede Operativa:</em>&nbsp;Via Petrarca, 2 - 20058 Villasanta MB&nbsp;<br style="font-size: medium; " />
					Tel. 039 23411 - Fax 039 2341401&nbsp;<br style="font-size: medium; " />
					<em style="font-size: medium; ">Sede Legale:</em>Corso Milano 23 - 20052 Monza MB</span></p>
			</td>
			<td style="text-align: right;">
				<p>
					 </p>
				<p>
					 </p>
				<p>
					<span style="font-size:18px;">Capitale sociale € 99.000<br style="font-size: medium; " />
					Partita IVA - Codice Fiscale - Registro Imprese 02611890969<br style="font-size: medium; " />
					Registro Nazionale Pile: iscr. nr. IT09060P00000808 - R.E.A. 1507542<br />
					</span></p>
			</td>
		</tr>
	</tbody>
</table>
<p>
<?php echo getPcDetails(); ?></p>
</br></br>
<div  style="height:501px;width:1px"> &nbsp;</div>
<table border="1" cellpadding="1" cellspacing="1" style="width:1250px;">
	<tbody>
		<tr>
			<td height="50" style="vertical-align:top;padding-left:10px;">
				<div>
					<span style="font-size: x-small; ">INIZIO DEL TRASPORTO O CONSEGNA</span></div>
			</td>
			<td style="vertical-align:top;padding-left:10px;">
				<span style="font-size: x-small; ">ASPETTO ESTERIORE DEI BENI<br />
				&nbsp;</span></td>
			<td colspan="2" style="vertical-align:top;padding-left:10px;">
				<div>
					<span style="font-size: x-small; ">NUMERO COLLI</span></div>
			</td>
		</tr>
		<tr>
			<td height="100" style="vertical-align:top;padding-left:10px;">
				<div>
					<span style="font-size: x-small; ">VETTORE</span></div>
				<div>
					 </div>
				<p>
<?php echo distributor_name(); ?>				</p>
				<p>
					<span style="font-family:arial,helvetica,sans-serif;">P.I.&nbsp;</span><?php echo pi_vettore(); ?></p>
				<p>
					<span style="font-family:arial,helvetica,sans-serif;">Iscr.albo autotrasp.&nbsp;</span><?php echo reg_auto(); ?></p>
			</td>
			<td style="vertical-align:top;padding-left:10px;">
				<div>
					<span style="font-size: x-small; ">Sede legale del vettore</span></div>
				<div>
					 </div>
				<div>
<?php echo sede1(); ?>				</div>
				<div>
<?php echo sede2(); ?>				</div>
				<div>
					 </div>
			</td>
			<td style="vertical-align:top;padding-left:10px;">
				<div>
					<span style="font-size: x-small; ">data e ora del ritiro</span></div>
				<div>
					<span style="font-size: x-small; ">&nbsp;</span></div>
			</td>
			<td style="vertical-align:top;padding-left:10px;">
				<span style="font-size: x-small; ">firma &nbsp; &nbsp; &nbsp; &nbsp;</span></td>
		</tr>
		<tr>
			<td style="vertical-align:top;padding-left:10px;">
				<span style="font-size: x-small; ">FIRMA CONDUCENTE</span></td>
			<td colspan="3" style="vertical-align:top;padding-left:10px;">
				<span style="font-size: x-small; ">FIRMA DESTINATARIO</span>
				<p>
					 </p>
			</td>
		</tr>
	</tbody>
</table>