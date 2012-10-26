<? 
	include_once 'classes/imap.class.php'; 
	
	session_start();
	ini_set('error_reporting',E_ALL);
	ini_set('display_errors',1);
	if(isset($_POST['Login'])){
		$_SESSION['ImapLogin'] = $_POST['Login'];
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>PHP-IMAP</title>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<style type="text/css">
body{
font-family: "Ubuntu", "Ubuntu Beta", "Bitstream Vera Sans", DejaVu Sans, Tahoma, sans-serif
color: #333;
font-size: 16px;
line-height:1.5;
width:1024px;
margin:auto;
}
.block{
background-color: #F1F1ED;
border:1px solid #333;
padding:10px;
margin:auto;
}
.round{
-moz-border-radius: 8px;
-webkit-border-radius: 8px;
-khtml-border-radius: 8px;
border-radius: 8px;
}

.code{
font-weight:normal;
font-size:16px;
color:green;
}
.bold{
    font-weight:bold;
}
</style>
</head>
<body>
<div class="block round">
<?
if(isset($_GET['part'])){
    echo '<h3>MailBoxes <span class="code">imap::saveAttachment( (INT) $msgno, (INT) $part, (STRING) $file )</span></h3>';
    $imap = new Imap($_SESSION['ImapLogin']['server'],$_SESSION['ImapLogin']['username'],$_SESSION['ImapLogin']['password']);
    $imap->saveAttachment($_GET['msgno'],$_GET['part'],$_GET['name']);
    echo '<p>DOWNLOAD HAS BEEN SAVED TO CURRENT WORKING DIRECTORY. IF YOU RECEIVED AN ERROR CHECK PERMISSIONS ON THE FOLDER</p>';
    DIE();
}
?>
<h2>Imap Connection <span class="code">imap::__construct()</span></h2>
<p>This is a basic demo application using the imap.class.php. It has only been built to give you 
a live working example of the class. I feel like this is better than documentation, but I'll eventually add better documentation. 
This is not meant for production use and the demo code is littered with examples of what not to do when programming.</p>
<form method="post">
<table>
<tr>
<td>Server</td>
<td><input type="text" name="Login[server]" value="<?=@$_SESSION['ImapLogin']['server']?>" /></td>
</tr>
<tr>
<td>Username</td>
<td><input type="text" name="Login[username]" value="<?=@$_SESSION['ImapLogin']['username']?>" /></td>
</tr>
<tr>
<td>Password</td>
<td><input type="text" name="Login[password]" value="<?=@$_SESSION['ImapLogin']['password']?>" /></td>
</tr>
<tr>
<td></td>
<td><input type="submit" value="Connect" /></td>
</tr>
</table>
</form>
<?
if(isset($_SESSION['ImapLogin'])){
	
	include_once 'classes/imap.class.php'; 
	$imap = new Imap($_SESSION['ImapLogin']['server'],$_SESSION['ImapLogin']['username'],$_SESSION['ImapLogin']['password']);
	$imapObj = $imap->returnImapMailBoxmMsgInfoObj();
	echo '<h3>Mailbox Stats <span class="code">imap::returnImapMailBoxmMsgInfoObj()</span></h3><p>Unread: ('.$imapObj->Unread.') Deleted: ('.$imapObj->Deleted.') Emails: ('.$imapObj->Nmsgs.') Size: ('.round($imapObj->Size/1024/1024,1).' MB)</p>';
	echo '<h3>MailBoxes <span class="code">imap::returnMailboxListArr()</span></h3>';
	$mailBoxArr = $imap->returnMailboxListArr();
	
	if(is_array($mailBoxArr)){
		echo '<ul>';
		foreach($mailBoxArr as $i){
			echo '<li><a href="?mailbox='.urlencode($i).'">'.$i.'</a></li>';
		}
        echo '</ul>';
	}
    
    if(isset($_GET['msgno'])){
        echo '<h3>Read Email <span class="code">imap::returnEmailMessageArr( (INT) $msgno )</span></h3>';
        $email = $imap->returnEmailMessageArr(urldecode($_GET['msgno']));
        $attachments = '';
        if(isset($email['attachments'])){
            
            $attachments = '<h4>Attachments</h4>
                <em>NOTE: In the demo attachments are saved to the current working directory. Make sure this script has write permissions to the folder.</em>
                <ul>';
            
            foreach($email['attachments'] as $i){
                $attachments.= '<li><a href="?mailbox='.urlencode($_GET['mailbox']).'&msgno='.trim($_GET['msgno']).'&part=2&name='.$i['name'].'" target="_blank">'.$i['name'].' ('.round(1024/$i['bytes'],1).' KB)</a></li>';
            }
            
            $attachments.= '</ul>';
        }
        
        echo '<div class="block round">
                <h3>'.$email['header']['subject'].'</h3>
                Date: '.$email['header']['date'].'<br/>
                From: '.$email['header']['from'].'<br/>
                Size: '.round(1024/$email['header']['size'],1).' KB<br/>
                <hr/>
                <h4>Body</h4>
                '.base64_decode($email['html']).$attachments.'
            </div>';
    }
    
    if(isset($_GET['mailbox'])){
        echo '<h3>List Emails <span class="code">imap::returnMailBoxHeaderArr( (STRING) $mailbox )</span></h3>';
        $emailArr = $imap->returnMailBoxHeaderArr(urldecode($_GET['mailbox']));
        rsort($emailArr);
        
        if(is_array($emailArr)){
            echo '<ul>';
            foreach($emailArr as $i){
                echo '<li>
                        <a href="?mailbox='.urlencode($_GET['mailbox']).'&msgno='.trim($i['msgno']).'" class="'.((strtoupper($i['status']) != 'READ') ?'bold':'').'">'.$i['subject'].'</a> ('.$i['status'].')<br/>
                        Date: '.$i['date'].'<br/>
                        From: '.$i['from'].'<br/>
                        Size: '.round(1024/$i['size'],1).' KB<br/>
                    </li>';
            }
            echo '</ul>';
        }
    }
}
?>
</div>
</body>
<?php
echo "<pre>"; 
//print_r($imap->returnMailBoxHeaderArr()); 
//print_r($imap->returnEmailMessageArr(1)); 
echo "</pre>"; 

//echo $imap->saveAttachment(2,2,'/path/to/where/you/want/the/attachment/saved'.md5('14'.date('Y-m-d H:i:s')));
