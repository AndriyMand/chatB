<div id="keyboard">
    <div class="keyboard_add">
		<p><textarea rows='3' class="i_tags" cols='30' placeholder='separate tags using ","'></textarea><p>
		<button type='button' class='addKeysBtn'>Add keyboard</button>
	</div>
	<form id="keyboard_form" method="post" name="keyboard_form">
		<div class="for_padding">
			<div id="key-block">
			<?php 
				$enabledKeys = [];
				$result = $conn->query(selectAll('botmessage', '*'));
				if ($result->num_rows > 0) { 
					while($row = $result->fetch_assoc()) { 
						if (empty($row['type'])) { 
							$enabledKeys[] = $row['keyboard_name']; ?>
						<label>
							<input type="checkbox" class="checkBlock" value="<?php  echo $row['id']; ?>" name="blockCheck" data-key="<?php  echo $row['keyboard_name']; ?>" />
							<span><?php echo $row['header']; ?></span>
						</label>
			<?php 		}
					} 
				}?>   
			</div>
		
			<div id="key_checkboxes">
				<?php
					$keyboard = $conn->query(selectAll('keyboard', '*'));
					if ($keyboard->num_rows > 0) { ?>
					<?php 	while($word = $keyboard->fetch_assoc()) { 
								if (!empty($word['name'])) { 
									if (in_array($word['name'], $enabledKeys)) { ?>
										<label style="background-color:#00FFFF">
							
							<?php 	} else { ?>
										<label>
							<?php   } ?>
										<input type="radio" class="radio" value="<?php  echo $word['name']; ?>" name="keyboardCheck" />
										<span>/<?php  echo $word['name']; ?></span>
									</label>
						<?php	}
							}
					} ?>
			</div>
			
			<br>
			<button type='button' id='delete_keyboard'>Delete</button>
			<button type='button' id='save_keyboard'>Save</button>
		</div>
	</form>
</div>