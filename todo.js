$(function() {
  'use strict';

  $('#new_task').focus();

  // 更新
  $('#tasks').on('click', '.update_task', function() {
    var id = $(this).parents('li').data('id');

    $.post('_ajax.php', {
      id: id,
      mode: 'update',
      token: $('#token').val()
    }, function(res) {
      if (res.state === '1') {
        $('#task_' + id).find('.task_title').addClass('done');
      } else {
        $('#task_' + id).find('.task_title').removeClass('done');
      }
    })
  });

  // 削除
  $('#tasks').on('click', '.delete_task', function() {
    var id = $(this).parents('li').data('id');

    if (confirm('are you sure?')) {
      $.post('_ajax.php', {
        id: id,
        mode: 'delete',
        token: $('#token').val()
      }, function() {
        $('#task_' + id).fadeOut(800);
      });
    }
  });

  // 作成
  $('#new_task_form').on('submit', function() {
    var title = $('#new_task').val();

    $.post('_ajax.php', {
      title: title,
      mode: 'create',
      token: $('#token').val()
    }, function(res) {
      var $li = $('#task_template').clone();
      $li
        .attr('id', 'task_' + res.id)
        .data('id', res.id)
        .find('.task_title').text(title);
        $('#tasks').prepend($li.fadeIn());
        $('#new_task').val('').focus();
    });
    return false;
  });

});
