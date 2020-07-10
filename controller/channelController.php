<?php 

require_once __DIR__ . '/../model/message.class.php';
require_once __DIR__ . '/../model/channel.class.php';
require_once __DIR__ . '/../model/chatservice.class.php';


class ChannelController
{
	public static function index() 
	{
        //Default for chat/index
        ChannelController::Show();
    }
    
	public static function Show()
	{
		$Channel = isset( $_GET['id_channel'] ) ? ChatService::GetChannelById( $_GET['id_channel'] ) : null;
		if( $Channel !== null )
		{
			$Channel->fillMessages();

			$username     = $_SESSION['username'];      
			$title        = $Channel->title;
			$MessagesList = ChatService::CreateLinksToUsersMessages( $Channel->Messages );
			$id_channel   = $Channel->id;
			require_once __DIR__ . '/../view/channel_show.php';
		}
		else
		{
			header('Location: index.php?rt=_404');
		}
	}

	public static function Post()
	{
		$Channel = isset( $_GET['id_channel'] ) ? ChatService::GetChannelById( $_GET['id_channel'] ) : null;
		if( $Channel !== null )
		{
			if( isset( $_POST['content'] ) && $_POST['content'] !== '' )
			{
				ChatService::AddMessageToChannel( $_SESSION['id'] , $Channel->id , $_POST['content'] );
				unset( $_POST['content'] );
			}
			header( 'Location: index.php?rt=channel/show&id_channel=' . $Channel->id );
		}
		else
		{
			header('Location: index.php?rt=_404');
		}
	}
	
	public static function CreateNew()
	{
		if( isset( $_POST['NewChannelName'] ) && $_POST['NewChannelName'] !== '' )
		{
			$NewChannelName = $_POST['NewChannelName'];
			unset( $_POST['NewChannelName'] );

			$NewChannelId = ChatService::CreateNewChannel( $NewChannelName , $_SESSION['id'] );
			
			if( $NewChannelId < 0 )
			{
				$username = $_SESSION['username'];
				$title = "Channel with name \"" . $NewChannelName . "\" already exists!";
				
				require_once __DIR__ . '/../view/chat_newchannel.php';
			}
			else
			{
				header( 'Location: index.php?rt=channel/show&id_channel=' . $NewChannelId );
			}
		}
		else
		{
			header('Location: index.php?rt=chat/newchannel');
		}
	}
}; 

?>

