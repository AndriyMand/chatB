<?php
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
			
			switch ($type) {
			case 0:
				$data = $array['link'][$i];
				break;
			case 1:
				$data = $array['btn_block'][$i];
				break;
			case 2:
				$data = $array['link'][$i];
				break;
		}
			
			$str .= '"'.$i.'" => ["type" => "'.$type.'", "name" => "'.$array['name'][$i].'", "link" => "'.$data.'", "color" => "'.$array['color'][$i].'", "text_color" => "'.$array['text_color'][$i].'"],';
		}
		$str .= ']';
		
		return $str;
	} else {
		return '';
	}
}

if (isset($_POST['edit']) && $_POST['edit']) {
    include 'view/block/edit.php';
    $_POST['edit'] = null;
} elseif (isset($_POST['delete']) && $_POST['delete']) {
    $sql = delete('botmessage', "id={$_POST['blockId']}");
    if ($conn->query($sql) === true) {
        ?><input type='hidden' id="deleteStatus" value='1'><?php
    } else {
        ?><input type='hidden' id="deleteStatus" value='0'><?php
    }
    $_POST['delete'] = null;
} elseif (isset($_POST['addTags']) && $_POST['addTags']) {
    $tags_set     = $conn->query(selectRow('tags_set', '*', "id={$_POST['tagsId']}"))->fetch_assoc(); 
    $tags_db      = explode(",", $tags_set['tags']);
    $tags_insert  = explode(",", $_POST['tagsVal']);
    $result       = array_unique(array_merge($tags_db, $tags_insert));
    
    $sql = update(
                    'tags_set', 
                    "tags='". implode(",", $result) ."'",
                    "id={$_POST['tagsId']}"
                );
    if ($conn->query($sql) === true) {
        ?><input type='hidden' id="addTagsStatus" value='1'><?php
    } else {
        ?><input type='hidden' id="addTagsStatus" value='0'><?php
    }
    $_POST['addTags'] = null;
} elseif (isset($_POST['deleteTag']) && $_POST['deleteTag']) {
    $tags_set     = $conn->query(selectRow('tags_set', '*', "id={$_POST['tagsId']}"))->fetch_assoc(); 
    $tags_db      = explode(",", $tags_set['tags']);
    $pos = array_search(trim($_POST['tagVal']), $tags_db);
    unset($tags_db[$pos]);
    
    $sql = update(
                    'tags_set', 
                    "tags='". implode(",", $tags_db) ."'",
                    "id={$_POST['tagsId']}"
                );
    if ($conn->query($sql) === true) {
        ?><input type='hidden' id="deleteTagStatus" value='1'><?php
    } else {
        ?><input type='hidden' id="deleteTagStatus" value='0'><?php
    }
    $_POST['deleteTag'] = null;
} elseif (isset($_POST['block'])) {
	
	echo "<pre>";
	var_dump($_POST);
	die;

    if (!empty($_POST['block']['header']) && !empty($_POST['block']['text'])) {

		$image = [];
		$image['link'] = isset($_POST['file']['link']) ? $_POST['file']['link'] : '';
		$image['path'] = isset($_FILES['file']['name']) ? $_FILES['file']['name'] : '';
		foreach($_FILES['file']['name'] as $k=>$f) {
			if (!$_FILES['file']['error'][$k]) {
				if (is_uploaded_file($_FILES['file']['tmp_name'][$k])) {
					if (move_uploaded_file($_FILES['file']['tmp_name'][$k], "../fileadmin/images/Files/".$_FILES['file']['name'][$k])) {

					} 
				}
			}
		}
		
		$messageBlocks = [];
		$botmessage = $conn->query(selectAll('botmessage', '*'));
		if ($botmessage->num_rows > 0) {
			while($ress = $botmessage->fetch_assoc()) { 
				$messageBlocks[$ress['id']] = trim($ress['header']);
			}
			$duplicate = array_search(trim($_POST['block']['header']), $messageBlocks);
		}
		

        if($_POST['block']['action_type'] === 'create') {
			if (!$duplicate) {
				$sql = insert('botmessage',[
					'header' => $_POST['block']['header'],
					'text'   => $_POST['block']['text'],
					//'tags'   => isset($_POST['block']['hotKeys']) ? $_POST['block']['hotKeys'] : '',
					'links'  => isset($_POST['block']['link']) ? arrayToString($_POST['block']['link'], 'name', 'host') : '',
					'images' => !empty($image) ? arrayToString($image, 'path', 'link') : '',
					'buttons'=> btnInfoToStr($_POST['block']['button'])
				]);
				if ($conn->query($sql) !== true) {
					echo $conn->error . "<br>";
					echo $sql . "<br>";
				}
			} else {
				echo "<h3>Creation error! Block with name '{$_POST['block']['header']}' already exist</h3><br>";
			}
        } else {
			if (!$duplicate || ($duplicate && $duplicate == (int)$_POST['block']['blockId'])) {
				$link = isset($_POST['block']['link']) ? arrayToString($_POST['block']['link'], 'name', 'host') : '';
				$image = !empty($image) ? arrayToString($image, 'path', 'link') : '';
				$sql = update(
						'botmessage', 
						  "header='". $_POST['block']['header'] ."', "
						. "text='". $_POST['block']['text'] ."', "
						//. "tags='". $_POST['block']['hotKeys'] ."', "
						. "links='" . $link . "', "
						. "images='" . $image . "',"
						. "buttons='" . btnInfoToStr($_POST['block']['button']) . "'",
						"id={$_POST['block']['blockId']}"
						);
				if ($conn->query($sql) !== true) {
					echo $conn->error . "<br>";
					echo $sql . "<br>";
				}
			} else {
				echo "<h3>Creation error! Block with name '{$_POST['block']['header']}' already exist</h3><br>";
			}
        }
    }
    $_POST['block'] = null;
} elseif (isset($_POST['setBlockToTags']) && $_POST['setBlockToTags']) {
	if ($_POST['checked_tag_sets']) {
		$block = $conn->query(selectRow('botmessage', 'tags', "id={$_POST['blockId']}"))->fetch_assoc(); 
		$tags = explode(",", $block['tags']);
		$tags = array_merge($_POST['checked_tag_sets'], $tags);
		$tags = array_unique($tags);
		$sql = update(
            'botmessage', 
            "tags='". implode(",", $tags) ."'",
			"id={$_POST['blockId']}"
		);
		if ($conn->query($sql) !== true) {
			echo $conn->error . "<br>";
			echo $sql . "<br>";
		}
	}
	
	if ($_POST['checked_keys']) {
		$sql = update(
            'keyboard', 
            "block='" . $_POST['blockId'] . "'",
			"id IN (" . implode(",", $_POST['checked_keys']) . ")"
		);
		if ($conn->query($sql) !== true) {
			echo $conn->error . "<br>";
			echo $sql . "<br>";
		}
	}
    
    $_POST['setBlockToTags'] = null;
} elseif (isset($_POST['blockDelete']) && $_POST['blockDelete']) {
   
    $tags    = $conn->query(selectRow('botmessage', 'tags', "id={$_POST['blockDeleteId']}"))->fetch_assoc(); 
    $tags_id = explode(",", $tags['tags']);
    $pos = array_search(trim($_POST['tagSetId']), $tags_id);
    if ($pos !== false) {
        unset($tags_id[$pos]);
    }
    $sql = update(
            'botmessage', 
              "tags='". implode(",", $tags_id) ."'",
            "id={$_POST['blockDeleteId']}"
            );
    if ($conn->query($sql) !== true) {
        echo "Update is failed!<br>";
		echo $conn->error . "<br>";
        echo $sql . "<br>";
    }
    $_POST['blockDelete'] = null;
} elseif (isset($_POST['setTagSet']) && $_POST['setTagSet']) {
    $tags = explode(",", $_POST['tagSet']);
    $tags = array_map('trim',$tags);
    $tags = array_unique($tags);

    $sql  = insert('tags_set',[
        'tags' => implode(",", $tags)
    ]);
    if ($conn->query($sql) !== true) {
        echo "Crearion is failed!<br>";
		echo $conn->error . "<br>";
        echo $sql . "<br>";
    }
} elseif (isset($_POST['setKeySet']) && $_POST['setKeySet']) {

    $keyName = trim($_POST['keySet']);
	$isDuplicate = $conn->query(selectRow('keyboard', '*', "name='$keyName'"));
	

	if($isDuplicate->num_rows == 0) {
		$sql = insert('keyboard',[
			'name' => trim($keyName),
		]);
		if ($conn->query($sql) !== true) {
			echo $conn->error . "<br>";
			echo $sql . "<br>";
		}
	}  
} elseif (isset($_POST['deleteBtn']) && $_POST['deleteBtn']) {
	$itemId = trim($_POST['itemId']);
	$sql = delete($_POST['table'], "id=$itemId");
	if ($conn->query($sql) !== true) {
		echo 'Delete is failed!<br>';
		echo $conn->error . "<br>";
	}

	if ($_POST['table'] === 'tags_set') {
		$botmessage = $conn->query(selectAll('botmessage', '*'));
		if ($botmessage->num_rows > 0) {
			while($ress = $botmessage->fetch_assoc()) { 
			   if (!empty($ress['tags'])) {
					$tags = explode(",", $ress['tags']);
					if (in_array($tagSetId, $tags)) {
						$pos = array_search($tagSetId, $tags);
						unset($tags[$pos]);
						$sql = update(
								'botmessage', 
								"tags='". implode(",", $tags) ."'",
								"id={$ress['id']}"
								);
						if ($conn->query($sql) !== true) {
							echo "Update block '{$ress['id']}' is failed!<br>";
							echo $conn->error . "<br>";
							echo $sql . "<br>";
						}
					}
				}
			}
		}
	}
    
} elseif (isset($_POST['addKeys']) && $_POST['addKeys']) {
	
	$keyboard = [];
	$keyboardTable = $conn->query(selectAll('keyboard', '*'));
	if ($keyboardTable->num_rows > 0) {
		while($key = $keyboardTable->fetch_assoc()) {
			$keyboard[] = strtolower($key['name']); 
		}
	}

    $formPostResult  = explode(",", $_POST['keysVal']);
	
    foreach ($formPostResult as $word) {
		if (!in_array(strtolower($word), $keyboard)) {
			$sql  = insert('keyboard',['name' => $word]);
			if ($conn->query($sql) !== true) {
				echo $conn->error . "<br>";
				echo $sql . "<br>";
			}
		}
	}
    $_POST['addKeys'] = null;
} elseif (isset($_POST['keyboardCheck']) && $_POST['keyboardCheck']) {
	if (isset($_POST['blockCheck'])) {
		$sql = update(
			'botmessage', 
			"keyboard_name='". $_POST['keyboardCheck'] ."'",
			"id=" . $_POST['blockCheck']
			);
		
	} else {
		$sql = update(
			'botmessage', 
			"keyboard_name=''",
			"keyboard_name=" . $_POST['keyboardCheck']
			);
	}
	
	if ($conn->query($sql) !== true) {
		echo $conn->error . "<br>";
		echo $sql . "<br>";
	}
} elseif (isset($_POST['changeDescr']) && $_POST['changeDescr']) {
	$sql = update(
			'keyboard', 
			"description='". $_POST['descrVal'] ."'",
			"id={$_POST['keyId']}"
			);
	if ($conn->query($sql) !== true) {
		echo $conn->error . "<br>";
		echo $sql . "<br>";
	}
}
?>

