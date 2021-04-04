<?php

/*
Plugin Name: Form vehicle inspection
Description: Форма заявки по осмотру авто.
Version: 1.0
Author: Vladislav
*/


add_action('admin_menu', 'register_my_page');
function register_my_page()
{
    add_menu_page('Заявки', 'Заявки', 1, 'my_page_slug', 'my_page_function');
}

function get_array_bid(){
    global $wpdb;
    return $wpdb->get_results("SELECT * FROM wp_bid");
}

function my_page_function()
{
    if(isset($_POST['del_bid'])){
        global $wpdb;
        $id = $_POST['del_bid'];
        $wpdb->query("DELETE FROM wp_bid WHERE id=$id");
    }
    echo '<table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Ф.И.О</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Veh</th>
          <th>Cause</th>
        </tr>
      </thead>
      <tbody>';
    foreach (get_array_bid() as $bid){
        echo "<tr><td>" . $bid->ID
            . "</td><td>" . $bid->bid_fio
            . "</td><td>" . $bid->bid_email
            . "</td><td>" . $bid->bid_phone
            . "</td><td>" . $bid->bid_veh
            . "</td><td>" . $bid->bid_cause
            . "</td><td>
                <form method='post'>
                    <input hidden name='del_bid' value='" . $bid->ID . "'>
                    <button>Удалить</button>
                 </form>
              </td></tr>";
    }
    echo "</tbody>";
}


function vehinsp_form()
{
    echo '
    <form id="form_feedback" method="post">
           <input type="text" name="feedback_fio" id="feedback_fio" placeholder="ФИО" required><br>
           <input type="email" name="feedback_email" id="feedback_email" placeholder="email"><br>
           <input type="tel" name="feedback_tel" id="feedback_tel" placeholder="Номер телефона"><br>
           <input type="text" name="feedback_veh" id="feedback_veh" placeholder="Марка автомобиля"><br>
           <textarea name="feedback_cause" id="feedback_cause" cols="30" rows="10" placeholder="Причина обращения"></textarea>
            <input type="submit" id="feedback_submit" name="submit" value="Отправить заявку">
    </form>
    ';
    if (isset($_POST['submit'])) {
        global $errors;
        $errors = new WP_Error;
        $fio = $_POST['feedback_fio'];
        $email = $_POST['feedback_email'];
        $tel = $_POST['feedback_tel'];
        $veh = $_POST['feedback_veh'];
        $cause = $_POST['feedback_cause'];

        if (empty($email) || empty($fio) || empty($tel) || empty($veh) || empty($cause)) {
            $errors->add('info', ' Одно из полей не заполнено');
        } elseif (strlen($tel) < 11) {
            $errors->add('info', 'Телефон должен содержать не менее 11 цифр');
        } elseif (!preg_match('/^[а-яёА-ЯЁ\s]+$/u', $fio)) {
            $errors->add('info', 'ФИО - только кирилица');
        }
        global $wpdb;
        if ($wpdb->get_results("SELECT bid_email  FROM wp_bid WHERE bid_email='$email'")) {
            $errors->add('info', 'С это почты уже была отправлена заявка');
        }
        if (is_wp_error($errors)) {
            foreach ($errors->get_error_messages() as $error) {
                echo '<div>';
                echo '<strong>ERROR</strong>:';
                echo $error . '<br/>';
                echo '</div>';
            }
        }
        if (empty($errors->errors)) {
            $wpdb->insert(
                'wp_bid',
                array(
                    'bid_fio' => $fio,
                    'bid_email' => $email,
                    'bid_phone' => $tel,
                    'bid_veh' => $veh,
                    'bid_cause' => $cause,
                )
            );
            echo 'Заявка успешно принята';
        }
    }
}
