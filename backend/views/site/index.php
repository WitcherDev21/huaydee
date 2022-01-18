
<div class="row-fluid">
    <div class="widget-box">
    <div class="widget-title bg_lg"><span class="icon"><i class="icon-bar-chart"></i></span>
        <h5>ผลประกอบการวันที่ <?php echo date('d/m/Y'); ?></h5>
    </div>
    <div class="widget-content" >
        <div class="row-fluid">
        <div class="span12">
            <ul class="site-stats">
                <li class="bg_lb">
                    <i class="icon-user"></i> 
                    <strong>จำนวนสมาชิกทั้งหมด</strong>
                    <strong><?php echo (!empty($amountUser) ? number_format($amountUser) : '0'); ?> คน</strong>
                </li>
                <li class="bg_db">
                    <i class="icon-credit-card"></i>
                    <strong>ยอดฝากวันนี้</strong>
                    <strong><?php echo (!empty($amountPostIn->amount) ? number_format($amountPostIn->amount,2) : '0'); ?> ฿</strong>
                </li>
                <li class="bg_lg">
                    <i class="icon-user"></i>
                    <strong>สมาชิกที่ถูกระงับ</strong>
                    <strong><?php echo (!empty($amountUserWithhold) ? number_format($amountUserWithhold) : 0); ?> คน</strong>
                </li>
                <li class="bg_dg">
                    <i class="icon-credit-card"></i>
                    <strong>ยอดถอนวันนี้</strong>
                    <strong><?php echo (!empty($amountPostOut->amount) ? number_format($amountPostOut->amount,2) : 0); ?> ฿</strong>
                </li>
                
                <li class="bg_ly">
                    <i class="icon-repeat"></i>
                    <strong>จำนวนโพยยี่กี้วันนี้</strong>
                    <strong><?php echo (!empty($amountYeekeeChit) ? number_format($amountYeekeeChit) : 0); ?> โพย</strong>
                </li>

                <li class="bg_ly">
                    <i class="icon-repeat"></i>
                    <strong>จำนวนโพยหวยหุ้นวันนี้</strong>
                    <strong><?php echo (!empty($amountSharedChit) ? number_format($amountSharedChit) : 0); ?> โพย</strong>
                </li>

                <li class="bg_dy">
                    <i class="icon-credit-card"></i>
                    <strong>ยอดเครติดคงเหลือวันนี้</strong>
                    <strong><?php echo (!empty($amountCredit->balance) ? number_format($amountCredit->balance,2) : 0); ?> ฿</strong>
                </li>

                <li class="bg_dg">
                    <i class="icon-credit-card"></i>
                    <strong>จำนวนยอดฝากตรงวันนี้</strong>
                    <strong><?php echo (!empty($amountCreditTopUpPromotion->amount) ? number_format($amountCreditTopUpPromotion->amount,2) : 0); ?> ฿</strong>
                </li>

                <li class="bg_dg">
                    <i class="icon-credit-card"></i>
                    <strong>จำนวนยอดถอนตรงวันนี้</strong>
                    <strong><?php echo (!empty($amountCreditWithDrawPromotion->amount) ? number_format($amountCreditWithDrawPromotion->amount,2) : 0); ?> ฿</strong>
                </li>

                <li class="bg_dg">
                    <i class="icon-credit-card"></i>
                    <strong>จำนวนผู้ฝากเงินวันนี้</strong>
                    <strong><?php echo $countAmountToday ?></strong>
                </li>
            </ul>
        </div>
            
            
            
<!--            <div class="span12">
                <ul class="site-stats">-->
<!--                    <li class="bg_lh"><i class="icon-user"></i> <strong><?php // echo (!empty($amountYeekeeChit->total_amount) ? number_format($amountYeekeeChit->total_amount,2) : 0); ?> บาท</strong> <small>ยอดแทงทั้งหมด</small></li>
                    <li class="bg_lh"><i class="icon-plus"></i> <strong><?php // echo (!empty($amountCreditTransection->amount) ? number_format($amountCreditTransection->amount,2) : 0); ?> บาท</strong> <small>ยอดถอนทั้งหมด</small></li>
                    <li class="bg_lh"><i class="icon-shopping-cart"></i> <strong><?php // echo (!empty($amountCredit->balance) ? number_format($amountCredit->balance,2) : 0); ?> บาท</strong> <small>ยอดคงเหลือของเงินในกระเป๋า</small></li>-->
<!--                <li class="bg_lh"><i class="icon-tag"></i> <strong>9540</strong> <small>ยอดแทงวันนี้</small></li>
                <li class="bg_lh"><i class="icon-repeat"></i> <strong>2,000</strong> <small>รอถอนเงิน</small></li>
                <li class="bg_lh"><i class="icon-globe"></i> <strong>540</strong> <small>สมาชิกที่กำลังใช้งาน</small></li>-->
<!--                </ul>
            </div>-->
        </div>
    </div>
    </div>
</div>


<script type="text/javascript">
  // This function is called from the pop-up menus to transfer to
  // a different page. Ignore if the value returned is a null string:
  function goPage (newURL) {

      // if url is empty, skip the menu dividers and reset the menu selection to default
      if (newURL != "") {
      
          // if url is "-", it is this page -- reset the menu:
          if (newURL == "-" ) {
              resetMenu();            
          } 
          // else, send page to designated URL            
          else {  
            document.location.href = newURL;
          }
      }
  }

// resets the menu selection upon entry to this page:
function resetMenu() {
   document.gomenu.selector.selectedIndex = 2;
}
</script>