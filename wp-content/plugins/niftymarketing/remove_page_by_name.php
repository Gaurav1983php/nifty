<h2>Remove Page By Name</h2>
<form action="" method="post">
   <table>
       <tr>
            <td>Page Name</td><td><input placeholder="Page Name" type="text" name="titlep" value="<?php echo $_POST['titlep']; ?>" /></td>
        </tr>
        <tr>    
            <td>Delete Permanently</td><td><input type="checkbox" name="force_delete" value="1" /> (Without moving to trash)</td>
        </tr>
        <tr>    
            <td>Dry Run</td><td><input type="checkbox" name="dry_run" value="1" checked /> (Uncheck this if you want to delete)</td>
        </tr>
        <tr>
            <td colspan="2"><input type="submit" value="Remove" name="remove" /></td>
        </tr>        
   </table> 
</form>
<pre>
<?php

if($_POST['remove'] == 'Remove'){

    $force_delete = false;
    $deleted = false;
    if($_POST['force_delete'] == '1'){ $force_delete = true; }

    global $wpdb;
    $result = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}posts WHERE post_type = 'page' AND post_title = '".trim($_POST['titlep'])."'");


            foreach($result as $v){

                    if($_POST['dry_run'] != '1'){ 
                            wp_delete_post($v->ID, $force_delete);
                            $deleted = true;
                        }

                    echo $v->ID.' '.$v->post_title.' '.$v->post_status.'  '.$v->post_date.'<br />' ;
                    
                 

            }


}

if($deleted){
    echo '<br/><br/><h2>Above pages deleted successfully</h2>';
}

?>
