<div class="section no-pad-bot" id="index-banner">
  <div class="container">
      <div class="row">
          <div class="col-md-10 col-md-offset-1">
            <div class="add-note">
              <a id="add-note" class="btn-floating btn-large waves-effect waves-light red img-plus pulse">
              <i class="material-icons" style="font-weight: bold;">add</i></a>
            </div>
            <?php if(empty($get_all_notes)){ ?>
              <div class="add-note-text">
                <i class="material-icons" style="font-size:100px !important;color: #ffca28;">note_add</i>
                <p class="text-note">Write note here</p>
              </div>
            <?php } ?>
              <div class="added-note">
                <?php foreach ($get_all_notes as $r) { ?>
                      <div class="main-wrap">
                          <div class="note-handle"></div> 
                          <div style="background:<?php echo $r->Color;?>" class="content-editable" id="data-<?php echo $r->TempNoteId ?>" contenteditable="true" style="">
                            <?php echo $r->Description; ?>
                          </div>
                          <div class="bs-example"> 
                              <div class="dropdown">
                                  <ul class="dropdown-menu note-color-drpdown"> 
                                    <?php foreach (json_decode(NOTE_COLOR_ARRAY) as $k=>$v) { ?>
                                      <li>
                                        <a href="#" id="<?php echo $v; ?>"><span class="<?php echo $k; ?>"></span></a>
                                      </li>
                                    <?php } ?>
                                  </ul> 
                              </div> 
                          </div> 
                          <a href="javascript:;" class="delete-note"><i class="material-icons deleteNote">delete</i></a> 
                      </div>
                <?php } ?>
              </div>
          </div>
      </div>
  </div>
</div>


