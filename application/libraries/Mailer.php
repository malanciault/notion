<?php
class Mailer {
    private $ci;

	function __construct()
	{
		$this->ci =& get_instance();
	}
	//=============================================================
	function Tpl_Certificate($name, $link) {

		$link_certificate = $link['certificate'];
		$link_share = $link['share'];

		$tpl = 
	   "<div style='width: 600px; margin: auto; font-size:14px'>
			<div style='text-align: center'>
				<img src='https://planetair.ca/wp-content/uploads/2019/08/logo-planetair-200-1.png' alt='Planetair' />
			</div>
			<p>
				Bonjour $name,
				<br><br>
				Merci de vous joindre à nous dans la lutte aux changements climatiques!
				<br><br>
				Si ce n’est déjà fait, vous pouvez cliquer sur les boutons ci-dessous pour télécharger votre certificat de compensation carbone et le reçu de la transaction.
				<div style='text-align: center;'>
					<div style=\"padding:15px;\">
						<!--[if mso]>
							<v:roundrect xmlns:v=\"urn:schemas-microsoft-com:vml\" xmlns:w=\"urn:schemas-microsoft-com:office:word\" href=\"$link_certificate\" style=\"height:40px;v-text-anchor:middle;width:160px;\" arcsize=\"63%\" strokecolor=\"#FF8800\" fillcolor=\"#FF8800\">
								<w:anchorlock/>
								<center style=\"color:#ffffff;font-family:sans-serif;font-size:13px;font-weight:bold;\">
									Certificat / Reçu
									
								</center>
							</v:roundrect>
						<![endif]-->
						<a href=\"$link_certificate\"style=\"background-color:#FF8800;border:1px solid #FF8800;border-radius:25px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:13px;font-weight:bold;line-height:40px;text-align:center;text-decoration:none;width:160px;-webkit-text-size-adjust:none;mso-hide:all;\">
							Certificat / Reçu 
						</a>
					</div>
				</div>
			</p>
			<p>
				Paypal vous transmettra également un reçu de la transaction.
			</p>
			<p>
				Nous vous invitons à partager votre action par le biais des réseaux sociaux en cliquant sur les boutons ci-dessous:
			</p>
			<div style=\"text-align:center;padding:15px;\">
				<a href=\"https://www.facebook.com/sharer/sharer.php?u=$link_share\" target=\"_blank\" style=\"margin:0 10px;\"><img src=\"https://planetair.ca/wp-content/uploads/2019/11/facebook-icon-email-2.png\" style=\"width:50px;height:50px;\"></a>
				<a href=\"http://www.linkedin.com/shareArticle?mini=true&amp;url=$link_share\" target=\"_blank\" style=\"margin:0 10px;\"><img src=\"https://planetair.ca/wp-content/uploads/2019/11/linkedin-icon-email.png\" style=\"width:50px;height:50px;\"></a>
				<a href=\"https://twitter.com/share?url=$link_share&amp;&amp;hashtags=planetair\" target=\"_blank\" style=\"margin:0 10px;\"><img src=\"https://planetair.ca/wp-content/uploads/2019/11/twitter-icon-email.png\" style=\"width:50px;height:50px;\"></a>
			</div>
			<div style='font-size: 11px; border-top: 1px solid silver;margin-top:10px;'>
				<br>
				Planetair est une initiative du Centre international UNISFÉRA, une organisation sans but lucratif fondée au Canada en 2002.
			</div>
		</div>";
		
		return $tpl;		
	}

	function Tpl_Certificate_en($name, $link) {
		
		$link_certificate = $link['certificate'];
		$link_share = $link['share'];
		
		$tpl = 
	   "<div style='width: 600px; margin: auto; font-size:14px'>
			<div style='text-align: center'>
				<img src='https://planetair.ca/wp-content/uploads/2019/08/logo-planetair-200-1.png' alt='Planetair' />
			</div>
			<p>
				Hello $name,
				<br><br>
				Thank you for joining us in the fight against climate change!
				<br><br>
				If you have not done so already, you can click on the buttons below to download your carbon offset certificate and the transaction receipt.
				<div style='text-align: center;'>
					<div style=\"padding:15px;\">
						<!--[if mso]>
							<v:roundrect xmlns:v=\"urn:schemas-microsoft-com:vml\" xmlns:w=\"urn:schemas-microsoft-com:office:word\" href=\"$link_certificate\" style=\"height:40px;v-text-anchor:middle;width:160px;\" arcsize=\"63%\" strokecolor=\"#FF8800\" fillcolor=\"#FF8800\">
								<w:anchorlock/>
								<center style=\"color:#ffffff;font-family:sans-serif;font-size:13px;font-weight:bold;\">
									Certificate / Receipt

								</center>
							</v:roundrect>
						<![endif]-->
						<a href=\"$link_certificate\"style=\"background-color:#FF8800;border:1px solid #FF8800;border-radius:25px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:13px;font-weight:bold;line-height:40px;text-align:center;text-decoration:none;width:160px;-webkit-text-size-adjust:none;mso-hide:all;\">
							Certificate / Receipt
						</a>
					</div>
				</div>
			</p>
			<p>
				Paypal will also send you a receipt of the transaction.
			</p>
			<p>
				We invite you to share your action through social networks by clicking on the buttons below
			</p>
			<div style=\"text-align:center;padding:15px;\">
				<a href=\"https://www.facebook.com/sharer/sharer.php?u=$link_share\" target=\"_blank\" style=\"margin:0 10px;\"><img src=\"https://planetair.ca/wp-content/uploads/2019/11/facebook-icon-email-2.png\" style=\"width:50px;height:50px;\"></a>
				<a href=\"http://www.linkedin.com/shareArticle?mini=true&amp;url=$link_share\" target=\"_blank\" style=\"margin:0 10px;\"><img src=\"https://planetair.ca/wp-content/uploads/2019/11/linkedin-icon-email.png\" style=\"width:50px;height:50px;\"></a>
				<a href=\"https://twitter.com/share?url=$link_share&amp;&amp;hashtags=planetair\" target=\"_blank\" style=\"margin:0 10px;\"><img src=\"https://planetair.ca/wp-content/uploads/2019/11/twitter-icon-email.png\" style=\"width:50px;height:50px;\"></a>
			</div>
			<div style='font-size: 11px; border-top: 1px solid silver;margin-top:10px;'>
				<br>
				Planetair is an initiative of the UNISFERA International Centre, a Canadian not-for-profit organization, founded in 2002
			</div>
		</div>";
		
		return $tpl;		
	}



}