<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 5/16/2023
 * Time: 11:32 AM
 */
class WPAjax
{
    private $success = 0;
    private $error = 0;
    private $response = 0;

    function __construct($function)
    {
        if (method_exists($this, $function)) {
            // Runt he function
            $this->$function();
        } else {
            $this->error = 1;
            $this->response = 'Function not found for ' . $function;
        }
        echo $this->getResponse();
        session_write_close();
        exit;
    }

    public function getResponse()
    {
        // Prepare response array
        $json = Array(
            'success' => $this->success,
            'error' => $this->error,
            'response' => $this->response
        );
        $output = $json['response'];

        return $output;
    }
    private function approveMember()
    {
        $id = $_REQUEST['member_id'];
        $member = new Member($id);
        if($member->getCustomField('status') == "Pending") {
            // new registration
            // update member status
            update_post_meta($member->id(), 'wpcf-status', "Active");

            // create wordpress user account
            $display_name = $member->getDisplayName();
            $password = wp_generate_password( $length = 12, $include_standard_special_chars = false );
            $user_id = wp_insert_user(array(
                'user_login' => $member->getCustomField('member-email'),
                'user_pass' => $password,
                'user_email' => $member->getCustomField('member-email'),
                'first_name' => $member->getCustomField('member-firstname'),
                'last_name' => $member->getCustomField('member-surname'),
                'display_name' => $display_name,
                'role' => 'member'
            ));
            // update user field
            update_user_meta($user_id, 'wpcf-user-membership-id', $member->id());

            // send email to user
            $headers = array('Content-Type: text/html; charset=UTF-8');
            $message = '<html><head></head><body>';
            $message .= '<div style="padding:40px;">' . get_field('membership_approved',5) . '</div>';
            $message .= '</body></html>';
            $message = str_replace('{firstname}', $member->getCustomField('member-firstname'), $message);
            $message = str_replace('{lastname}', $member->getCustomField('member-surname'), $message);
            $message = str_replace('{username}', $member->getCustomField('member-email'), $message);
            $message = str_replace('{password}', $password, $message);
            $email = $member->getCustomField('member-email');
            $email = 'aaron.zame@gmail.com';
            wp_mail($email, 'Welcome to your NTDTC membership!', $message, $headers);
        } else if($member->getCustomField('status') == "Pending renewal"){
            // renewing membership
            update_post_meta($member->id(), 'wpcf-status', "Active");
            // send email to user
            $headers = array('Content-Type: text/html; charset=UTF-8');
            $message = '<html><head></head><body>';
            $message .= '<div style="padding:40px;">' . get_field('membership_renewed',5) . '</div>';
            $message .= '</body></html>';
            $message = str_replace('{firstname}', $member->getCustomField('member-firstname'), $message);
            $email = $member->getCustomField('member-email');
            $email = 'aaron.zame@gmail.com';
            wp_mail($email, 'Your NTDTC membership has been renewed', $message, $headers);
        } else {
            // do nothing
        }
        //sign up to mailchimp
        $member->subscribeUserMailChimp($member->getCustomField('member-email'));
        // redirect to member screen
        $url = get_admin_url() . 'edit.php?post_type=member';
        $this->response = $url;
    }
    private function approveClassEnrolment()
    {
        $id = $_REQUEST['enrolment_id'];
        $enrolment = new Enrolment($id);
        update_post_meta($enrolment->id(), 'wpcf-enrolment-status', "Enrolled");
        $class_id = $enrolment->getClassID();
        $url = get_admin_url() . 'post.php?post=' . $class_id . '&action=edit';
        $this->response = $url;
    }
}