<style>
button {
    background-color: #4CAF50;
    border: none;
    color: white;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
}
button:hover {
    background-color: #009900;
}
</style>
<?php
include 'db.php';
include 'db_func.php';
if (isset($_POST['question'])) {
    $conn->query(update('botmessage', "text='".$_POST['question']."', tags='".$_POST['question_tags']."'" , 'id=1'));
    $conn->query(update('botmessage', "text='".$_POST['hello']."', tags='".$_POST['hello_tags']."'" , 'id=2'));
    echo "Saved!";
} 
$question = $conn->query(selectRow('botmessage', '*', 'id=1'))->fetch_assoc();
$hello    = $conn->query(selectRow('botmessage', '*', 'id=2'))->fetch_assoc();
?>

<form name="test" method="post">
    <table>
        <tr>
            <td>
               <p>Default question answer:</p>
                <textarea rows="4" cols="50" name="question"><?php
                      echo $question['text'];
                ?></textarea> 
            </td>
            <td>
                <p>hot words</p>
                <textarea rows="3" cols="50" name="question_tags"><?php
                      echo $question['tags'];
                ?></textarea>
            </td>
        </tr>
        <tr>
            <td>
                <p>Hello message:</p>
                <textarea rows="4" cols="50" name="hello"><?php
                      echo $hello['text'];
                ?></textarea>
            </td>
            <td>
                <p>hot words</p>
                <textarea rows="3" cols="50" name="hello_tags"><?php
                      echo $hello['tags'];
                ?></textarea>
            </td>
        </tr>
    </table>
  <br><br>
  <button type="submit" class="button1">Save</button>
</form>