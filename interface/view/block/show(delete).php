
<div id='blocks_headers'>
<?php
	$default = [];
    $result  = $conn->query(selectAll('botmessage', '*'));
    if ($result->num_rows > 0) { 
        //$count = 0; ?>
        <table border="1">
				<tr class="tr_tags">
                    <td>
                        <h5>Available keyboard</h5>
                        <div class="ck-button">
                            <?php
                                $keyboard = $conn->query(selectAll('keyboard', '*'));
                                if ($keyboard->num_rows > 0) { ?>
                                    <?php while($row = $keyboard->fetch_assoc()) { ?>
                                            <label>
                                                <input class='keys_col' type="checkbox" name="checked_keyboard[]" value="<?php echo $row['id']; ?>"><span><?php echo $row['name']; ?></span>
                                            </label>      
                                <?php } ?>
                            <?php  } ?>    
                        </div>
                    </td>
                </tr>
                <tr class="tr_tags">
                    <td>
                        <h5>Available hot words collections</h5>
                        <div class="ck-button">
                            <?php
                                $tags = $conn->query(selectAll('tags_set', '*'));
                                if ($tags->num_rows > 0) { ?>
                                    <?php while($tagset = $tags->fetch_assoc()) { ?>
                                            <label>
                                                <input class='tags_col' type="checkbox" name="checked_tag_sets[]" value="<?php echo $tagset['id']; ?>"><span><?php echo $tagset['id']; ?></span>
                                            </label>      
                                <?php } ?>
                            <?php  } ?>    
                        </div>
                    </td>
                </tr>
                <tr class="tr_tags">
                    <td>
                        <h5>Available blocks:</h5>
                        <div class="block_set">
                <?php while($row = $result->fetch_assoc()) { 
							if (empty($row['type'])) { ?>
							<p id="<?php echo $row['id']; ?>"><?php echo $row['header']; ?></p>
				<?php 
							} else {
								$default[] = $row;
							}
						} ?>
                        </div>
                    </td>
                </tr>
				<tr>
					<?php foreach ($default as $block) { ?>
					<td>
						<p style="width:100%; text-align:center;"><?php echo $block['type_desc']; ?></p>
						<div class='block'>
							<h3><?php echo $block['header']; ?></h3>
							<p><?php echo $block['text']; ?></p>
							<?php 
								if (!empty($block['links'])) {
									$links = [];
									eval("\$links = {$block['links']};"); 
									foreach ($links as $name => $link) { ?>
									   <p><a href="<?php echo $link; ?>"><?php echo $name; ?></a></p> 
							<?php   }
								}
								if (!empty($block['images'])) {
									$images = [];
									eval("\$images = {$block['images']};"); 
									foreach ($images as $path => $link) { ?>
									   <?php if (!empty($link)) { ?>
											<p><a href="<?php echo $link; ?>">
												<img src="<?php echo "fileadmin/images/Files/" . $path; ?>" alt="" width="200">
											</a></p>
									   <?php } else { ?>
											<p><img src="<?php echo "fileadmin/images/Files/" . $path; ?>" alt="" width="200"></p>
										<?php   
									   }
									}
								}
								if (!empty($block['buttons'])) {
									$style = '';
									$buttons = [];
									eval("\$buttons = ".$block['buttons'].";"); 
									foreach ($buttons as $button) { 
										if (!empty($button['color'])) { 
											$style .= "background-color: ".trim($button['color']).";";
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
										" href="<?php echo $button['link']; ?>" class="button"><?php echo $button['name']; ?>

										</a>
									<?php }
								}
							?>
						</div>
						<div class='block edit'>
							<input type='hidden' id="blockId" value='<?php echo $block['id']; ?>'>
							<button type='button' class='editBlock'>Edit</button>
						</div>
					</td>
					<?php } ?>
				</tr>
        </table>
<?php } ?>
</div>


<div id='blocks_table'>
<?php
    $result = $conn->query(selectAll('botmessage', '*'));
    if ($result->num_rows > 0) { ?>
        <table>
        <tr>
        <?php while($row = $result->fetch_assoc()) { 
			if (empty($row['type'])) { ?>
            <td>
                <div class='block'>
                    <h3><?php echo $row['header']; ?></h3>
                    <p><?php echo $row['text']; ?></p>
                    <?php 
                        if (!empty($row['links'])) {
                            $links = [];
                            eval("\$links = {$row['links']};"); 
                            foreach ($links as $name => $link) { ?>
                               <p><a href="<?php echo $link; ?>"><?php echo $name; ?></a></p> 
                    <?php   }
                        }
                        if (!empty($row['images'])) {
                            $images = [];
                            eval("\$images = {$row['images']};"); 
                            foreach ($images as $path => $link) { ?>
                               <?php if (!empty($link)) { ?>
                                    <p><a href="<?php echo $link; ?>">
                                        <img src="<?php echo "fileadmin/images/Files/" . $path; ?>" alt="" width="200">
                                    </a></p>
                               <?php } else { ?>
                                    <p><img src="<?php echo "fileadmin/images/Files/" . $path; ?>" alt="" width="200"></p>
                                <?php   
                               }
                            }
                        }
						if (!empty($row['buttons'])) {
							$style = '';
                            $buttons = [];
                            eval("\$buttons = ".$row['buttons'].";"); 
							
                            foreach ($buttons as $button) { 
						
								if (!empty($button['color'])) { 
									$style .= "background-color: ".trim($button['color']).";";
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
                <div class='block tags'>
                    <p>Tag set numbers:</p>
                    <div class="tags_in_block">
                        <?php
                            if (!empty($row['tags'])) {
                                $tags = explode(",", $row['tags']);
                                foreach ($tags as $tagId) { 
                                    if ($tagId) {?>
                                <p>
                                   <?php echo $tagId; ?>
                                </p>
                                <?php 
                                    }
                                }
                            }
                        ?>
                    </div>
                </div>
                <div class='block edit'>
                    <input type='hidden' id="blockId" value='<?php echo $row['id']; ?>'>
                    <button type='button' class='editBlock'>Edit</button>
                    <button type='button' class='deleteBlock'>Delete</button>
                </div>
            </td>
                
	<?php }
		} ?>
        </tr>
        </table>
<?php } ?>
</div>

