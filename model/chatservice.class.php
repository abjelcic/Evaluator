<?php

require_once __DIR__ . '/../app/database/db.class.php';

require_once __DIR__ . '/../model/channel.class.php';
require_once __DIR__ . '/../model/message.class.php';
require_once __DIR__ . '/../model/user.class.php';


class ChatService
{

    public static function GetChannelsByUser( $id_creator )
    {
        $db = DB::getConnection();
        try
        {
          $st = $db->prepare( 'SELECT * FROM dz2_channels WHERE id_user=:id_user' );
          $st->execute( [ 'id_user' => $id_creator ] );
        }
        catch( PDOException $e ) { exit( 'Error:' . $e->getMessage() ); }

        $ChannelsList = [];
        while( $row = $st->fetch() )
        {
            $ChannelsList[] = new Channel( $row['id'] , $row['id_user'] , $row['name'] );
        }

        return $ChannelsList;
    }

    public static function GetAllChannels()
    {
        $db = DB::getConnection();
        try
        {
          $st = $db->prepare( 'SELECT * FROM dz2_channels' );
          $st->execute();
        }
        catch( PDOException $e ) { exit( 'Error:' . $e->getMessage() ); }

        $ChannelsList = [];
        while( $row = $st->fetch() )
        {
            $ChannelsList[] = new Channel( $row['id'] , $row['id_user'] , $row['name'] );
        }

        return $ChannelsList;
    }

    public static function GetAllUsers()
    {
        $db = DB::getConnection();
        try
        {
          $st = $db->prepare( 'SELECT * FROM dz2_users' );
          $st->execute();
        }
        catch( PDOException $e ) { exit( 'Error:' . $e->getMessage() ); }

        $UsersList = [];
        while( $row = $st->fetch() )
        {
            $UsersList[] = new User( $row['id'] , $row['username'] , $row['email'] , $row['has_registered'] );
        }

        return $UsersList;
    }

    public static function GetMessagesBySenderId( $id_sender )
    {
        $db = DB::getConnection();
        try
        {
          $st = $db->prepare( 'SELECT * FROM dz2_messages WHERE id_user=:id_user ORDER BY date DESC' );
          $st->execute( [ 'id_user' => $id_sender ] );
        }
        catch( PDOException $e ) { exit( 'Error:' . $e->getMessage() ); }

        $MessagesList = [];
        while( $row = $st->fetch() )
        {   
            $sender = ChatService::GetUserById( $row['id_user'] );

            $MessagesList[] = new Message( $row['id'], 
                                           $sender,
                                           $row['id_channel'] ,
                                           $row['date'],
                                           $row['content'],
                                           $row['thumbs_up']
                                         );
        }

        return $MessagesList;
    }

    public static function GetUserById( $id )
    {
        $db = DB::getConnection();
        try
        {
          $st = $db->prepare( 'SELECT * FROM dz2_users WHERE id=:id' );
          $st->execute( [ 'id' => $id ] );
        }
        catch( PDOException $e ) { exit( 'Error:' . $e->getMessage() ); }

        if( $st->rowCount() === 0 )
            return null;
        
        $row = $st->fetch();

        $user = new User( $row['id'] , $row['username'] , $row['email'] , $row['has_registered'] );

        return $user;
    }

    public static function GetChannelById( $id )
    {
        $db = DB::getConnection();
        try
        {
          $st = $db->prepare( 'SELECT * FROM dz2_channels WHERE id=:id' );
          $st->execute( [ 'id' => $id ] );
        }
        catch( PDOException $e ) { exit( 'Error:' . $e->getMessage() ); }

        if( $st->rowCount() === 0 )
            return null;
        
        $row = $st->fetch();

        $channel = new Channel( $row['id'] , $row['id_user'] , $row['name'] );

        return $channel;
    }

    public static function GetMessagesByChannelId( $id )
    {
        $db = DB::getConnection();
        try
        {
          $st = $db->prepare( 'SELECT * FROM dz2_messages WHERE id_channel=:id_channel ORDER BY date ASC' );
          $st->execute( [ 'id_channel' => $id ] );
        }
        catch( PDOException $e ) { exit( 'Error:' . $e->getMessage() ); }

        $MessagesList = [];
        while( $row = $st->fetch() )
        {   
            $sender = ChatService::GetUserById( $row['id_user'] );

            $MessagesList[] = new Message( $row['id'], 
                                           $sender,
                                           $row['id_channel'] ,
                                           $row['date'],
                                           $row['content'],
                                           $row['thumbs_up']
                                         );
        }

        return $MessagesList;
    }

    public static function AddMessageToChannel( $id_sender, $id_channel, $MessageContent )
    {
        if( $MessageContent === '' )
            return;
        
        $date = ChatService::GetCurrentDateTime_SQLFormat();
        
        $db = DB::getConnection();
        try
        {
          $st = $db->prepare( 'INSERT INTO dz2_messages ' . 
                              '(id_user, id_channel, content, thumbs_up, date) ' . 
                              'VALUES (:id_user, :id_channel, :content, :thumbs_up, :date)'
                            );
          $st->execute( [ 'id_user'    => $id_sender , 
                          'id_channel' => $id_channel ,
                          'content'    => $MessageContent ,
                          'thumbs_up'  => 0 ,
                          'date'       => $date
                        ] );
        }
        catch( PDOException $e ) { exit( 'Error:' . $e->getMessage() ); }

    }

    public static function GetMessageById( $id )
    {
        $db = DB::getConnection();
        try
        {
          $st = $db->prepare( 'SELECT * FROM dz2_messages WHERE id=:id' );
          $st->execute( [ 'id' => $id ] );
        }
        catch( PDOException $e ) { exit( 'Error:' . $e->getMessage() ); }

        if( $st->rowCount() === 0 )
            return null;
        
        $row = $st->fetch(); 
        
        $Message = new Message( $row['id'],
                                $row['id_user'],
                                $row['id_channel'],
                                $row['date'],
                                $row['content'],
                                $row['thumbs_up']
                              );
        return $Message;
    }

    public static function IncreaseLikes( $id )
    {
        $db = DB::getConnection();
        try
        {
          $st = $db->prepare( 'SELECT * FROM dz2_messages WHERE id=:id' );
          $st->execute( [ 'id' => $id ] );
        }
        catch( PDOException $e ) { exit( 'Error:' . $e->getMessage() ); }

        if( $st->rowCount() === 0 )
            return null;
        
        $row = $st->fetch(); 
        
        $Likes = $row['thumbs_up'];


        $db = DB::getConnection();
        try
        {
          $st = $db->prepare( 'UPDATE dz2_messages SET thumbs_up=:likes WHERE id=:id' );
          $st->execute( [ 'likes' => $Likes+1 , 'id' => $id ] );
        }
        catch( PDOException $e ) { exit( 'Error:' . $e->getMessage() ); }
    }

    public static function CreateNewChannel( $NewChannelName, $id_creator )
    {
        $ChannelsList = ChatService::GetAllChannels();
        foreach( $ChannelsList as $channel )
            if( $channel->title === $NewChannelName )
                return -1;


        $db = DB::getConnection();
        try
        {
          $st = $db->prepare( 'INSERT INTO dz2_channels ' . 
                              '(id_user,name) ' . 
                              'VALUES (:id_user, :name)'
                            );
          $st->execute( [ 'id_user' => $id_creator , 
                          'name'    => $NewChannelName ,
                        ] );
        }
        catch( PDOException $e ) { exit( 'Error:' . $e->getMessage() ); }

        $db = DB::getConnection();
        try
        {
          $st = $db->prepare( 'SELECT * FROM dz2_channels WHERE name=:name' );
          $st->execute( [ 'name' => $NewChannelName ] );
        }
        catch( PDOException $e ) { exit( 'Error:' . $e->getMessage() ); }

        if( $st->rowCount() !== 1 )
            exit( 'Database has two channels of the same name somehow...' );

        $row = $st->fetch();

        return $row['id'];
    }

    public static function CreateLinksToUsersMessages( $MessagesList )
    {
        $AllUsers = ChatService::GetAllUsers();

        //Very uneffiecient...
        $ModifiedMessagesList = [];
        foreach( $MessagesList as $Message )
        {

            foreach( $AllUsers as $user )
            {
                $username = "@" . $user->name;
                $link     = "index.php?rt=chat/usermessages&id_user=" . $user->id; 
                $replace  = "<a class=\"userlinks\" href=\"" . $link . "\">" . $username . "</a>";

                $Message->content      = str_replace( $username , $replace , $Message->content ); 
            }
                     
            $username = "@" . $Message->sender->name;
            $link     = "index.php?rt=chat/usermessages&id_user=" . $Message->sender->id; 
            $replace  = "<a class=\"userlinks\" href=\"" . $link . "\">" . $username . "</a>";
            
            $Message->sender->name = $replace;



            $ModifiedMessagesList[] = $Message;
        }

        return $ModifiedMessagesList;
    }

    public static function IsValidUserId( $id )
    {
        return ChatService::GetUserById( $id ) !== null;
    }

    public static function GetUsernameById( $id )
    {
        $user = ChatService::GetUserById( $id );
        return $user === null ? 'Invalid id' : $user->name; 
    }

    private static function GetCurrentDateTime_SQLFormat()
    {
        date_default_timezone_set("Europe/Zagreb");
        $mysqldate = date( 'Y-m-d H:i:s' );
        return $mysqldate;
    }

};

?>

