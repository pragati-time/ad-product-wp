<?php

/*
 * Template Name: Quiz Page
 */
  get_header();
  if(function_exists('get_field') ){
    $questions = get_field('questions', $post->ID);
    $quiz_type = get_field('quiz_type', $post->ID);
  }
  ?>
    <div class="wrapper row ">
        <div class="col-md-8">

            <form name="quiz" method="POST" id="quizForm" class="myForm">
            <input type="hidden" name="quiz_type" id="quiz_type" value="<?php echo $quiz_type; ?>">
            <?php if (!empty($questions)) {
                    $i = 0;
                    foreach($questions as $q) {
                      //print_r($q);exit;
                      $layout = $q['layout'];
                      $layout_class = '';
                      if($layout === 'Grid') {
                       $layout_class =  'col-md-4';
                      }
                      else {
                        $layout_class = 'list';
                      }
              ?>
            <div class="quiz-wrapper row question<?php echo $i; ?>">
                <div class="quiz-title col-md-12">
                  <?php echo $q['question']; ?>
                </div>
                <div class="quiz-answers col-md-12 q<?php echo $i; ?>">
                <?php if (!empty($q['answers'])) {
                        $j = 0;
                        foreach($q['answers'] as $a) {
                          $answer = $a['answer'];
                ?>

                    <label class="answer<?php echo $j; ?>">
                          <div class="quiz-answer <?php echo $layout_class ?>">
                          <input type="radio" class="preview-radio" name="q<?php echo $i; ?>" class="answer" data-id="<?php echo $a['correct_answer'];?>" value=<?php echo "'$answer'"; ?> >
                          <span><?php echo $answer; ?></span>
                          </div>
                          </label>
                <?php
                          $j++;
                        }
                } ?>
                </div>
            </div>
            <?php
                    $i++;
                  }
                    } ?>
            </form>
            <div id="quiz-results"></div>
            <div id="poll-results"></div>
        </div>

    </div>
  <?php
  get_footer();
  ?>
<script>
  $(document).ready(function(){
    $('#quiz-results').hide();
    $('#poll-results').hide();
    var quiz_type = $('#quiz_type').val();
    var check_question = [];
    $('.quiz-wrapper').each(function(){
      var question_name = $(this).find('.quiz-answer input').attr('name');
      check_question.push(question_name);
    });
    var check_questionCount = check_question.length;
    var clicked = [];
    var counter = 0;
    var correct_ans_counter = 0;
    var vals = {};

    $('.quiz-wrapper label').on('click', function(){
      $.each(check_question, function(i,l) {
        if ($('input[name="'+l+'"]').is(':checked')){
           if ($.inArray(l, clicked) <= -1) {
              clicked.push(l);
              var val1 = $('input[name="'+l+'"]:checked').val();
              var val_chk = $('input[name="'+l+'"]:checked').attr('data-id');
              var myL =  l.substring(1, l.length);
              vals[myL] = val1;
              counter = counter + 1;
              if(quiz_type == 'Quiz'){
              if(val_chk == 1)
              {
                correct_ans_counter = correct_ans_counter + 1;
                 $( '.'+l+ ' label .quiz-answer input[name="'+l+'"]:checked' ).parent().css('border', '2px solid #008000' );
              }
              else{
                $( '.'+l+ ' label .quiz-answer input[name="'+l+'"]:checked' ).parent().css( 'border','2px solid #FF0000' );
              }
              }
              $('.'+l+ ' label .quiz-answer input:radio').attr('disabled',true);
              $('.'+l+ ' label .quiz-answer').css('background-color', '#e8e8e8');
           }
        }
      });

      if (counter === check_questionCount) {
         if(quiz_type == 'Quiz'){
          var get_correct_percentage=(correct_ans_counter/check_questionCount)*100;
          if(get_correct_percentage <=50){
          $('#quiz-results').html("<h3>Yikes! You didn’t do too hot, but don’t be hard on yourself. There’s always another cheese to taste and another quiz to take. Have a great life!</h3>");
          }else if((get_correct_percentage > 50) && (get_correct_percentage <=70)){
          $('#quiz-results').html("<h3>You did OK! But you could have done better. There’s always room for improvement, keep it up!</h3>");
          }else{
          $('#quiz-results').html("<h3>Bow down. You’re a real  expert! Congratulations!</h3>");
          }
          $('#quiz-results').show();
          var scrollPos =  $("#quiz-results").offset().top;
          $(window).scrollTop(scrollPos);
          $('#quiz-results').focus();
        }
        else{
          $( ".AcceptedBar").progressbar({
            value: 37
          });

        $.each(vals, function(key, ans){
          $('#poll-results').html("80 % <div class = \"progress\"><div class = \"progress-bar progress-bar-info\" role = \"progressbar\" aria-valuenow = \"60\" aria-valuemin = \"0\" aria-valuemax = \"100\" style = \"width: 80%;\"> <span class = \"sr-only\">90% </span></div></div>"+ans+"");
        });
          $('#poll-results').show();
          $('#poll-results').focus();
        }

      }
    });



  });
</script>
