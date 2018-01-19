<script src="../../fileadmin/js/jquery.maskedinput.min.js"></script>
<div class="editForm">
<?php
    $block = $conn->query(selectRow('blocks', '*', "id={$_POST['blockId']}"))->fetch_assoc();  ?>

		<input type='hidden' id="hidden_hotKeys" value='<?php echo $block['tags_id']; ?>'>
		<input type='hidden' id="hidden_header" value='<?php echo $block['header']; ?>'>
		<input type='hidden' id="hidden_text" value='<?php echo $block['text']; ?>'>
		<input type='hidden' id='hidden_blockId' name='block[blockId]' value='<?php echo $_POST['blockId']; ?>'>
		<div class="additionalFields">
			<?php 
				if (!empty($block['attach_id'])) {
					file_put_contents('log__3_attach_id.txt', print_r( $block['attach_id'], true), FILE_APPEND);
					$attachIds = explode(",", rtrim($block['attach_id'],","));
					
					foreach ($attachIds as $id) {
						$attach = $conn->query(selectRow('attach', '*', "id='" . $id . "'"))->fetch_assoc(); 
						
						switch ($attach['type']) {
							case 0:
								?> 
									<table border='1'>
										<tr>
											<td colspan='2'>
												<p style='text-align:center'>Link</p>
											</td>
										<tr>
										<tr>
											<td>
												<span><input class='deleteItem' width='25' type='image' src="../../fileadmin/images/close.ico" /></span>
											</td>
											<td>
												<input type='text' name='block[link][name][<?php echo $id; ?>r]' placeholder='Input title' value="<?php echo $attach['name']; ?>">
												<input type='text' name='block[link][host][<?php echo $id; ?>r]' placeholder='Input link' value="<?php echo $attach['link']; ?>">
											</td>
										<tr>
									</table>
								<?php
								break;
							case 1:
								?>
									<table border='1'>
										<tr>
											<td colspan='2'>
												<p style='text-align:center'>Image</p>
											</td>
										<tr>
										<tr>
											<td>
												<span><input class='deleteItem' width='25' type='image' src="../../fileadmin/images/close.ico" /></span>
											</td>
											<td>
												<input type='text' name='file[host][<?php echo $id; ?>r]' placeholder='Input link' value="<?php echo isset($attach['link']) ? $attach['link'] : ''; ?>">
												<input type='file' name='file[<?php echo $id; ?>r]' accept='image/*' value="<?php echo isset($attach['name']) ? $attach['name'] : ''; ?>">
											</td>
										<tr>
									</table>

								<?php   
								break;
						}
						
					}
				}

				if (!empty($block['button_id'])) {
					file_put_contents('log__3_button_id.txt', print_r( $block['button_id'], true), FILE_APPEND);
					
					$buttonIds = explode(",", rtrim($block['button_id'],","));

					foreach ($buttonIds as $id) { 
						$button = $conn->query(selectRow('buttons', '*', "id='" . $id . "'"))->fetch_assoc();
						?>
							<table border='1'>
							<tr>
								<td colspan='2'>
									<p style='text-align:center'>Button</p>
								</td>
							<tr>
							<tr>
								<td>
									<span><input class='deleteItem' width='25' type='image' src="../../fileadmin/images/close.ico" /></span>
								</td>
								<td>
									
									<?php
										switch ($button['type']) {
											case 0:
												?>
													<input type='hidden' class='btn_type' name='block[button][type][<?php echo $id; ?>r]' value='0'>
													<div class='addDiv'><button type='button' class='btnType colored' data-type='0' data-id='<?php echo $id; ?>r'>Link</button></div>
													<div class='addDiv'><button type='button' class='btnType' data-type='1' data-id='<?php echo $id; ?>r'>Block</button></div>
													<div class='addDiv'><button type='button' class='btnType' data-type='2' data-id='<?php echo $id; ?>r'>Call</button></div><br>
													<label>Title&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
														<input type='text' class='i_btn_name' name='block[button][name][<?php echo $id; ?>r]' placeholder='Input name*' value="<?php echo $button['name']; ?>"><br>
													<label>Button color </label>
														<input type='text' class='i_btn_color' name='block[button][bg_color][<?php echo $id; ?>r]' placeholder='Input color' value="<?php echo $button['bg_color']; ?>"><br>
													<label>Text color&nbsp;&nbsp;&nbsp;&nbsp;</label>
														<input type='text' class='i_btn_text_color' name='block[button][text_color][<?php echo $id; ?>r]' placeholder='Input text color' value="<?php echo $button['text_color']; ?>"><br>
													<label>Link&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
														<input type='text' class='i_btn_link' name='block[button][special_val][<?php echo $id; ?>r]' placeholder='Input link*' value="<?php echo $button['data']; ?>"><br>
												<?php
												break;
											case 1:
												?>
													<input type='hidden' class='btn_type' name='block[button][type][<?php echo $id; ?>r]' value='1'>
													<div class='addDiv'><button type='button' class='btnType' data-type='0' data-id='<?php echo $id; ?>r'>Link</button></div>
													<div class='addDiv'><button type='button' class='btnType colored' data-type='1' data-id='<?php echo $id; ?>r'>Block</button></div>
													<div class='addDiv'><button type='button' class='btnType' data-type='2' data-id='<?php echo $id; ?>r'>Call</button></div><br>
													<label>Title&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
														<input type='text' class='i_btn_name' name='block[button][name][<?php echo $id; ?>r]' placeholder='Input name*' value="<?php echo $button['name']; ?>"><br>
													<label>Button color </label>
														<input type='text' class='i_btn_color' name='block[button][bg_color][<?php echo $id; ?>r]' placeholder='Input color' value="<?php echo $button['bg_color']; ?>"><br>
													<label>Text color&nbsp;&nbsp;&nbsp;&nbsp;</label>
														<input type='text' class='i_btn_text_color' name='block[button][text_color][<?php echo $id; ?>r]' placeholder='Input text color' value="<?php echo $button['text_color']; ?>"><br>
													<div class='i_btn_block'>
														<select class='i_btn_block_val' name='block[button][btn_block][<?php echo $id; ?>r]'>
															<?php
																$allBlocks = $conn->query(selectAll('blocks', '*', 'portal=' . $domain));
																if ($allBlocks->num_rows > 0) { 
																	while($row = $allBlocks->fetch_assoc()) { ?>
																		<option value='<?php echo $row['id'] ?>' <?php echo ($row['id'] === $button['data']) ? 'selected' : '' ?> > <?php echo $row['header'] ?></option>
																	<?php
																	}
																} ?>
														</select>
													</div>
												<?php
												break;
											case 2:
												?>
													<input type='hidden' class='btn_type' name='block[button][type][<?php echo $id; ?>r]' value='2'>
													<div class='addDiv'><button type='button' class='btnType' data-type='0' data-id='<?php echo $id; ?>r'>Link</button></div>
													<div class='addDiv'><button type='button' class='btnType' data-type='1' data-id='<?php echo $id; ?>r'>Block</button></div>
													<div class='addDiv'><button type='button' class='btnType colored' data-type='2' data-id='<?php echo $id; ?>r'>Call</button></div><br>
													
													<label>Title&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
														<input type='text' class='i_btn_name' name='block[button][name][<?php echo $id; ?>r]' placeholder='Input name*' value="<?php echo $button['name']; ?>"><br>
													<label>Button color </label>
														<input type='text' class='i_btn_color' name='block[button][bg_color][<?php echo $id; ?>r]' placeholder='Input color' value="<?php echo $button['bg_color']; ?>"><br>
													<label>Text color&nbsp;&nbsp;&nbsp;&nbsp;</label>
														<input type='text' class='i_btn_text_color' name='block[button][text_color][<?php echo $id; ?>r]' placeholder='Input text color' value="<?php echo $button['text_color']; ?>"><br>
													<label>Phone&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
														<input type='text' class='i_btn_link' name='block[button][special_val][<?php echo $id; ?>r]' placeholder='Input number*' value="<?php echo $button['data']; ?>"><br>
												<?php
												break;
										}
									?>

								</td>
							<tr>
						</table>
					<?php }
				}
			?>
		</div>
</div>