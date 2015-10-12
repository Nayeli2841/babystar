<?php
class MessageRepo
{
	public function sendMessage($data)
	{
		$to = $data['email'];
		$subject = $data['subject'];
		$message = nl2br($data['message']);


		$message = "
		<html>
		<head>
		<title>New Message</title>
		</head>
		<body>
		".$message."
		</table>
		</body>
		</html>
		";

		// Always set content-type when sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

		// More headers
		$headers .= 'From: <jasonbourne501@gmail.com>' . "\r\n";

		mail($to, $subject,$message,$headers);
		return array('status' => 'success');
	}

	
}