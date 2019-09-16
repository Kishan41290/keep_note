<?php if(@$success_msg!=''){?><div class="msg-success"><?php echo $success_msg;?></div><?php }?>
<?php if(@$error_msg!=''){?><div class="msg-error"><?php echo $error_msg;?></div><?php }?>
<?php if(@$warning_msg!=''){?><div class="msg-warning"><?php echo $warning_msg;?></div><?php }?>
<?php if(@$confirm_msg!=''){?><div class="msg-confirm"><?php echo $confirm_msg;?></div><?php }?>

<script type="text/javascript">
	var noti_suc_msg = "<?php echo $this->session->flashdata('notification');?>";
	var noti_err_msg = "<?php echo $this->session->flashdata('error');?>";
	var noti_war_msg = "<?php echo $this->session->flashdata('warning');?>";
	var noti_info_msg = "<?php echo $this->session->flashdata('ingres_fetch_object(result)');?>";
</script>


<?php if($this->session->flashdata('notification')){?>
	<script type="text/javascript">
		fn_success_msg(noti_suc_msg);
	</script>
	<!-- <div class="msg-success"><?php echo $this->session->flashdata('notification');?></div> -->
<?php }

if($this->session->flashdata('warning')){?>
	<!-- <div class="msg-warning"><?php echo $this->session->flashdata('warning');?></div> -->
	<script type="text/javascript">
		fn_warning_msg(noti_war_msg);
	</script>
<?php }

if($this->session->flashdata('error')){?>
	<!-- <div class="msg-error"><?php echo $this->session->flashdata('error');?></div> -->
	<script type="text/javascript">
		fn_error_msg(noti_err_msg);
	</script>
<?php }

if( $this->session->flashdata('info')){ ?>
	<!-- <div class="msg-info"><?php echo $this->session->flashdata('info');?></div> -->
	<script type="text/javascript">
		fn_warning_msg(noti_info_msg);
	</script>
<?php }

if(!validation_errors()){
	if(@$csrf_error!=''){?>
		<div class="msg-error"><?php echo $csrf_error;?></div>
	<?php } 
}?>

<?php if(validation_errors()){?>
	<div class="msg-error validation-error"><?php echo validation_errors();?></div>
<?php }?>
