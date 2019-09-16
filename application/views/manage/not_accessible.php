<!-- MIDDLE -->
<div class="dash_main" style="margin:10px;">
<h1><?php echo $this->lang->line('not_found'); ?></h1>
<form>
	<input type="hidden" name="csrf_name" id="csrf_name" value="<?php echo htmlspecialchars($unique_form_name);?>"/>
 	<input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo htmlspecialchars($token);?>"/>	
	<div class="page-not-accessible" >					
		<p><?php echo $msg;?></p>
	</div>
</form>		
</div>