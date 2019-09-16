<?php if(@$error_msg!=''){?><div class="msg"><p class="msg-error"><?php echo $error_msg;?></p></div><?php } ?>
<?php if(@$success_msg!=''){?><div class="msg"><p class="msg-success"><?php echo $success_msg;?></p></div><?php }?>
<?php if(@$warning_msg!=''){?><div class="msg"><p class="msg-warning"><?php echo $warning_msg;?></p></div><?php }?>

<?php if($this->session->flashdata('notification')){?>
<div class="msg">
	<p class="msg-success"><?php echo $this->session->flashdata('notification');?></p>
</div>
<?php }

if($this->session->flashdata('warning')){?>
<div class="msg">
	<p class="msg-warning"><?php echo $this->session->flashdata('warning');?></p>
</div>
<?php }?>
<?php if($this->session->flashdata('error')){?>
<div class="msg">
	<p class="msg-error"><?php echo $this->session->flashdata('error');?></p>
</div>
<?php } ?>
<?php if(!validation_errors()){if(@$csrf_error!=''){?><div class="msg"><p class="msg-error"><?php echo $csrf_error;?></p></div><?php } }?>
<?php if(validation_errors()){?>
	<div class="msg">
		<div class="msg-error">
			<p style="padding-bottom:0;"><strong>Following error(s) need your attention:</strong></p>
			<?php echo validation_errors(); ?>
		</div>
	</div>
<?php }

if($upload_error['error']!=''){?>
	<div class="msg">
		<div class="msg-error">
			<p style="padding-bottom:0;"><strong>Following error(s) need your attention:</strong></p>
			<?php echo $upload_error['error'];?>		
	</div>
	</div>

<?php } ?>

