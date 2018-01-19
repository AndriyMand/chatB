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
            <!--<p><input type='text' id="i_hotKeys" name='block[hotKeys]' placeholder='Hotkeys (Example: 1,5,7)'></p>-->
			<p></p>
			<p class="i_header lead emoji-picker-container">
				<input type='text' id="i_header" name='block[header]' placeholder='Input header name*' data-emojiable="true">
			<p>
			
			<p class="i_text lead emoji-picker-container">
				<textarea rows='3' id="i_text" cols='25' name='block[text]' placeholder='Input text*' data-emojiable="true"></textarea>
			<p>

            <div class='formAdditional'><div class="additionalFields"></div></div>   
			<img class="close" src="fileadmin/images/close.ico" style="width:25;">			
        </form>
    </div>
</div>