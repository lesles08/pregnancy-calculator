<?php
/**
 * Plugin Name: APA Pregnancy Calculator
 * Plugin URI: http://lesliew.com
 * Description: APA Pregnancy Calculator
 * Author: Leslie W
 * Author URI: http://lesliew.com
 * Version: 0.1.0
 */

function add_my_plugin_stylesheet() {
  //https://kenwheeler.github.io/slick/
  wp_register_style('slickstyles', '/wp-content/plugins/apa-pregnancy-calculator/slick/slick.css');
  wp_register_style('slicktheme', '/wp-content/plugins/apa-pregnancy-calculator/slick/slick-theme.css');
  wp_register_style('mypluginstylesheet', '/wp-content/plugins/apa-pregnancy-calculator/style.css');
  wp_enqueue_script( 'jquery-migrate', 'https://code.jquery.com/jquery-migrate-1.2.1.min.js', array( 'jquery' ) );
  wp_enqueue_script( 'slick-script', '/wp-content/plugins/apa-pregnancy-calculator/slick/slick.min.js', array( 'jquery-migrate' ) );
  wp_enqueue_style('slickstyles');
  wp_enqueue_style('slicktheme');
  wp_enqueue_style('mypluginstylesheet');
  wp_enqueue_script('slick-script');
}
add_action( 'wp_print_styles', 'add_my_plugin_stylesheet' );

function APA_Pregnancy_Calculator() {
  $dir = plugins_url();
  $slide = 0;

  if($_GET['menstrual']) {
    $menstrual = $_GET['menstrual'];
  }
  if($_GET['conception']) {
    $conception = $_GET['conception'];
  }
  if($_GET['duedate']) {
    $duedate = $_GET['duedate'];
  }
  if($_GET['cycle']) {
    $cycle = $_GET['cycle'];
    $cycle = number_format($cycle);
  }
  if($duedate) {
    $ov_date = 14;
    $due_date = strtotime($duedate);
    $conception = date('Y-m-d', strtotime('-266 day', strtotime($duedate)));
    //$conception = strtotime($due_date,'-266 days');
    $menstrual_stamp = strtotime($conception,'-'.$ov_date.' days');
  }
  if($conception) {
    $ov_date = 14;
    $conception = strtotime($conception);
    $menstrual_stamp = strtotime('-'.$ov_date.' days', $conception);
    $due_date = strtotime('+266 days', $conception);
  }
  if($cycle) {
    $ov_date = $cycle-14;
    $menstrual_stamp = strtotime($menstrual);
    $fert_start = strtotime('+8 days', $menstrual_stamp);
    $fert_end = strtotime('+18 days', $menstrual_stamp);
    $conception = strtotime('+'.$ov_date.' days', $menstrual_stamp);
    $due_date = strtotime('+266 days', $conception);
  }
  if($menstrual || $conception || $due_date) {
  $mens_month = substr($menstrual, 5, 2);
  $mens_day = substr($menstrual, 8, 2);
  $mens_year = substr($menstrual, 0, 4);
  //$now_stamp = strtotime("now");
  $now_stamp = current_time('U');
  $days_preg = ($now_stamp - $conception)/86400;
  $full_days = ($now_stamp - $menstrual_stamp)/86400;
  $weeks_preg = floor($full_days/7);
  $slide = $weeks_preg-2;
  $percent = ceil(($full_days/280)*100);
  $mos_preg = floor($weeks_preg/4);
  $wks_leftover_preg = $weeks_preg%4;
  $leftover_days = $full_days%7;
  
  $html = '

  <div class="pregcalc">
  <div class="cordcare top"><a href="https://americanpregnancy.org/corp-sponsors/affordable-cord-care/">This page presented by <span class="image"></span></a></div>

    <h2 class="darkgray upper">Your baby is due:</h2>
    
    <p class="calendar"><span class="month satisfy white">'.date('M', $due_date).'</span><span class="day satisfy blue">'.date('d', $due_date).'</span><!--<span class="year satisfy">'.date('Y', $due_date).'</span>--></p>
    
    <div class="black satisfy currentwk">You are currently</div>
    <div class="purple weeks">'.$weeks_preg.'</div>
    <div class="upper pink wkspreg bebas">weeks pregnant</div>
    <p class="conception pink">Estimated Date of Conception: '.date('M d, Y', $conception).'</p>
    
    <p class="purple wksdays">('.$weeks_preg.' weeks '.$leftover_days.' days or '.$mos_preg.' months)</p>

    <!--<p><a class="darkgray ubuntu" href="#week'.$weeks_preg.'">Jump to details about week '.$weeks_preg.' ></a></p>-->
    <small><i>This is based on the cycle length provided, not an average cycle length of 28 days, however it is still an estimate.</i></small>
    <div class="divider"></div>

    <div class="pink upper bebas progress">Progress</div>

    <p class="black satisfy percent">You are '.$percent.'% of the way through your pregnancy.</p>
    <p class="progress" style="display: inline-block;border: 1px solid #000;width: 200px;padding-left: 5px;height: 40px;background: -webkit-linear-gradient(left, rgb(220, 107, 136) '.$percent.'%, transparent 0%);
    background: -moz-linear-gradient(left, rgb(220, 107, 136) '.$percent.'%, transparent 0%);
    background: -o-linear-gradient(left, rgb(220, 107, 136) '.$percent.'%, transparent 0%);
    background: -ms-linear-gradient(left, rgb(220, 107, 136) '.$percent.'%, transparent 0%);
    background: linear-gradient(left, rgb(220, 107, 136) '.$percent.'%, transparent 0%);">'.$percent.'%<span class="heart"></span></p>

    <div class="divider"></div>

    <h3 class="black satisfy develop">Weekly Development</h3>

    <div id="slides">
        

          <div id="week1" class="weekly';
          if($weeks_preg == 1 || $weeks_preg == 2) {
            $html .= ' current';
          }
          $html .='">
            <p class="weektitle">Week 1 & 2</td>
            <p class="date purple ubuntu">Weeks 1 & 2 of your pregnancy is '.date('M d, Y', strtotime('+1 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+14 days', $menstrual_stamp)).'</p>
            <p class="darkgray milestone">Baby Conceived</p>
            <p>It\'s ovulation time. If sperm and egg meet, you\'re on your way to pregnancy.</p>
            <p class="post"><a href="https://apadev.wpengine.com/week-by-week/1-and-2-weeks-pregnant/" class="pink">Read our full post on weeks 1 & 2 of pregnancy ></a></p>
          </div>

        
            <div id="week3" class="weekly';
            if($weeks_preg == 3) {
              $html .= ' current';
            }
            $html .='">
              <p class="weektitle">Week 3</p>
              <p class="date purple ubuntu">Week 3 of your pregnancy is '.date('M d, Y', strtotime('+15 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+21 days', $menstrual_stamp)).'</p>
              <p class="darkgray milestone">Implantation occurs</p>
              <p class="black text">Your baby is a tiny ball of several hundred cells that are rapidly multiplying and burrowing into the lining of your uterus. The cells that become the placenta are producing hCG, the pregnancy hormone. It tells your ovaries to stop releasing eggs and keep producing progesterone. Once there\'s enough hCG in your urine, you\'ll get a positive pregnancy test result.</p>
              <p class="post"><a href="https://apadev.wpengine.com/week-by-week/3-weeks-pregnant/" class="pink">Read our full post on week 3 of pregnancy ></a></p>
            </div>
        
            <div id="week4" class="weekly';
            if($weeks_preg == 4) {
              $html .= ' current';
            }
            $html .='">
              <p class="weektitle">Week 4</p>
              <p class="date purple ubuntu">Week 4 of your pregnancy is '.date('M d, Y', strtotime('+22 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+28 days', $menstrual_stamp)).'</p>
              <p class="darkgray milestone">Positive Pregnancy Test</p>
              <p class="black text">Your baby is an embryo made up of two layers, the hypoblast and the epiblast. The primative placenta is developing and preparing to provide nutrients and oxygen to your growing baby. The amniotic sac is developing and will surround and protect your baby while it continues to grow.</p>
              <p class="post"><a href="https://apadev.wpengine.com/week-by-week/4-weeks-pregnant/" class="pink">Read our full post on week 4 of pregnancy ></a></p>
            </div>
        
            <div id="week5" class="weekly';
            if($weeks_preg == 5) {
              $html .= ' current';
            }
            $html .='">
              <p class="weektitle">Week 5</p>
              <p class="date purple ubuntu">Week 5 of your pregnancy is '.date('M d, Y', strtotime('+29 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+35 days', $menstrual_stamp)).'</p>
              <p class="black text">Your embryo is now made up of three layers, the ectoderm, the mesoderm and the endoderm which will later form all the organs and tissues. You might start to feel the first twinges of pregnancy such as tender breasts, frequent urination, or morning sickness</p>
              <p class="post"><a href="https://apadev.wpengine.com/week-by-week/5-weeks-pregnant/" class="pink">Read our full post on week 5 of pregnancy ></a></p>
            </div>
        
            <div id="week6" class="weekly';
            if($weeks_preg == 6) {
              $html .= ' current';
            }
            $html .='">
              <p class="weektitle">Week 6</p>
              <p class="date purple ubuntu">Week 6 of your pregnancy is '.date('M d, Y', strtotime('+36 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+42 days', $menstrual_stamp)).'</p>
              <p class="darkgray milestone">Heartbeat detectable by ultrasound</p>
              <p class="black text">Your baby\'s heart is beating about 160 times a minute and the nose, mouth and ears are taking shape. Lungs and digestive system are forming organs.</p>
              <p class="post"><a href="https://apadev.wpengine.com/week-by-week/6-weeks-pregnant/" class="pink">Read our full post on week 6 of pregnancy ></a></p>
            </div>
        
            <div id="week7" class="weekly';
            if($weeks_preg == 7) {
              $html .= ' current';
            }
            $html .='">
              <p class="weektitle">Week 7</p>
              <p class="date purple ubuntu">Week 7 of your pregnancy is '.date('M d, Y', strtotime('+43 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+49 days', $menstrual_stamp)).'</p>
              <p class="black text">Your baby is forming hands and feet. Key organs like the stomach, liver and esophagus are beginning to form. Your uterus has doubled in size. The umbilical cord is transferring blood and waste between baby and mother.</p>
              <p class="post"><a href="https://apadev.wpengine.com/week-by-week/7-weeks-pregnant/" class="pink">Read our full post on week 7 of pregnancy ></a></p>
            </div>
        
            <div id="week8" class="weekly';
            if($weeks_preg == 8) {
              $html .= ' current';
            }
            $html .='">
              <p class="weektitle">Week 8</p>
              <p class="date purple ubuntu">Week 8 of your pregnancy is '.date('M d, Y', strtotime('+50 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+56 days', $menstrual_stamp)).'</p>
              <p class="black text">The respiratory system is forming now. Breathing tubes extend from the throat to the branches of the developing lungs.</p>
              <p class="post"><a href="https://apadev.wpengine.com/week-by-week/8-weeks-pregnant/" class="pink">Read our full post on week 8 of pregnancy ></a></p>
            </div>
        
            <div id="week9" class="weekly';
            if($weeks_preg == 9) {
              $html .= ' current';
            }
            $html .='">
              <p class="weektitle">Week 9</p>
              <p class="date purple ubuntu">Week 9 of your pregnancy is '.date('M d, Y', strtotime('+57 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+63 days', $menstrual_stamp)).'</p>
              <p class="black text">Your baby is nearly an inch long now. If you watch closely, you may see your baby move if you have an ultrasound done</p>
              <p class="post"><a href="https://apadev.wpengine.com/week-by-week/9-weeks-pregnant/" class="pink">Read our full post on week 9 of pregnancy ></a></p>
            </div>
        
            <div id="week10" class="weekly';
            if($weeks_preg == 10) {
              $html .= ' current';
            }
            $html .='">
              <p class="weektitle">Week 10</p>
              <p class="date purple ubuntu">Week 10 of your pregnancy is '.date('M d, Y', strtotime('+64 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+70 days', $menstrual_stamp)).'</p>
              <p class="black text">Your baby\'s organs are growing and beginning to mature. The baby\'s head comprises half the length of the body.</p>
              <p class="post"><a href="https://apadev.wpengine.com/week-by-week/10-weeks-pregnant/" class="pink">Read our full post on week 10 of pregnancy ></a></p>
            </div>
        
            <div id="week11" class="weekly';
            if($weeks_preg == 11) {
              $html .= ' current';
            }
            $html .='">
              <p class="weektitle">Week 11</p>
              <p class="date purple ubuntu">Week 11 of your pregnancy is '.date('M d, Y', strtotime('+71 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+77 days', $menstrual_stamp)).'</p>
              <p class="black text">Fingers and toes have separated and the bones are beginning to harden. External genitalia has almost completely formed.</p>
              <p class="post"><a href="https://apadev.wpengine.com/week-by-week/11-weeks-pregnant/" class="pink">Read our full post on week 11 of pregnancy ></a></p>
            </div>
        
            <div id="week12" class="weekly';
            if($weeks_preg == 12) {
              $html .= ' current';
            }
            $html .='">
              <p class="weektitle">Week 12</p>
              <p class="date purple ubuntu">Week 12 of your pregnancy is '.date('M d, Y', strtotime('+78 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+84 days', $menstrual_stamp)).'</p>
              <p class="black text">The kidneys can now secrete urine and the nervous system is maturing. You baby may be curling all 10 toes, practicing opening and closing fingers and sucking a thumb. And mom should have gained from 2-5 lbs.</p>
              <p class="post"><a href="https://apadev.wpengine.com/week-by-week/12-weeks-pregnant/" class="pink">Read our full post on week 12 of pregnancy ></a></p>
            </div>
            <div id="week13" class="weekly';
      if($weeks_preg == 13) {
        $html .= ' current';
      }
      $html .='">
        <p class="weektitle">Week 13</p>
        <p class="date purple ubuntu">Week 13 of your pregnancy is '.date('M d, Y', strtotime('+85 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+91 days', $menstrual_stamp)).'</p>
        <p class="milestone">Miscarrige risk decreases</p>
        <p class="black text">Your baby now has unique fingerprints and the kidney and urinary tract are completely functional, that means she\'s peeing. And if you are having a girl, her ovaries are already full of thousands of eggs.</p>
        <p class="post"><a href="https://apadev.wpengine.com/week-by-week/13-weeks-pregnant/" class="pink">Read our full post on week 13 of pregnancy ></a></p>
      </div>
      <div id="week14" class="weekly';
      if($weeks_preg == 14) {
        $html .= ' current';
      }
      $html .='">
        <p class="weektitle">Week 14</p>
        <p class="date purple ubuntu">Week 14 of your pregnancy is '.date('M d, Y', strtotime('+92 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+98 days', $menstrual_stamp)).'</p>
        <p class="black text">Your baby\'s facial muscles are getting a workout as he squints, frowns, grimaces and practices his first smile for you.</p>
        <p class="post"><a href="https://apadev.wpengine.com/week-by-week/14-weeks-pregnant/" class="pink">Read our full post on week 14 of pregnancy ></a></p>
      </div>
      <div id="week15" class="weekly';
      if($weeks_preg == 15) {
        $html .= ' current';
      }
      $html .='">
        <p class="weektitle">Week 15</p>
        <p class="date purple ubuntu">Week 15 of your pregnancy is '.date('M d, Y', strtotime('+99 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+105 days', $menstrual_stamp)).'</p>
        <p class="black text">Your baby is looking more like a baby with legs growing longer than the arms and all her limbs moving. The ears are properly positioned on the side of her head and the eyes are moving from the side of the head to the front of the face.At your doctor\'s visit, he should offer you a quad screening test to check for Down\'s syndrome or other chromosomal abnormalities.</p><p class="post"><a href="https://apadev.wpengine.com/week-by-week/15-weeks-pregnant/" class="pink">Read our full post on week 15 of pregnancy ></a></p>
      </div>
      <div id="week16" class="weekly';
      if($weeks_preg == 16) {
        $html .= ' current';
      }
      $html .='">
        <p class="weektitle">Week 16</p>
        <p class="date purple ubuntu">Week 16 of your pregnancy is '.date('M d, Y', strtotime('+106 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+112 days', $menstrual_stamp)).'</p>
        <p class="black text">Baby\'s heart is pumping about 25 quarts of blood each day. His eyes are working and moving side to side even though the eyelids are still sealed. Mom will have a "pregnancy glow" due to increased blood supply.</p>
        <p class="post"><a href="https://apadev.wpengine.com/week-by-week/16-weeks-pregnant/" class="pink">Read our full post on week 16 of pregnancy ></a></p>
      </div>
      <div id="week17" class="weekly';
      if($weeks_preg == 17) {
        $html .= ' current';
      }
      $html .='">
        <p class="weektitle">Week 17</p>
        <p class="date purple ubuntu">Week 17 of your pregnancy is '.date('M d, Y', strtotime('+113 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+119 days', $menstrual_stamp)).'</p>
        <p class="black text">Baby\'s skeleton is changing from soft cartilage to bone and her heart is now regulated by her brain. She\'s practicing her sucking and swallowing skills in preparation for that first suckle at your breast or the bottle. Mom\'s breasts may have increased a full bra size.</p>
        <p class="post"><a href="https://apadev.wpengine.com/week-by-week/17-weeks-pregnant/" class="pink">Read our full post on week 17 of pregnancy ></a></p>
      </div>
      <div id="week18" class="weekly';
      if($weeks_preg == 18) {
        $html .= ' current';
      }
      $html .='">
        <p class="weektitle">Week 18</p>
        <p class="date purple ubuntu">Week 18 of your pregnancy is '.date('M d, Y', strtotime('+120 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+126 days', $menstrual_stamp)).'</p>
        <p class="milestone">Gender reveal time</p>
        <p class="black text">If you\'re having a girl, her uterus and fallopian tubes are formed and in place. If you\'re having a boy, his genitals are noticable now but he may hide them during an ultrasound. Are you feeling kickes and punches? Baby\'s hearing is also developing, so you may want to start talking to your baby.</p>
        <p class="post"><a href="https://apadev.wpengine.com/week-by-week/18-weeks-pregnant/" class="pink">Read our full post on week 18 of pregnancy ></a></p>
      </div>
      <div id="week19" class="weekly';
      if($weeks_preg == 19) {
        $html .= ' current';
      }
      $html .='">
        <p class="weektitle">Week 19</p>
        <p class="date purple ubuntu">Week 19 of your pregnancy is '.date('M d, Y', strtotime('+127 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+133 days', $menstrual_stamp)).'</p>
        <p class="black text">Baby\'s brain is designating specialized areas for his 5 senses - vision, hearing, taste, smell and touch. A waxy protective coating called the vernix caseosa is forming on his skin to prevent wrinkling.</p>
        <p class="post"><a href="https://apadev.wpengine.com/week-by-week/19-weeks-pregnant/" class="pink">Read our full post on week 19 of pregnancy ></a></p>
      </div>
      <div id="week20" class="weekly';
      if($weeks_preg == 20) {
        $html .= ' current';
      }
      $html .='">
        <p class="weektitle">Week 20</p>
        <p class="date purple ubuntu">Week 20 of your pregnancy is '.date('M d, Y', strtotime('+134 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+140 days', $menstrual_stamp)).'</p>
        <p class="black text">Your baby weighs about 10 ounces and is the size of a small banana.  Her uterus is fully formed this week and she may have tiny primitive eggs in tiny ovaries now. His testicles are waiting for the scrotum to finish growing and will begin their descent soon. Mom can expect to gain about 1/2 lb per week for the rest of her pregnancy.</p>
        <p class="post"><a href="https://apadev.wpengine.com/week-by-week/20-weeks-pregnant/" class="pink">Read our full post on week 20 of pregnancy ></a></p>
      </div>
      <div id="week21" class="weekly';
      if($weeks_preg == 21) {
        $html .= ' current';
      }
      $html .='">
        <p class="weektitle">Week 21</p>
        <p class="date purple ubuntu">Week 21 of your pregnancy is '.date('M d, Y', strtotime('+141 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+147 days', $menstrual_stamp)).'</p>
        <p class="black text">Feel all that moving and shaking going on! Baby\'s arms and legs are in proportion now and movements are much more coordinated. Bone marrow is now helping the liver and spleen produce blood cells. The intestines are starting to produce meconium, the thick tarry looking stool first seen in baby\'s diaper.</p>
        <p class="post"><a href="https://apadev.wpengine.com/week-by-week/21-weeks-pregnant/" class="pink">Read our full post on week 21 of pregnancy ></a></p>
      </div>
      <div id="week22" class="weekly';
      if($weeks_preg == 22) {
        $html .= ' current';
      }
      $html .='">
        <p class="weektitle">Week 22</p>
        <p class="date purple ubuntu">Week 22 of your pregnancy is '.date('M d, Y', strtotime('+148 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+154 days', $menstrual_stamp)).'</p>
        <p class="black text">Senses are growing stronger. Now she can hear your heart beat, your breathing and digestion. Sense of sight is becoming more fine-tuned and he can preceive light and dark. Hormones are now developing which will the organs the commands they need to operate.</p>
        <p class="post"><a href="https://apadev.wpengine.com/week-by-week/22-weeks-pregnant/" class="pink">Read our full post on week 22 of pregnancy ></a></p>
      </div>
      <div id="week23" class="weekly';
      if($weeks_preg == 23) {
        $html .= ' current';
      }
      $html .='">
        <p class="weektitle">Week 23</p>
        <p class="date purple ubuntu">Week 23 of your pregnancy is '.date('M d, Y', strtotime('+155 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+161 days', $menstrual_stamp)).'</p>
        <p class="milestone">Premature baby may survive</p>
        <p class="black text">Baby\'s organs and bones are visible through his skin, which has a red hue because of the developing veins and arteries beneath. He\'ll become less transparent as his fat deposits fill in. Baby is also developing surfactant which will help the lungs inflate if baby is born prematurely</p>
        <p class="post"><a href="https://apadev.wpengine.com/week-by-week/23-weeks-pregnant/" class="pink">Read our full post on week 23 of pregnancy ></a></p>
      </div>
      <div id="week24" class="weekly';
      if($weeks_preg == 24) {
        $html .= ' current';
      }
      $html .='">
        <p class="weektitle">Week 24</p>
        <p class="date purple ubuntu">Week 24 of your pregnancy is '.date('M d, Y', strtotime('+162 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+168 days', $menstrual_stamp)).'</p>
        <p class="black text">Baby\'s face is almost fully formed complete with eyelashes, eyebrows and hair. Right now her hair is white because there\'s no pigment yet. Between now and 28 weeks, the doctor should send mom for a glucose screening test for gestational diabetes.</p>
        <p class="post"><a href="https://apadev.wpengine.com/week-by-week/24-weeks-pregnant/" class="pink">Read our full post on week 24 of pregnancy ></a></p>
      </div>
      <div id="week25" class="weekly';
      if($weeks_preg == 25) {
        $html .= ' current';
      }
      $html .='">
        <p class="weektitle">Week 25</p>
        <p class="date purple ubuntu">Week 25 of your pregnancy is '.date('M d, Y', strtotime('+169 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+175 days', $menstrual_stamp)).'</p>
        <p class="black text">Baby is gaining more fat and looking more like a newborn. Hair color and texture is in place. His lungs are maturing and preparing for that first breath. You might feel the baby having hiccups.</p>
        <p class="post"><a href="https://apadev.wpengine.com/week-by-week/25-weeks-pregnant/" class="pink">Read our full post on week 25 of pregnancy ></a></p>
      </div>
      <div id="week26" class="weekly';
      if($weeks_preg == 26) {
        $html .= ' current';
      }
      $html .='">
        <p class="weektitle">Week 26</p>
        <p class="date purple ubuntu">Week 26 of your pregnancy is '.date('M d, Y', strtotime('+176 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+182 days', $menstrual_stamp)).'</p>
        <p class="black text">Brain-wave activity is on high That means baby can hear noises and respond to them with an increase pulse rate or movement. Eyes are beginning to open but they don\'t have much pigmentation. That will develop over the next couple months and may even continue to change until she\'s about 6-months-old.</p>
        <p class="post"><a href="https://apadev.wpengine.com/week-by-week/26-weeks-pregnant/" class="pink">Read our full post on week 26 of pregnancy ></a></p>
      </div>
      <div id="week27" class="weekly';
      if($weeks_preg == 27) {
        $html .= ' current';
      }
      $html .='">
        <p class="weektitle">Week 27</p>
        <p class="date purple ubuntu">Week 27 of your pregnancy is '.date('M d, Y', strtotime('+183 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+189 days', $menstrual_stamp)).'</p>
        <p class="milestone">Start talking to your baby</p>
        <p class="black text">Baby may recognize both your and your partner\'s voices. This is the time to read and even sing to your baby. She now has taste buds so when you eat spicy food, your baby will be able to taste the difference in the amniotic fluid. Her mealtime comes about two hours after  yours. Feel some belly spasms? Those are likely hiccups from that spicy food. It doesn\'t bother the baby as much as you. Baby also has settled in to a regular sleep cycle, but it may be different from mom\'s.</p>
        <p class="post"><a href="https://apadev.wpengine.com/week-by-week/27-weeks-pregnant/" class="pink">Read our full post on week 27 of pregnancy ></a></p>
      </div>
      <div id="week28" class="weekly';
      if($weeks_preg == 28) {
        $html .= ' current';
      }
      $html .='">
        <p class="weektitle">Week 28</p>
        <p class="date purple ubuntu">Week 28 of your pregnancy is '.date('M d, Y', strtotime('+190 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+196 days', $menstrual_stamp)).'</p>
        <p class="black text">During the third trimester the brain triples in weight adding billions of new nerve cells. Senses of hearing, smell and touch are developed and functional. Your baby is having different sleep cycles, including rapid eye movement. That means she\'s dreaming.</p>
        <p class="post"><a href="https://apadev.wpengine.com/week-by-week/28-weeks-pregnant/" class="pink">Read our full post on week 28 of pregnancy ></a></p>
      </div>
      <div id="week29" class="weekly';
      if($weeks_preg == 29) {
        $html .= ' current';
      }
      $html .='">
        <p class="weektitle">Week 29</p>
        <p class="date purple ubuntu">Week 29 of your pregnancy is '.date('M d, Y', strtotime('+197 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+203 days', $menstrual_stamp)).'</p>
        <p class="milestone">Baby can breathe</p>
        <p class="black text">Baby\'s bones are soaking up lots of calcium as they harden so be sure to consume good sources of calcium. We recommend taking Nordic Naturals and Fairhaven Health supplements.</p>
        <p class="post"><a href="https://apadev.wpengine.com/week-by-week/29-weeks-pregnant/" class="pink">Read our full post on week 29 of pregnancy ></a></p>
      </div>
      <div id="week30" class="weekly';
      if($weeks_preg == 30) {
        $html .= ' current';
      }
      $html .='">
        <p class="weektitle">Week 30</p>
        <p class="date purple ubuntu">Week 30 of your pregnancy is '.date('M d, Y', strtotime('+204 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+210 days', $menstrual_stamp)).'</p>
        <p class="black text">Baby\'s brain is taking on characteristic grooves and indentations to allow for an increased amount of brain tissue. Bone marrow has taken over the production of red blood cells. This means she\'ll be better able to thrive on her own when she\'s born Baby is now weighing about 3 lbs and is 11 inches.</p>
        <p class="post"><a href="https://apadev.wpengine.com/week-by-week/30-weeks-pregnant/" class="pink">Read our full post on week 30 of pregnancy ></a></p>
      </div>
      <div id="week31" class="weekly';
      if($weeks_preg == 31) {
        $html .= ' current';
      }
      $html .='">
        <p class="weektitle">Week 31</p>
        <p class="date purple ubuntu">Week 31 of your pregnancy is '.date('M d, Y', strtotime('+211 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+217 days', $menstrual_stamp)).'</p>
        <p class="black text">Baby\'s brain is developing faster than ever and he\'s processing information, tracking light and perceiving signals from all five sense. She\'s probably moving a lot, especially  at night when you\'re trying to sleep. Take comfort that all this activity means your baby is healthy. Mom may start feeling some Braxton Hicks contractions.</p>
        <p class="post"><a href="https://apadev.wpengine.com/week-by-week/31-weeks-pregnant/" class="pink">Read our full post on week 31 of pregnancy ></a></p>
      </div>
      <div id="week32" class="weekly';
      if($weeks_preg == 32) {
        $html .= ' current';
      }
      $html .='">
        <p class="weektitle">Week 32</p>
        <p class="date purple ubuntu">Week 32 of your pregnancy is '.date('M d, Y', strtotime('+218 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+224 days', $menstrual_stamp)).'</p>
        <p class="black text">Baby can focus on large objects not too far away; toenails and fingernails have grown in along with real hair. He\'s practicing swallowing, breathing, kicking and sucking. All key skills for thriving after birth.</p>
        <p class="post"><a href="https://apadev.wpengine.com/week-by-week/32-weeks-pregnant/" class="pink">Read our full post on week 32 of pregnancy ></a></p>
      </div>
      <div id="week33" class="weekly';
      if($weeks_preg == 33) {
        $html .= ' current';
      }
      $html .='">
        <p class="weektitle">Week 33</p>
        <p class="date purple ubuntu">Week 33 of your pregnancy is '.date('M d, Y', strtotime('+225 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+231 days', $menstrual_stamp)).'</p>
        <p class="milestone">Immune system is maturing</p>
        <p class="black text">The bones in your baby\'s skull are still pliable which makes it easier for her to fit through the birth canal. Your uterine walls are becoming thinner allowing more light to penetrate your womb. That helps baby differentiate between night and day. </p>
        <p class="post"><a href="https://apadev.wpengine.com/week-by-week/33-weeks-pregnant/" class="pink">Read our full post on week 33 of pregnancy ></a></p>
      </div>
      <div id="week34" class="weekly';
      if($weeks_preg == 34) {
        $html .= ' current';
      }
      $html .='">
        <p class="weektitle">Week 34</p>
        <p class="date purple ubuntu">Week 34 of your pregnancy is '.date('M d, Y', strtotime('+232 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+238 days', $menstrual_stamp)).'</p>
        <p class="black text">Baby\'s fat layers are filling her out and will help regulate body temperture when she\'s born. If your baby is a boy, the testicles are making their way down from the abdomen to the scrotum.</p>
        <p class="post"><a href="https://apadev.wpengine.com/week-by-week/34-weeks-pregnant/" class="pink">Read our full post on week 34 of pregnancy ></a></p>
      </div>
      <div id="week35" class="weekly';
      if($weeks_preg == 35) {
        $html .= ' current';
      }
      $html .='">
        <p class="weektitle">Week 35</p>
        <p class="date purple ubuntu">Week 35 of your pregnancy is '.date('M d, Y', strtotime('+239 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+245 days', $menstrual_stamp)).'</p>
        <p class="black text">Kidneys are fully developed and her liver can process some waste products. Most of her physical development is complete. She\'ll spend the next few weeks gaining weight and adding baby fat. Baby is settling lower into the pelvis preparing for delivery and this is called "lightening".</p>
        <p class="post"><a href="https://apadev.wpengine.com/week-by-week/35-weeks-pregnant/" class="pink">Read our full post on week 35 of pregnancy ></a></p>
      </div>
      <div id="week36" class="weekly';
      if($weeks_preg == 36) {
        $html .= ' current';
      }
      $html .='">
        <p class="weektitle">Week 36</p>
        <p class="date purple ubuntu">Week 36 of your pregnancy is '.date('M d, Y', strtotime('+246 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+252 days', $menstrual_stamp)).'</p>
        <p class="black text">Hopefully your baby is in a head-down position. If not, your practitioner may suggest an external cephalic version to manipulate your baby into a head down position. The vernix caseosa has now disappeared.</p>
        <p class="post"><a href="https://apadev.wpengine.com/week-by-week/36-weeks-pregnant/" class="pink">Read our full post on week 36 of pregnancy ></a></p>
      </div>
      <div id="week37" class="weekly';
      if($weeks_preg == 37) {
        $html .= ' current';
      }
      $html .='">
        <p class="weektitle">Week 37</p>
        <p class="date purple ubuntu">Week 37 of your pregnancy is '.date('M d, Y', strtotime('+253 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+259 days', $menstrual_stamp)).'</p>
        <p class="milestone">Baby is considered full term</p>
        <p class="black text">Baby is taking up most of the room in your womb so he\'s only kicking and poking you, no more somersaults. Baby is sucking her thumb, blinking eyes and inhaling and exhaling amniotic fluid.</p>
        <p class="post"><a href="https://apadev.wpengine.com/week-by-week/37-weeks-pregnant/" class="pink">Read our full post on week 37 of pregnancy ></a></p>
      </div>
      <div id="week38" class="weekly';
      if($weeks_preg == 38) {
        $html .= ' current';
      }
      $html .='">
        <p class="weektitle">Week 38</p>
        <p class="date purple ubuntu">Week 38 of your pregnancy is '.date('M d, Y', strtotime('+260 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+266 days', $menstrual_stamp)).'</p>
        <p class="black text">Baby\'s eyes right now are blue, gray or brown but once they\'re exposed to light they may change color or a shade. The lanugo, the fine downy hair that covered his body for warmth is falling off in preparation for delivery. Her lungs have strenthened and her vocal cords have developed. That means she\'s ready for her first cry.</p>
        <p class="post"><a href="https://apadev.wpengine.com/week-by-week/38-weeks-pregnant/" class="pink">Read our full post on week 38 of pregnancy ></a></p>
      </div>
      <div id="week39" class="weekly';
      if($weeks_preg == 39) {
        $html .= ' current';
      }
      $html .='">
        <p class="weektitle">Week 39</p>
        <p class="date purple ubuntu">Week 39 of your pregnancy is '.date('M d, Y', strtotime('+267 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+273 days', $menstrual_stamp)).'</p>
        <p class="black text">Baby is ready to make his debut. He\'s adding more fat as his pinkish skin turns white or white-grayish. He won\'t have his final pigment until shortly after birth.</p>
        <p class="post"><a href="https://apadev.wpengine.com/week-by-week/39-weeks-pregnant/" class="pink">Read our full post on week 39 of pregnancy ></a></p>
      </div>
      <div id="week40" class="weekly';
      if($weeks_preg == 40) {
        $html .= ' current';
      }
      $html .='">
        <p class="weektitle">Week 40</p>
        <p class="date purple ubuntu">Week 40 of your pregnancy is '.date('M d, Y', strtotime('+274 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+280 days', $menstrual_stamp)).'</p>
        <p class="black text">This is the official end of your pregnancy but because due dates are just a calculation he might be "late." No need to worry, your body knows the right time to go into labor, or your doctor may suggest you be induced. At birth your baby\'s eye sight is a little blurry since central vision is still developing. Just say hello and he\'ll recognize your voice.</p>
        <p class="post"><a href="https://apadev.wpengine.com/week-by-week/40-weeks-pregnant/" class="pink">Read our full post on week 40 of pregnancy ></a></p>
      </div>
          
      </div>
      <!-- Begin Mailchimp Signup Form -->
<div id="mc_embed_signup">
<form id="mc-embedded-subscribe-form" class="validate" action="https://americanpregnancy.us20.list-manage.com/subscribe/post?u=922cc1f79b572d57ba26f37ff&amp;id=51f5c092c1" method="post" name="mc-embedded-subscribe-form" novalidate="" target="_blank">
<div id="mc_embed_signup_scroll">
<h2>Sign up for week by week emails</h2>
<div class="mc-field-group"><label for="mce-EMAIL">Email Address </label> <input id="mce-EMAIL" class="required email" name="EMAIL" type="email" value="" /></div>
<div class="mc-field-group"><label for="mce-group[4055]">What week is your pregnancy in? </label><select id="mce-group[4055]" class="REQ_CSS" name="group[4055]"><option value=""></option><option value="1">Week 1-2</option><option value="2">Week 3</option><option value="4">Week 4</option><option value="8">Week 5</option><option value="16">Week 6</option><option value="32">Week 7</option><option value="64">Week 8</option><option value="128">Week 9</option><option value="256">Week 10</option><option value="512">Week 11</option><option value="1024">Week 12</option><option value="2048">Week 13</option><option value="4096">Week 14</option><option value="8192">Week 15</option><option value="16384">Week 16</option><option value="32768">Week 17</option><option value="65536">Week 18</option><option value="131072">Week 19</option><option value="262144">Week 20</option><option value="524288">Week 21</option><option value="1048576">Week 22</option><option value="2097152">Week 23</option><option value="4194304">Week 24</option><option value="8388608">Week 25</option><option value="16777216">Week 26</option><option value="33554432">Week 27</option><option value="67108864">Week 28</option><option value="134217728">Week 29</option><option value="268435456">Week 30</option><option value="536870912">Week 31</option><option value="1073741824">Week 32</option><option value="2147483648">Week 33</option><option value="4294967296">Week 34</option><option value="8589934592">Week 35</option><option value="17179869184">Week 36</option><option value="34359738368">Week 37</option><option value="68719476736">Week 38</option><option value="137438953472">Week 39</option><option value="274877906944">Week 40</option></select></div>
<div id="mce-responses" class="clear">
<div id="mce-error-response" class="response" style="display: none;"> </div>
<div id="mce-success-response" class="response" style="display: none;"> </div>
</div>
<p><!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups--></p>
<div style="position: absolute; left: -5000px;" aria-hidden="true"><input tabindex="-1" name="b_922cc1f79b572d57ba26f37ff_51f5c092c1" type="text" value="" /></div>
<div class="clear"><input id="mc-embedded-subscribe" class="button" name="subscribe" type="submit" value="Subscribe" /></div>
</div>
</form>
</div>
<script type="text/javascript" src="//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js"></script> <script type="text/javascript">// <![CDATA[ (function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]="EMAIL";ftypes[0]="email";fnames[1]="FNAME";ftypes[1]="text";fnames[2]="LNAME";ftypes[2]="text";fnames[3]="ADDRESS";ftypes[3]="address";fnames[4]="PHONE";ftypes[4]="phone";}(jQuery));var $mcj = jQuery.noConflict(true); // ]]></script>
<!--End mc_embed_signup-->

      
      <h3 class="black satisfy develop">Pregnancy Milestones</h3>
      <div class="divider short"></div>
      <div class="first">
        <div class="trimester_num pink">1st</div>
        <div class="pink ubuntu tri_word">Trimester</div>
        <p class="tri_text">After fertilization and implantation, your baby is just an embryo, that is a set of cells from which all organs and body parts will develop. It will be exciting to see how fast your baby grows.</p>
        <div class="tri_milestones">Milestones</div>
          <div class="icon conceive"></div>
          <div class="ms_full">
            <div class="ms_weeks purple"><span class="pink">Weeks 1&2</span> | '.date('M d, Y', strtotime('+1 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+14 days', $menstrual_stamp)).'</div><div class="darkgray milestone">Baby Conceived</div>
          </div>
          <div class="clearfix"></div>
          <div class="icon heart-belly"></div>
          <div class="ms_full">
            <div class="ms_weeks purple"><span class="pink">Week 3</span> | '.date('M d, Y', strtotime('+15 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+21 days', $menstrual_stamp)).'</div><div class="darkgray milestone">Implantation occurs</div>
          </div>
          <div class="clearfix"></div>
          <div class="icon preg-test"></div>
          <div class="ms_full">
            <div class="ms_weeks purple"><span class="pink">Week 4</span> | '.date('M d, Y', strtotime('+22 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+28 days', $menstrual_stamp)).'</div><div class="darkgray milestone">Positive Pregnancy Test</div>
          </div>
          <div class="clearfix"></div>
          <div class="icon heartbeat"></div>
          <div class="ms_full">
            <div class="ms_weeks purple"><span class="pink">Week 6</span> | '.date('M d, Y', strtotime('+36 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+42 days', $menstrual_stamp)).'</div><div class="darkgray milestone">Heartbeat detectable by ultrasound</div>
          </div>
          <div class="clearfix"></div>
      </div>

      <div class="divider gray"></div>
      <div class="second">
        <div class="trimester_num pink">2nd</div>
        <div class="pink ubuntu tri_word">Trimester</div>
        <p class="tri_text">At the beginning of the second trimester, your baby is about 3 1/2 inches long and weigh about 1 1/2 ounces. Tiny, unique fingerprints are now in place, and the heart pumps 25 quarts of blood a day. As the weeks go by, your baby\'s skeleton starts to harden from rubbery cartilage to bone, and he or she develops the ability to hear. You\'re likely to feel kicks and flutters soon if you haven\'t already.</p>
        <div class="tri_milestones">Milestones</div>
        <div class="icon mis"></div>
          <div class="ms_full">
            <div class="ms_weeks purple"><span class="pink">Week 13</span> | '.date('M d, Y', strtotime('+85 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+91 days', $menstrual_stamp)).'</div><div class="darkgray milestone">Miscarrige risk decreases</div>
          </div>
          <div class="clearfix"></div>
          <div class="icon gender"></div>
          <div class="ms_full">
            <div class="ms_weeks purple"><span class="pink">Week 18</span> | '.date('M d, Y', strtotime('120 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+126 days', $menstrual_stamp)).'</div><div class="darkgray milestone">Gender reveal time</div>
          </div>
          <div class="clearfix"></div>
          <div class="icon premature"></div>
          <div class="ms_full">
            <div class="ms_weeks purple"><span class="pink">Week 23</span> | '.date('M d, Y', strtotime('155 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+161 days', $menstrual_stamp)).'</div><div class="darkgray milestone">Premature baby may survive</div>
          </div>
          <div class="clearfix"></div>
      </div>
      <div class="divider gray"></div>
      <div class="third">
      <div class="trimester_num pink">3rd</div>
        <div class="pink ubuntu tri_word">Trimester</div>
        <p class="tri_text">By the end of the second trimester, your baby is about 14-1/2 inches and weighing around 2 pounds.</p>
        <div class="tri_milestones">Milestones</div>
        <div class="icon talk"></div>
        <div class="ms_full">
          <div class="ms_weeks purple"><span class="pink">Week 27</span> | '.date('M d, Y', strtotime('183 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+189 days', $menstrual_stamp)).'</div><div class="darkgray milestone">Start talking to your baby</div>
        </div>
        <div class="clearfix"></div>
        <div class="icon breathe"></div>
        <div class="ms_full">
          <div class="ms_weeks purple"><span class="pink">Week 29</span> | '.date('M d, Y', strtotime('197 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+203 days', $menstrual_stamp)).'</div><div class="darkgray milestone">Baby can breathe</div>
        </div>
        <div class="clearfix"></div>
        <div class="icon immune"></div>
        <div class="ms_full">
          <div class="ms_weeks purple"><span class="pink">Week 33</span> | '.date('M d, Y', strtotime('225 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+231 days', $menstrual_stamp)).'</div><div class="darkgray milestone">Immune system is maturing</div>
        </div>
        <div class="clearfix"></div>
        <div class="icon full"></div>
        <div class="ms_full">
          <div class="ms_weeks purple"><span class="pink">Week 37</span> | '.date('M d, Y', strtotime('253 days', $menstrual_stamp)).' - '.date('M d, Y', strtotime('+259 days', $menstrual_stamp)).'</div><div class="darkgray milestone">Baby is considered full term</div>
        </div>
        <div class="clearfix"></div>
      </div>
      <div class="divider"></div>
    </div><!--pregcalc-->';


  
}
$html .='<div class="cordcare"><a href="https://americanpregnancy.org/corp-sponsors/affordable-cord-care/">This page presented by <span class="image"></span></a></div><div class="pc-form"><h3 class="black satisfy develop">Pregnancy Calendar and Due Date Calculator</h3>Calculate Based On: <select name="calctype" id="cal_select">
<option value="period">Last Period</option>
<option value="conception">Conception Date</option>
<option value="duedate">Due Date</option>
</select>';
//period form
    $html .='
<form onsubmit="return pregnancyCalc(this);" action="" class="preg-calc form-horizontal period" role="form">
  
  <!-- Begin Form -->

  <div id="preg_calc_tool">

            <div class="panel panel-info">
                
              <div class="panel-body">

                <div class="form-group">
                  <label for="menstrual" class="control-label mens_label col-sm-12 col-lg-6">
                    First Day of Last Menstrual Period<strong class="required">*</strong>
                        </label>
                        <script type="text/javascript">
                            function changevalue(a){
                              jQuery("#"+a).attr("value","");
                              jQuery("#"+a).removeClass("hint-value");
                          }
                          function unhide(a,b){
                                jQuery("#"+a).attr("class",b);
                            }
                        </script>
                        <div class="col-sm-12 col-lg-6">
                            <div class="input-group date" id="datetimepicker1">
                                <input type="date" id="menstrual" class="form-control" name="menstrual" value="'.$menstrual.'" class="medium" maxlength="10" placeholder="05/12/2015">
                                
                            </div><!--date-->
                        </div><!--col-sm-7-->
                </div><!--form-group-->
       
                <div class="form-group">
                  <div class="col-sm-12 col-lg-6 marginr-10">
                    <label for="cycle" class="control-label">
                      Average Length of Cycles
                    </label>
                  </div>
                  <div class="col-sm-12 col-lg-6">
                    <input name="cycle" id="cycle" value="28" class="form-control cycle" size="2" maxlength="2" type="text" />
                  </div><!--col-sm-7-->

                </div><!--form-group-->
                <div class="col-sm-12" style="clear: both;">
                  <div class="help-block alert alert-info">From first day of your period to the first day of your next period. Ranges from: 22 to 44. Default = 28 <em>Optional:</em> Leave 28 if unsure.</div>
                </div>
                <div class="panel-footer panel-info">
                  <button class="btn btn-warning btn-block" value="Calculate!" type="submit" onclick="unhide("results","normal")"><i class="fa fa-calendar fa-lg"></i>  Get The Date!</button> 
                </div><!--panel-footer-->

              </div><!--panel-body-->

            </div><!--panel-->
          </div><!--preg_calc_tool-->
    
</form>';

//due date
$html .='
<form onsubmit="return pregnancyCalc(this);" action="" class="preg-calc form-horizontal duedate" role="form">
  
  <!-- Begin Form -->

  <div id="preg_calc_tool">

            <div class="panel panel-info">
                
                <div class="panel-body">

                  <div class="form-group">
                    <label for="menstrual" class="control-label due col-sm-4">
                      Your Due Date<strong class="required">*</strong>
                    </label>
                      <script type="text/javascript">
                          function changevalue(a){
                            jQuery("#"+a).attr("value","");
                            jQuery("#"+a).removeClass("hint-value");
                        }
                        function unhide(a,b){
                              jQuery("#"+a).attr("class",b);
                          }
                      </script>
                      <div class="col-sm-7">
                        <div class="input-group date" id="datetimepicker1">
                          <input type="date" id="duedate" class="form-control" name="duedate" value="'.$duedate.'" class="medium" maxlength="10" placeholder="05/12/2015">
                            
                        </div><!--date-->
                      </div><!--col-sm-7-->
                    </div><!--form-group-->

                  <div class="panel-footer panel-info">
                    <button class="btn btn-warning btn-block" value="Calculate!" type="submit" onclick="unhide("results","normal")"><i class="fa fa-calendar fa-lg"></i>  Calculate!</button> 
                  </div><!--panel-info-->
                </div><!--panel-body-->
            </div><!--panel-->

  </div><!--preg_calc_tool-->
    
</form>';

//conception
$html .='
<form onsubmit="return pregnancyCalc(this);" action="" class="preg-calc form-horizontal conception" role="form">
  
  <!-- Begin Form -->

  <div id="preg_calc_tool">

            <div class="panel panel-info">
                
                <div class="panel-body">

                <div class="form-group">
                  <label for="menstrual" class="control-label concept col-sm-4">
                    Your Conception Date<strong class="required">*</strong>
                        </label>
                        <script type="text/javascript">
                            function changevalue(a){
                              jQuery("#"+a).attr("value","");
                              jQuery("#"+a).removeClass("hint-value");
                          }
                          function unhide(a,b){
                                jQuery("#"+a).attr("class",b);
                            }
                        </script>
                        <div class="col-sm-7">
                            <div class="input-group date" id="datetimepicker1">
                                <input type="date" id="conception" class="form-control" name="conception" value="'.$conception.'" class="medium" maxlength="10" placeholder="05/12/2015">
                                
                            </div>
                        </div>
                </div>
         
                
                </div>


                <div class="panel-footer panel-info">
                    <button class="btn btn-warning btn-block" value="Calculate!" type="submit" onclick="unhide("results","normal")"><i class="fa fa-calendar fa-lg"></i>  Calculate!</button> 
                </div>

            </div>

  </div>
    
</form>
<div class="alert alert-warning">This is not a diagnosis. The calculations that are provided are estimates based on averages.</div>
</div>
<script type="text/javascript">
  
jQuery(document).ready(function($){
  jQuery(function($) {
    $(".preg-calc").hide();
    $(".period").show();
  $("#cal_select").change(function(){
    $(".preg-calc").hide();
    $("." + $(this).val()).show();
  });
});
  $("#slides").slick({
    centerMode: true,
    centerPadding: "40px",
    slidesToShow: 1,
    initialSlide: '.$slide.',
    responsive: [
    {
      breakpoint: 768,
      settings: {
        arrows: true,
        centerMode: true,
        centerPadding: "0px",
        slidesToShow: 3
      }
    },
    {
      breakpoint: 480,
      settings: {
        arrows: true,
        centerMode: true,
        centerPadding: "0px",
        slidesToShow: 1
      }
    }
  ]
  });
});
  </script>';


    return $html;
}


add_shortcode( 'apapregcalc', 'APA_Pregnancy_Calculator' );