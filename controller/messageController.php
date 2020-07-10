<?php 

require_once __DIR__ . '/../model/message.class.php';
require_once __DIR__ . '/../model/chatservice.class.php';


class MessageController
{
	public static function Like()
	{
		$Message = isset( $_GET['id_message'] ) ? ChatService::GetMessageById( $_GET['id_message'] ) : null;
		if( $Message !== null )
		{
            ChatService::IncreaseLikes( $Message->id );
			header( 'Location: index.php?rt=channel/show&id_channel=' . $Message->id_channel );	
		}
		else
		{
			header('Location: index.php?rt=_404');
		}
	}

}; 

?>