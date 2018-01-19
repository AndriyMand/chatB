<div id='keys_set_table'>
<?php
    $result = $conn->query(selectAll('keyboard', '*'));
    if ($result->num_rows > 0) { ?>
        <table border="1"> 
			<tr class="tr_tags">
				<td colspan="4">
					<h5>Fill this area to add keyboard</h5>
					<p><textarea rows='3' style="width:100%;" class="key_input" placeholder='input keyboard'></textarea><p>
					<button type='button' id="create_key_btn">Add keyboard</button>
				</td>
			</tr>
			<tr>
				<td>
				</td>
				<td>
					<p>Keyboard</p>
				</td>
				<td>
					<p>Teaser</p>
				</td>
				<td>
					<p>Related block</p>
				</td>
			</tr>
            <?php while($row = $result->fetch_assoc()) { ?>
                        <tr class="tr_tags">
                            <td class="tags_id">
                                <p><?php echo $row['id']; ?></p>
                                <button type='button' id="deleteBtn" data-id="<?php echo $row['id']; ?>" data-table="keyboard">Delete</button>
                            </td>
                            <td>
								<div class="tags_in_block">
										<p>/ <?php  echo $row['name']; ?></p>
								</div>
							</td>
							<td>
								<div>
									<p><input type='text' style="width:100%;" maxlength="50" value="<?php echo $row['description']; ?>" id="i_descr" placeholder='Input description'></p>
									<button type='button' class='saveKeyDescr' value="<?php echo $row['id']; ?>">Save</button>
								</div>
							</td>
                            <td>
								<?php
									if ($row['block']) { 
										$related = $conn->query(selectRow('botmessage', '*', "id={$row['block']}"))->fetch_assoc();
									?>
										<div class="tags_in_block">
												<p class='<?php  echo $related['id']; ?>'> <?php  echo $related['header']; ?> </p>
										</div>
							<?php 	} else { ?>
									<p>empty</p>	
							<?php 	} ?>
                            </td>
                        </tr> 
                <?php } ?>
        </table>
<?php } ?>
</div>