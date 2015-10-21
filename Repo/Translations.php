<?php
class Translation {

	public static function getTranslations($lang)
	{
		$languages = array('en' => array(), 'es' => array());
		$languages['en'] = array(
					'fill_form' => 'Fill information using Facebook',
					'name' => 'Name',
					'child_name' => 'Name your child',
					'dob' => 'Date of birth',
					'branch_office' => 'Branch Office',
					'other_branch' => 'Testify in area - specify',
					'required_hours' => 'Required Hours',
					'from' => 'From',
					'to' => 'To',
					'email' => 'Email',
					'phone' => 'Phone',
					'what_services' => 'What services interest you',
					'web_cameras' => 'Web Cameras',
					'stimulation' => 'Early Stimulation',
					'english' => 'English',
					'kindergarten' => 'Kindergarten',
					'nursery_express' => 'Nursery Express',
					'infants' => 'Infants',
					'maternal' => 'Maternal',
					'other' => 'other',
					'how_did_you_find' => 'How did he find out about us',
					'recommendation' => 'Recommendation',
					'google' => 'Google',
					'bing' => 'Bing',
					'youtube' => 'Youtube',
					'facebook' => 'Facebook',
					'external_ad' => 'External advertising',
					'other_specify' => 'Other - specify',					
					'download_brochure' => 'Download Brochure',										
			);

			$languages['es'] = array(
					'fill_form' => 'Completa tus datos utilizando Facebook',
					'name' => 'Nombre',
					'child_name' => 'Nombre de su hijo(a)',
					'dob' => 'Fecha de Nacimiento',
					'branch_office' => 'Sucursal',
					'other_branch' => 'Requiero en otra zona. Especifique por favor',
					'required_hours' => 'Horario requerido',
					'from' => 'De las',
					'to' => 'a las',
					'email' => 'Correo electrÛnico',
					'phone' => 'Teléfono de contacto',
					'what_services' => 'Que servicios le interesan?',
					'web_cameras' => 'Cámaras Web',
					'stimulation' => 'Estimulación Temprana',
					'english' => 'Inglés',
					'kindergarten' => 'Guarderia',
					'nursery_express' => 'Guarderia Express',
					'infants' => 'Lactantes',
					'maternal' => 'Maternales',
					'other' => 'Otro',
					'how_did_you_find' => '¿Cómo se enteró de nosotros?',
					'recommendation' => 'Recomendación',
					'google' => 'Google',
					'bing' => 'Bing',
					'youtube' => 'Youtube',
					'facebook' => 'Facebook',
					'external_ad' => 'Publicidad exterior',					
					'other_specify' => 'Otro',					
					'download_brochure' => 'Descargar Folleto',										
			);

		return $languages[$lang];
	}

}
