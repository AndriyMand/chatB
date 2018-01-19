<html>
<head>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

	<link rel="stylesheet" type="text/css" href="../../fileadmin/css/style.css">
    <!-- Begin emoji-picker Stylesheets -->
    <link href="../../fileadmin/css/emoji.css" rel="stylesheet">
    <!-- End emoji-picker Stylesheets -->
	
	
	<!-- Begin emoji-picker JavaScript -->
    <script src="../../fileadmin/js/config.js"></script>
    <script src="../../fileadmin/js/util.js"></script>
    <script src="../../fileadmin/js/jquery.emojiarea.js"></script>
    <script src="../../fileadmin/js/emoji-picker.js"></script>
    

</head>
<body>
<script src="http://cloud.github.com/downloads/digitalBush/jquery.maskedinput/jquery.maskedinput-1.3.min.js"></script>
<script src="../../fileadmin/js/tools.js"></script>

<div id="abc">
    <div id="popupContact">
	
        <form id="popup_form" method="post" name="form_create_block" enctype="multipart/form-data">
            <input type='hidden' id="action_type" name='block[action_type]' value=''>
            <br>
            <h5>Create/Edit block:</h5>
            <div class="addButtons">
                <div class="addDiv"><button type='button' class='clear'>Clear all</button></div>
                <div class="addDiv"><button type='button' class='add' data-type='Link'>Add link</button></div>
                <div class="addDiv"><button type='button' class='add' data-type='Image'>Add image</button></div>
				<div class="addDiv"><button type='button' class='add' data-type='Button'>Add button</button></div>
            </div>
            <button type='button' id='save_block'>Save</button>
            <button type='button' id='open_chat'>add Chat btn</button>
            <!--<p><input type='text' id="i_hotKeys" name='block[hotKeys]' placeholder='Hotkeys (Example: 1,5,7)'></p>-->
			<p></p>
			<p class="i_header lead emoji-picker-container">
				<input type='text' id="i_header" name='block[header]' placeholder='Input header name*' data-emojiable="true">
			<p>
			
			<p class="i_text lead emoji-picker-container">
				<textarea rows='3' id="i_text" cols='25' name='block[text]' placeholder='Input text*' data-emojiable="true"></textarea>
			<p>

            <div class='formAdditional'><div class="additionalFields"></div></div>   
			<img class="close" src="../../fileadmin/images/close.ico" style="width:25;">			
        </form>
    </div>
</div>
	
	<script>
      $(function() {
        // Initializes and creates emoji set from sprite sheet
        window.emojiPicker = new EmojiPicker({
          emojiable_selector: '[data-emojiable=true]',
          assetsPath: '../../fileadmin/img/',
          popupButtonClasses: 'fa fa-smile-o'
        });
        // Finds all elements with `emojiable_selector` and converts them to rich emoji input fields
        // You may want to delay this step if you have dynamically created input fields that appear later in the loading process
        // It can be called as many times as necessary; previously converted input fields will not be converted again
        window.emojiPicker.discover();
      });

      // Google Analytics
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-49610253-3', 'auto');
      ga('send', 'pageview');
    </script>
	
	<ul class="menu cf">
		<li>
			<a href="../../index.phtml">Main</a>
		</li>
		<li>
			<a href="">Block</a>
			<ul class="submenu">
				<li><a href="list.phtml">List</a></li>
				<li><a href="detail.phtml" class="li_detail">Detail</a></li>
				<li><a href="detail.phtml" id="create_block">Create</a></li>
			</ul>
		</li>
		<li>
			<a href="">Tags</a>
			<ul class="submenu">
				<li><a href="../tags/list.phtml">List</a></li>
				<li><a href="../tags/edit.phtml">Edit</a></li>
			</ul>			
		</li>
		<li>
			<a href="../menu/menu.phtml">Menu</a>			
		</li>
		<!--
		<li>
			<a href="">Keyboard</a>
			<ul class="submenu">
				<li><a href="../keyboard/list.phtml">List</a></li>
				<li><a href="../keyboard/edit.phtml">Edit</a></li>
			</ul>
		</li>
		-->
	</ul>

<?php
	include '../../db/connection.php';
    include '../../db/query.php';
?>


<div class="fit_center">
<div id='blocks_table'>
<?php

	//$host = empty($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_X_FORWARDED_PROTO'] . "://" . $_SERVER['HTTP_HOST'] : $_SERVER['HTTP_ORIGIN'];
	$host = $_REQUEST['DOMAIN'];
	$portalId = $conn->query(selectRow('portals', 'id', "host_name='" . $host . "'"))->fetch_assoc()['id']; 
	
	if ($portalId) {
		$result = $conn->query(selectAll('blocks', '*', 'portal_id=' . $portalId));
	}

	$default = [];
    
    if (isset($result->num_rows) && $result->num_rows > 0) { ?>

        <?php while($row = $result->fetch_assoc()) { 
			if (empty($row['default_type'])) { ?>
				
				<input type='hidden' class="block_info" id="<?php echo $row['id']; ?>" name='block[info][]' value='<?php echo $row['header']; ?>'>
			
				<br>
				<div class="detail">
			<?php } else { ?>
				<div class="detail default">
					<h4 class="colored">Default (<?php echo $row['type']; ?>)</h4>
			<?php } ?>
			
					<div class='block'>
						<h3><?php echo $row['header']; ?></h3>
						<p><?php echo $row['text']; ?></p>
						<?php 
							if (!empty($row['attach_id'])) {
								$attachIds = explode(",", rtrim($row['attach_id'],","));
								
								foreach ($attachIds as $id) {
									$attach = $conn->query(selectRow('attach', '*', "id='" . $id . "'"))->fetch_assoc(); 
									
									switch ($attach['type']) {
										case 0:
											?> <p><a href="<?php echo $attach['link']; ?>"><?php echo $attach['name']; ?></a></p> <?php
											break;
										case 1:
											if (!empty($attach['link'])) { ?>
												<p><a href="<?php echo $attach['link']; ?>">
													<img src="<?php echo "../../fileadmin/images/Files/" . $attach['name']; ?>" alt="" width="200">
												</a></p>
										   <?php } else { ?>
												<p><img src="<?php echo "../../fileadmin/images/Files/" . $attach['name']; ?>" alt="" width="200"></p>
											<?php   
											}
											break;
									}
									
								}
							}

							if (!empty($row['button_id'])) {
								$style = '';
								
								$buttonIds = explode(",", rtrim($row['button_id'],","));

								foreach ($buttonIds as $id) { 
									$button = $conn->query(selectRow('buttons', '*', "id='" . $id . "'"))->fetch_assoc(); 
									
									if (!empty($button['bg_color'])) { 
										$style .= "background-color: ".trim($button['bg_color']).";";
									}
									if (!empty($button['text_color'])) { 
										$style .= "color: ".trim($button['text_color']).";";
									} ?>

									<a style="
									<?php echo $style; ?>
									border: none;
									padding: 5px 10px;
									text-align: center;
									text-decoration: none;
									display: inline-block;
									font-size: 16px;
									margin: 4px 2px;
									cursor: pointer;
									"class="button"><?php echo $button['name']; ?>

									</a>
								<?php }
							}
						?>
					</div>
					<div class="info">
						<div class='tags'>
							<span> Tag set numbers: </span> 
							<span class="tags_in_block">
								<?php
									if (!empty($row['tags_id'])) {
										$tags = explode(",", $row['tags_id']);
										foreach ($tags as $id) { 
											$attach = $conn->query(selectRow('tags', '*', "id='" . $id . "'"))->fetch_assoc(); 
											if ($attach['id']) {?>
											<p>
											   <?php echo $attach['id']; ?>
											</p>
											<?php 
											}
										}
									}
								?>
							</span>
						</div>
						<span> Block action: </span>
						<span class='edit'>
							<input type='hidden' id="blockId" value='<?php echo $row['id']; ?>'>
							<button type='button' class='editBlock'>Edit</button>
							<button type='button' class='deleteBlock'>Delete</button>
						</span>
					</div>
				</div>
                
	<?php  
		
		} ?>

<?php } ?>
</div>
</div>
</body>
</html>

<?php 

	function attachInsertion($data, $type) {
		global $conn;
		$attach_id = '';
		if (isset($data)) {
			foreach ($data['name'] as $id => $name) {
				$sql = insert('attach',[
					'type' => $type,
					'name' => $name,
					'link' => $data['host'][$id]
				]);
				if ($conn->query($sql) !== true) {
					file_put_contents('../log_query.txt', "Attach: \n", FILE_APPEND);
					file_put_contents('../log_query.txt', $conn->error . "\n", FILE_APPEND);
				}
				$attach_id .= $conn->insert_id . ',';
			}
		}
		return $attach_id;
	}
	
	function buttonInsertion($data) {
		global $conn;
		$button_id = '';
		if (isset($data)) {
			foreach ($data['name'] as $id => $name) {
				$sql = insert('buttons',[
					'type'       => $data['type'][$id],
					'name'       => $name,
					'data'       => $data['special_val'][$id],
					'bg_color'   => $data['bg_color'][$id],
					'text_color' => $data['text_color'][$id]
				]);
				if ($conn->query($sql) !== true) {
					file_put_contents('../log_query.txt', "Buttons: \n", FILE_APPEND);
					file_put_contents('../log_query.txt', $conn->error . "\n", FILE_APPEND);
				}
				$button_id .= $conn->insert_id . ',';
			}
		}
		return $button_id;
	}

/*
	function arrayToString($array, $index, $value) {
		$str = "[";
		foreach ($array[$index] as $i => $v) {
			if ($v) {
				$str .= '"' . $v . '" => "' . $array[$value][$i] . '",';
			}
		}
		$str .= "]";
		return $str;
	}

	function btnInfoToStr($array) {
		if (!empty($array)) {

			$str = '[';
			foreach ($array['type'] as $i => $type) {
				$data = $array['special_val'][$i];
	
				$str .= '"'.$i.'" => ["type" => "'.$type.'", "name" => "'.$array['name'][$i].'", "link" => "'.$data.'", "color" => "'.$array['color'][$i].'", "text_color" => "'.$array['text_color'][$i].'"],';
			}
			$str .= ']';
			
			return $str;
		} else {
			return '';
		}
	}
*/

	if (isset($_POST['delete']) && $_POST['delete']) {
		$sql = delete('blocks', "id={$_POST['blockId']}");
		if ($conn->query($sql) === true) {
			?><input type='hidden' id="deleteStatus" value='1'><?php
		} else {
			?><input type='hidden' id="deleteStatus" value='0'><?php
		}
		$_POST['delete'] = null;
	} elseif (isset($_POST['block'])) {

		if (!empty($_POST['block']['header']) && !empty($_POST['block']['text'])) {

			$image = [];
			$image['host'] = isset($_POST['file']['host']) ? $_POST['file']['host'] : '';
			$image['name'] = isset($_FILES['file']['name']) ? $_FILES['file']['name'] : '';
			foreach($_FILES['file']['name'] as $k=>$f) {
				if (!$_FILES['file']['error'][$k]) {
					if (is_uploaded_file($_FILES['file']['tmp_name'][$k])) {
						if (move_uploaded_file($_FILES['file']['tmp_name'][$k], "../../fileadmin/images/Files/".$_FILES['file']['name'][$k])) {

						} 
					}
				}
			}

			if($_POST['block']['action_type'] === 'create') {

					$attachId  = attachInsertion($image, 1);
					$attachId .= attachInsertion($_POST['block']['link'], 0);
					
					$buttonId  = buttonInsertion($_POST['block']['button']);
				
				//if (!$duplicate) {
					$sql = insert('blocks',[
						'header' => sprintf($conn->real_escape_string($_POST['block']['header'])),
						'text'   => sprintf($conn->real_escape_string($_POST['block']['text'])),
						'attach_id' => $attachId,
						'button_id' => $buttonId,
						'portal_id' => $portalId
					]);
					if ($conn->query($sql) !== true) {
						file_put_contents('../log_query.txt', 'Block:', FILE_APPEND);
						file_put_contents('../log_query.txt', $conn->error, FILE_APPEND);
					}
				//} else {
				//	echo "<h3>Creation error! Block with name '{$_POST['block']['header']}' already exist</h3><br>";
				//}
			} else {
				//if (!$duplicate || ($duplicate && $duplicate == (int)$_POST['block']['blockId'])) {
					
					$attachId  = attachInsertion($image, 1);
					$attachId .= attachInsertion($_POST['block']['link'], 0);
					
					$buttonId  = buttonInsertion($_POST['block']['button']);
					
					$sql = update(
							'blocks', 
							  "header='". sprintf($conn->real_escape_string($_POST['block']['header'])) ."', "
							. "text='". sprintf($conn->real_escape_string($_POST['block']['text'])) ."', "
							. "attach_id='" . $attachId . "',"
							. "button_id='" . $buttonId . "',"
							. "portal_id='" . $portalId . "'",
							"id={$_POST['block']['blockId']}"
							);
					if ($conn->query($sql) !== true) {
						file_put_contents('../log_query.txt', 'Block:', FILE_APPEND);
						file_put_contents('../log_query.txt', $conn->error, FILE_APPEND);
					}
				//} else {
				//	echo "<h3>Creation error! Block with name '{$_POST['block']['header']}' already exist</h3><br>";
				//}
			}
		}
		$_POST['block'] = null;
	} elseif (isset($_POST['edit']) && $_POST['edit']) {
		include 'edit.php';
		$_POST['edit'] = null;
	}

?>